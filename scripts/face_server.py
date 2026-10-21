"""Nhận diện khuôn mặt chạy nền.

Model InsightFace chỉ nạp một lần. PHP gọi HTTP localhost thay vì
mở tiến trình Python mới cho mỗi lần chấm công.
"""
import base64
import json
import os
import threading
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer

os.environ.setdefault("ORT_LOGGING_LEVEL", "3")

import cv2
import numpy as np

HOST = "127.0.0.1"
PORT = 18765
MAX_SIDE = 480
DET_SIZE = (320, 320)

face_app = None
ready = False
boot_error = ""


def resize_max(img, max_side):
    h, w = img.shape[:2]
    scale = max_side / float(max(h, w))
    if scale >= 1:
        return img
    return cv2.resize(img, (int(w * scale), int(h * scale)), interpolation=cv2.INTER_AREA)


def decode_image(image_b64):
    if not image_b64:
        return None
    if "," in image_b64[:40]:
        image_b64 = image_b64.split(",", 1)[1]
    raw = base64.b64decode(image_b64)
    arr = np.frombuffer(raw, dtype=np.uint8)
    img = cv2.imdecode(arr, cv2.IMREAD_COLOR)
    if img is None:
        return None
    return resize_max(img, MAX_SIDE)


def read_image(payload):
    if payload.get("image_base64"):
        return decode_image(payload["image_base64"])
    path = payload.get("image_path")
    if not path or not os.path.exists(path):
        return None
    img = cv2.imread(path)
    if img is None:
        return None
    return resize_max(img, MAX_SIDE)


def largest_face(img):
    faces = face_app.get(img)
    if not faces:
        return None
    return max(faces, key=lambda f: (f.bbox[2] - f.bbox[0]) * (f.bbox[3] - f.bbox[1]))


def cosine(a, b):
    v1 = np.asarray(a, dtype=np.float32)
    v2 = np.asarray(b, dtype=np.float32)
    n1 = np.linalg.norm(v1)
    n2 = np.linalg.norm(v2)
    if n1 == 0 or n2 == 0:
        return 0.0
    return float(np.dot(v1, v2) / (n1 * n2))


def boot():
    global face_app, ready, boot_error
    try:
        from insightface.app import FaceAnalysis

        face_app = FaceAnalysis(name="buffalo_l", providers=["CPUExecutionProvider"])
        face_app.prepare(ctx_id=-1, det_size=DET_SIZE)
        ready = True
    except Exception as exc:
        boot_error = str(exc)
        ready = False


class Handler(BaseHTTPRequestHandler):
    protocol_version = "HTTP/1.1"

    def log_message(self, fmt, *args):
        return

    def _send(self, code, payload):
        body = json.dumps(payload).encode("utf-8")
        self.send_response(code)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def do_GET(self):
        if self.path.split("?", 1)[0] != "/health":
            self._send(404, {"ok": False})
            return
        self._send(200, {"ok": True, "ready": ready, "error": boot_error})

    def do_POST(self):
        if not ready:
            self._send(503, {"success": False, "error": boot_error or "Model đang nạp"})
            return
        try:
            length = int(self.headers.get("Content-Length", "0"))
            payload = json.loads(self.rfile.read(length) or b"{}")
        except Exception as exc:
            self._send(400, {"success": False, "error": str(exc)})
            return

        action = payload.get("action")
        try:
            if action == "verify":
                img = read_image(payload)
                if img is None:
                    self._send(200, {"success": False, "error": "Không đọc được ảnh"})
                    return
                face = largest_face(img)
                if face is None:
                    self._send(200, {"success": True, "face_count": 0, "confidence": 0})
                    return
                confidence = cosine(face.normed_embedding, payload.get("embedding") or [])
                self._send(200, {"success": True, "face_count": 1, "confidence": confidence})
                return

            if action == "embedding":
                img = read_image(payload)
                if img is None:
                    self._send(200, {"success": False, "error": "Không đọc được ảnh"})
                    return
                face = largest_face(img)
                if face is None:
                    self._send(200, {"success": False, "error": "No face detected", "face_count": 0})
                    return
                self._send(200, {
                    "success": True,
                    "face_count": 1,
                    "embedding": face.normed_embedding.astype(float).tolist(),
                })
                return

            self._send(400, {"success": False, "error": "Unknown action"})
        except Exception as exc:
            self._send(500, {"success": False, "error": str(exc)})


if __name__ == "__main__":
    threading.Thread(target=boot, daemon=True).start()
    server = ThreadingHTTPServer((HOST, PORT), Handler)
    server.serve_forever()

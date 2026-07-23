import cv2
import sys
import os
from insightface.app import FaceAnalysis

# Khởi tạo
print("🔄 Khởi tạo model...")
app = FaceAnalysis(name='buffalo_l')
app.prepare(ctx_id=-1, det_size=(640, 640))
print("✅ Model ready")

# Kiểm tra ảnh
image_path = 'test.jpg'  # THAY ĐỔI ĐƯỜNG DẪN ẢNH CỦA BẠN

if not os.path.exists(image_path):
    print(f"❌ File không tồn tại: {image_path}")
    sys.exit(1)

print(f"📸 Đọc ảnh: {image_path}")
img = cv2.imread(image_path)

if img is None:
    print("❌ Không đọc được ảnh")
    sys.exit(1)

print(f"✅ Ảnh kích thước: {img.shape}")

# Phát hiện khuôn mặt
faces = app.get(img)

if faces is None or len(faces) == 0:
    print("❌ KHÔNG PHÁT HIỆN KHUÔN MẶT")
    print("💡 Thử ảnh khác rõ mặt hơn")
else:
    print(f"✅ PHÁT HIỆN {len(faces)} KHUÔN MẶT")
    embedding = faces[0].normed_embedding
    print(f"✅ Embedding: {len(embedding)} chiều")

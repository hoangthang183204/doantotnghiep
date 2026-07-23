import sys
import json
import cv2
import numpy as np
import os
import warnings
warnings.filterwarnings('ignore')

from insightface.app import FaceAnalysis

app = FaceAnalysis(name='buffalo_l')
app.prepare(ctx_id=-1, det_size=(640, 640))

def detect_face(image_path):
    try:
        if not os.path.exists(image_path):
            return {'success': False, 'error': f'File not found: {image_path}'}
        img = cv2.imread(image_path)
        if img is None:
            return {'success': False, 'error': f'Cannot read image: {image_path}'}
        faces = app.get(img)
        face_count = len(faces) if faces else 0
        return {'success': True, 'face_count': face_count}
    except Exception as e:
        return {'success': False, 'error': str(e)}

def get_face_embedding(image_path):
    try:
        if not os.path.exists(image_path):
            return {'success': False, 'error': f'File not found: {image_path}'}
        img = cv2.imread(image_path)
        if img is None:
            return {'success': False, 'error': f'Cannot read image: {image_path}'}
        faces = app.get(img)
        if not faces or len(faces) == 0:
            return {'success': False, 'error': 'No face detected'}
        embedding = faces[0].normed_embedding
        return {'success': True, 'embedding': embedding.tolist()}
    except Exception as e:
        return {'success': False, 'error': str(e)}

if __name__ == '__main__':
    try:
        if len(sys.argv) < 2:
            print(json.dumps({'success': False, 'error': 'No input data'}))
            sys.exit(1)
        data = json.loads(sys.argv[1])
        action = data.get('action')
        if action == 'detect':
            result = detect_face(data.get('image_path'))
        elif action == 'embedding':
            result = get_face_embedding(data.get('image_path'))
        else:
            result = {'success': False, 'error': f'Unknown action: {action}'}
        print(json.dumps(result))
    except json.JSONDecodeError as e:
        print(json.dumps({'success': False, 'error': f'JSON decode error: {str(e)}'}))
    except Exception as e:
        print(json.dumps({'success': False, 'error': str(e)}))
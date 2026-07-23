import cv2
import time

# Mở webcam
cap = cv2.VideoCapture(0)

if not cap.isOpened():
    print("❌ Không thể mở webcam")
    exit()

print("📸 Đang mở webcam... Nhấn SPACE để chụp, ESC để thoát")

while True:
    ret, frame = cap.read()
    if not ret:
        break
    
    # Hiển thị
    cv2.imshow('Press SPACE to capture', frame)
    
    key = cv2.waitKey(1)
    if key == 32:  # SPACE
        cv2.imwrite('test.jpg', frame)
        print("✅ Đã chụp ảnh: test.jpg")
        break
    elif key == 27:  # ESC
        break

cap.release()
cv2.destroyAllWindows()

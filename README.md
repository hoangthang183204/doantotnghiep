# HR Flow

Website quản lý nhân sự cho đồ án tốt nghiệp. Hệ thống gồm năm vai trò: Super Admin, HR, Kế toán, Trưởng phòng và Nhân viên.

HR quản lý hồ sơ, hợp đồng, chấm công, nghỉ phép và chốt số liệu cuối tháng. Kế toán tính lương, BHXH, thuế TNCN, duyệt ứng lương và xuất phiếu lương sau khi HR đã chốt. Nhân viên chấm công, xin phép, xem lương và ký hợp đồng của mình.

## Yêu cầu

- PHP 8.2 trở lên, bật extension `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`
- Composer
- MySQL 8
- Node.js 18 trở lên (chỉ cần khi build lại CSS)
- Python 3.10 trở lên, chỉ khi muốn thử chấm công bằng khuôn mặt

Không cần Redis. Cache, session và hàng đợi đang dùng MySQL.

## Cài đặt

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Trên Windows, nếu không có lệnh `cp`, copy file `.env.example` thành `.env` rồi chạy `php artisan key:generate`.

Sửa `.env` để dùng MySQL. File mẫu đang để SQLite, dự án này chạy trên MySQL:

```env
APP_NAME="HR Flow"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrflow
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

Tạo database `hrflow` trong MySQL, rồi chạy:

```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=NguoiDungVaiTroSeeder
php artisan storage:link
php artisan serve
```

Mở http://127.0.0.1:8000

`DatabaseSeeder` tạo người dùng nhưng chưa ghi bảng nối vai trò. Lệnh `NguoiDungVaiTroSeeder` là bước bắt buộc, nếu bỏ qua thì đăng nhập được mà menu trống.

`migrate:fresh` xóa toàn bộ dữ liệu hiện có. Chỉ dùng khi dựng máy mới hoặc muốn dữ liệu mẫu sạch.

## Tài khoản mẫu

Mật khẩu của dữ liệu seed là `123456`. Database đã dùng lâu có thể đã đổi mật khẩu.

| Vai trò | Email | Việc nên thử |
| --- | --- | --- |
| Super Admin | admin@hrflow.com | Vào mọi chức năng quản trị, tính lương không cần đợi HR chốt |
| HR | hr@hrflow.com | Hồ sơ, hợp đồng, chấm công, nghỉ phép, chốt dữ liệu tháng |
| Kế toán | ke.toan@hrflow.com | Tính lương sau khi HR chốt, BHXH, thuế, ứng lương, phiếu lương |
| Trưởng phòng | it.manager@hrflow.com | Duyệt đơn và xem nhân viên trong phòng mình |
| Nhân viên | dev@hrflow.com | Chấm công, xin phép, lương của tôi, hợp đồng của tôi |

Super Admin không hiện menu cá nhân. Các vai trò còn lại thấy hai nhóm **Công của tôi** và **Cá nhân**.

## Kiểm tra luồng lương

1. Đăng nhập HR, vào **Quản lý chấm công**, rồi **Chốt dữ liệu tháng**. Chọn tháng và bấm chốt. Kế toán nhận thông báo đã chốt.
2. Đăng xuất, đăng nhập Kế toán, vào **Lương** rồi **Tính lương**. Nếu HR chưa chốt tháng đó, nút tính lương bị chặn.
3. Sau khi có bảng lương nháp, kế toán điều chỉnh phụ cấp hoặc khấu trừ, duyệt, gửi phiếu lương và đánh dấu đã chi trả. HR nhận thông báo bảng lương đã được chi trả.
4. Đăng nhập Super Admin để tính lương một tháng chưa chốt. Tài khoản này không bị chặn.

Kế toán chỉ sửa được tài khoản ngân hàng và mã số thuế trên hồ sơ. Không duyệt đơn nghỉ, không tạo hợp đồng và không xem khuôn mặt.

## Chấm công khuôn mặt

Phần còn lại của web chạy được khi chưa bật dịch vụ này.

```bash
pip install insightface opencv-python onnxruntime onnx numpy
python scripts/face_server.py
```

Lần đầu InsightFace tải model `buffalo_l`, có thể mất vài phút. Khi terminal in ra server đang nghe `127.0.0.1:18765`, giữ cửa sổ đó mở rồi vào trang chấm công của nhân viên.

Trình duyệt cần camera. Ảnh gốc không được lưu. Hệ thống chỉ giữ đặc trưng khuôn mặt. Gửi lại cùng một lượt chấm công không tạo thêm bản ghi.

## Lịch và xuất Excel

Các việc nền chỉ chạy khi bật lịch:

```bash
php artisan schedule:work
```

Lịch gồm nhắc chấm công lúc 08:45, sao lưu database lúc 01:30, chốt lương và gửi email phiếu lương lúc 02:00 ngày 1 hàng tháng. Mail mặc định ghi vào `storage/logs/laravel.log`, không gửi ra hộp thư thật.

Xuất Excel bảng lương đi qua hàng đợi `database`. `schedule:work` đã kéo job ngắn mỗi phút. Muốn xử lý ngay:

```bash
php artisan queue:work --stop-when-empty
```

## Test tự động

```bash
php artisan test
```

PHPUnit dùng SQLite trong bộ nhớ, không đụng database `hrflow`. Bộ test hiện chỉ kiểm tra route và ví dụ mặc định của Laravel, chưa phủ hết nghiệp vụ lương.

## Cấu trúc chính

```text
app/Http/Controllers/Admin       Quản trị: hồ sơ, công, lương, hợp đồng
app/Http/Controllers/Employee    Nhân viên: chấm công, phép, phiếu lương
app/Services/TinhLuongService.php    Tính lương, BHXH, thuế
app/Services/KhoaNghiepVu.php        Khóa không cho tính lương trùng
scripts/face_server.py               Nhận diện khuôn mặt chạy nền
routes/web.php                       Toàn bộ đường dẫn web
database/seeders                     Dữ liệu mẫu
```

## Lưu ý

- Không commit file `.env`.
- `APP_KEY` dùng để mã hóa căn cước, mã số thuế, số BHXH, tài khoản ngân hàng và đặc trưng khuôn mặt. Đổi key thì dữ liệu đã mã hóa không đọc lại được.
- HTTPS chỉ bật khi `APP_ENV=production` hoặc `FORCE_HTTPS=true`. Máy local giữ `APP_ENV=local`.

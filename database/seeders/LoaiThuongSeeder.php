<?php

namespace Database\Seeders;

use App\Models\LoaiThuong;
use Illuminate\Database\Seeder;

/**
 * Danh mục loại thưởng phổ biến ở doanh nghiệp VN.
 * Đây chỉ là dữ liệu khởi tạo — admin/HR tự thêm/sửa/xoá tuỳ công ty.
 */
class LoaiThuongSeeder extends Seeder
{
    public function run(): void
    {
        $loaiThuongs = [
            [
                'ten' => 'Thưởng chuyên cần',
                'ma' => 'CHUYEN_CAN',
                'mo_ta' => 'Thưởng cho nhân viên đi làm đầy đủ, không đi muộn về sớm trong tháng.',
                'hinh_thuc_mac_dinh' => 'dinh_ky',
                'cach_tinh' => 'so_tien_co_dinh',
                'gia_tri_mac_dinh' => 300000,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng hiệu suất (KPI)',
                'ma' => 'KPI',
                'mo_ta' => 'Thưởng theo kết quả đánh giá KPI hàng tháng.',
                'hinh_thuc_mac_dinh' => 'dinh_ky',
                'cach_tinh' => 'phan_tram_luong_cb',
                'gia_tri_mac_dinh' => 10,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng trách nhiệm',
                'ma' => 'TRACH_NHIEM',
                'mo_ta' => 'Thưởng thêm cho vị trí kiêm nhiệm, quản lý nhóm.',
                'hinh_thuc_mac_dinh' => 'dinh_ky',
                'cach_tinh' => 'phan_tram_luong_cb',
                'gia_tri_mac_dinh' => 15,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Lương tháng 13',
                'ma' => 'THANG_13',
                'mo_ta' => 'Khoản thưởng cuối năm, thường bằng 1 tháng lương cơ bản.',
                'hinh_thuc_mac_dinh' => 'mot_lan',
                'cach_tinh' => 'phan_tram_luong_cb',
                'gia_tri_mac_dinh' => 100,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng Tết Nguyên đán',
                'ma' => 'TET',
                'mo_ta' => 'Thưởng dịp Tết Âm lịch, mức thưởng do công ty quyết định từng năm.',
                'hinh_thuc_mac_dinh' => 'mot_lan',
                'cach_tinh' => 'so_tien_co_dinh',
                'gia_tri_mac_dinh' => 5000000,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng lễ (30/4, 1/5, 2/9, Tết Dương lịch)',
                'ma' => 'LE',
                'mo_ta' => 'Thưởng các dịp lễ trong năm, áp dụng 1 lần cho kỳ lương tương ứng.',
                'hinh_thuc_mac_dinh' => 'mot_lan',
                'cach_tinh' => 'so_tien_co_dinh',
                'gia_tri_mac_dinh' => 500000,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng thâm niên',
                'ma' => 'THAM_NIEN',
                'mo_ta' => 'Thưởng ghi nhận số năm gắn bó với công ty.',
                'hinh_thuc_mac_dinh' => 'mot_lan',
                'cach_tinh' => 'so_tien_co_dinh',
                'gia_tri_mac_dinh' => 2000000,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng sáng kiến',
                'ma' => 'SANG_KIEN',
                'mo_ta' => 'Thưởng đột xuất cho sáng kiến, cải tiến mang lại hiệu quả.',
                'hinh_thuc_mac_dinh' => 'mot_lan',
                'cach_tinh' => 'so_tien_co_dinh',
                'gia_tri_mac_dinh' => 1000000,
                'chiu_thue' => true,
            ],
            [
                'ten' => 'Thưởng hoàn thành dự án',
                'ma' => 'DU_AN',
                'mo_ta' => 'Thưởng cho cá nhân/nhóm khi hoàn thành dự án đúng tiến độ.',
                'hinh_thuc_mac_dinh' => 'mot_lan',
                'cach_tinh' => 'so_tien_co_dinh',
                'gia_tri_mac_dinh' => 3000000,
                'chiu_thue' => true,
            ],
        ];

        foreach ($loaiThuongs as $loai) {
            LoaiThuong::updateOrCreate(
                ['ma' => $loai['ma']],
                $loai + ['trang_thai' => true]
            );
        }
    }
}

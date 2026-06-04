<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UngVienSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ung_vien')->insert([
            ['tin_tuyen_dung_id' => 1, 'ma_ho_so' => 'UV001', 'ho' => 'Nguyễn', 'ten' => 'Văn A', 'email' => 'nguyenvana@email.com', 'so_dien_thoai' => '0912345001', 'dia_chi' => 'Hà Nội', 'ngay_sinh' => '1995-05-10', 'gioi_tinh' => 'nam', 'trinh_do_hoc_van' => 'Đại học', 'so_nam_kinh_nghiem' => 3, 'luong_hien_tai' => 12000000, 'luong_mong_muon' => 15000000, 'duong_dan_cv' => '/cvs/nguyenvana.pdf', 'thu_xin_viec' => 'Tôi mong muốn được làm việc', 'url_portfolio' => null, 'url_linkedin' => null, 'ky_nang' => json_encode(['PHP', 'Laravel']), 'ngay_co_the_lam_viec' => now()->addDays(15), 'nguon_ung_tuyen' => 'linkedin', 'ten_nguoi_gioi_thieu' => null, 'trang_thai' => 'moi_nop', 'diem_phong_van' => null, 'ghi_chu_phong_van' => null, 'ly_do_tu_choi' => null, 'nguoi_dung_id' => null, 'thoi_gian_nop' => now(), 'nguoi_cap_nhat_cuoi_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['tin_tuyen_dung_id' => 2, 'ma_ho_so' => 'UV002', 'ho' => 'Trần', 'ten' => 'Thị B', 'email' => 'tranthib@email.com', 'so_dien_thoai' => '0912345002', 'dia_chi' => 'TP.HCM', 'ngay_sinh' => '1997-08-15', 'gioi_tinh' => 'nu', 'trinh_do_hoc_van' => 'Cao đẳng', 'so_nam_kinh_nghiem' => 2, 'luong_hien_tai' => 8000000, 'luong_mong_muon' => 12000000, 'duong_dan_cv' => '/cvs/tranthib.pdf', 'thu_xin_viec' => 'Tôi mong muốn được cống hiến', 'url_portfolio' => null, 'url_linkedin' => null, 'ky_nang' => json_encode(['Bán hàng']), 'ngay_co_the_lam_viec' => now()->addDays(10), 'nguon_ung_tuyen' => 'facebook', 'ten_nguoi_gioi_thieu' => 'Nguyễn Văn C', 'trang_thai' => 'moi_nop', 'diem_phong_van' => null, 'ghi_chu_phong_van' => null, 'ly_do_tu_choi' => null, 'nguoi_dung_id' => null, 'thoi_gian_nop' => now(), 'nguoi_cap_nhat_cuoi_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
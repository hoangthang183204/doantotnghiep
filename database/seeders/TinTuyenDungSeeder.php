<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TinTuyenDungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tin_tuyen_dung')->insert([
            ['tieu_de' => 'Tuyển Lập trình viên PHP', 'ma' => 'TD001', 'phong_ban_id' => 4, 'chuc_vu_id' => 7, 'vai_tro_id' => 3, 'loai_hop_dong' => 'khong_xac_dinh_thoi_han', 'cap_do_kinh_nghiem' => 'junior', 'kinh_nghiem_toi_thieu' => 1, 'kinh_nghiem_toi_da' => 3, 'luong_toi_thieu' => 10000000, 'luong_toi_da' => 15000000, 'so_vi_tri' => 3, 'mo_ta_cong_viec' => 'Phát triển web PHP/Laravel', 'yeu_cau' => json_encode(['PHP', 'Laravel']), 'phuc_loi' => json_encode(['BHXH', 'Thưởng']), 'ky_nang_yeu_cau' => json_encode(['Laravel']), 'trinh_do_hoc_van' => 'Đại học', 'han_nop_ho_so' => now()->addDays(30), 'lam_viec_tu_xa' => 0, 'tuyen_gap' => 1, 'trang_thai' => 'dang_tuyen', 'nguoi_dang_id' => 2, 'thoi_gian_dang' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['tieu_de' => 'Tuyển Nhân viên Kinh doanh', 'ma' => 'TD002', 'phong_ban_id' => 6, 'chuc_vu_id' => 11, 'vai_tro_id' => 3, 'loai_hop_dong' => 'xac_dinh_thoi_han', 'cap_do_kinh_nghiem' => 'fresher', 'kinh_nghiem_toi_thieu' => 0, 'kinh_nghiem_toi_da' => 1, 'luong_toi_thieu' => 8000000, 'luong_toi_da' => 12000000, 'so_vi_tri' => 5, 'mo_ta_cong_viec' => 'Tìm kiếm khách hàng', 'yeu_cau' => json_encode(['Giao tiếp tốt']), 'phuc_loi' => json_encode(['Hoa hồng']), 'ky_nang_yeu_cau' => json_encode(['Bán hàng']), 'trinh_do_hoc_van' => 'Cao đẳng', 'han_nop_ho_so' => now()->addDays(20), 'lam_viec_tu_xa' => 0, 'tuyen_gap' => 1, 'trang_thai' => 'dang_tuyen', 'nguoi_dang_id' => 2, 'thoi_gian_dang' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
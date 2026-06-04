<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonXinNghiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('don_xin_nghi')->insert([
            ['ma_don_nghi' => 'DN2024001', 'nguoi_dung_id' => 4, 'loai_nghi_phep_id' => 1, 'ngay_bat_dau' => '2024-12-20', 'ngay_ket_thuc' => '2024-12-22', 'so_ngay_nghi' => 3, 'ly_do' => 'Nghỉ phép năm', 'tai_lieu_ho_tro' => null, 'lien_he_khan_cap' => 'Phạm Thị E', 'sdt_khan_cap' => '0987654324', 'ban_giao_cho_id' => 2, 'ghi_chu_ban_giao' => 'Đã bàn giao công việc', 'trang_thai' => 'cho_duyet', 'cap_duyet_hien_tai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ma_don_nghi' => 'DN2024002', 'nguoi_dung_id' => 5, 'loai_nghi_phep_id' => 2, 'ngay_bat_dau' => '2024-12-18', 'ngay_ket_thuc' => '2024-12-19', 'so_ngay_nghi' => 2, 'ly_do' => 'Bị ốm', 'tai_lieu_ho_tro' => json_encode(['giay_benh_vien.pdf']), 'lien_he_khan_cap' => 'Hoàng Văn F', 'sdt_khan_cap' => '0987654325', 'ban_giao_cho_id' => 2, 'ghi_chu_ban_giao' => 'Đã bàn giao', 'trang_thai' => 'da_duyet', 'cap_duyet_hien_tai' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
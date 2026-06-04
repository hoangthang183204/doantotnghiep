<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhuCapSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phu_cap')->insert([
            ['ten' => 'Phụ cấp ăn trưa', 'ma' => 'AN_TRUA', 'mo_ta' => 'Phụ cấp ăn trưa hàng ngày', 'loai_phu_cap' => 'co_dinh', 'so_tien_mac_dinh' => 650000, 'cach_tinh' => 'so_tien_co_dinh', 'chiu_thue' => 0, 'dieu_kien_ap_dung' => json_encode(['tat_ca_nhan_vien' => true]), 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Phụ cấp xăng xe', 'ma' => 'XANG_XE', 'mo_ta' => 'Phụ cấp xăng xe hàng tháng', 'loai_phu_cap' => 'co_dinh', 'so_tien_mac_dinh' => 1000000, 'cach_tinh' => 'so_tien_co_dinh', 'chiu_thue' => 1, 'dieu_kien_ap_dung' => json_encode(['chuc_vu' => ['Trưởng phòng', 'Giám đốc']]), 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Phụ cấp trách nhiệm', 'ma' => 'TRACH_NHIEM', 'mo_ta' => 'Phụ cấp vị trí quản lý', 'loai_phu_cap' => 'theo_cap_bac', 'so_tien_mac_dinh' => 0, 'cach_tinh' => 'phan_tram_luong_cb', 'chiu_thue' => 1, 'dieu_kien_ap_dung' => json_encode(['truong_phong_tro_len' => true]), 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Phụ cấp điện thoại', 'ma' => 'DIEN_THOAI', 'mo_ta' => 'Phụ cấp điện thoại', 'loai_phu_cap' => 'co_dinh', 'so_tien_mac_dinh' => 300000, 'cach_tinh' => 'so_tien_co_dinh', 'chiu_thue' => 0, 'dieu_kien_ap_dung' => json_encode(['nhan_vien_van_phong' => true]), 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhuCapNhanVienSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phu_cap_nhan_vien')->insert([
            [
                'nguoi_dung_id' => 1,
                'phu_cap_id' => 1,
                'so_tien' => 1000000,
                'ngay_hieu_luc' => '2024-01-01',
                'ngay_ket_thuc' => null,
                'trang_thai' => 'hieu_luc',
                'ghi_chu' => 'Phụ cấp xăng xe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 2,
                'phu_cap_id' => 2,
                'so_tien' => 650000,
                'ngay_hieu_luc' => '2024-01-01',
                'ngay_ket_thuc' => null,
                'trang_thai' => 'hieu_luc',
                'ghi_chu' => 'Phụ cấp ăn trưa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 1,
                'phu_cap_id' => 3,
                'so_tien' => 2000000,
                'ngay_hieu_luc' => '2024-01-01',
                'ngay_ket_thuc' => null,
                'trang_thai' => 'hieu_luc',
                'ghi_chu' => 'Phụ cấp trách nhiệm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 3,
                'phu_cap_id' => 4,
                'so_tien' => 500000,
                'ngay_hieu_luc' => '2024-02-01',
                'ngay_ket_thuc' => null,
                'trang_thai' => 'hieu_luc',
                'ghi_chu' => 'Phụ cấp điện thoại',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 4,
                'phu_cap_id' => 5,
                'so_tien' => 300000,
                'ngay_hieu_luc' => '2024-03-01',
                'ngay_ket_thuc' => '2024-12-31',
                'trang_thai' => 'hieu_luc',
                'ghi_chu' => 'Phụ cấp độc hại',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
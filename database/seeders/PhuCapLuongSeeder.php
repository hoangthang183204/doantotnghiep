<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhuCapLuongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phu_cap_luong')->insert([
            [
                'luong_nhan_vien_id' => 1,
                'phu_cap_id' => 1,
                'so_tien' => 1000000,
                'ghi_chu' => 'Phụ cấp xăng xe tháng 1',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 1,
                'phu_cap_id' => 2,
                'so_tien' => 650000,
                'ghi_chu' => 'Phụ cấp ăn trưa tháng 1',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 1,
                'phu_cap_id' => 3,
                'so_tien' => 2000000,
                'ghi_chu' => 'Phụ cấp trách nhiệm tháng 1',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 2,
                'phu_cap_id' => 2,
                'so_tien' => 650000,
                'ghi_chu' => 'Phụ cấp ăn trưa tháng 1',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 3,
                'phu_cap_id' => 4,
                'so_tien' => 500000,
                'ghi_chu' => 'Phụ cấp điện thoại tháng 1',
                'created_at' => now(),
            ],
        ]);
    }
}
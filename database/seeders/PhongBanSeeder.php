<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhongBanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phong_ban')->insert([
            ['ten_phong_ban' => 'Ban Giám Đốc', 'ma_phong_ban' => 'BGD', 'mo_ta' => 'Ban lãnh đạo công ty', 'truong_phong_id' => null, 'ngan_sach' => 5000000000, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten_phong_ban' => 'Phòng Nhân Sự', 'ma_phong_ban' => 'HR', 'mo_ta' => 'Quản lý nhân sự và tuyển dụng', 'truong_phong_id' => null, 'ngan_sach' => 500000000, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten_phong_ban' => 'Phòng Kế Toán', 'ma_phong_ban' => 'KT', 'mo_ta' => 'Quản lý tài chính kế toán', 'truong_phong_id' => null, 'ngan_sach' => 800000000, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten_phong_ban' => 'Phòng Công Nghệ', 'ma_phong_ban' => 'IT', 'mo_ta' => 'Phát triển công nghệ', 'truong_phong_id' => null, 'ngan_sach' => 1500000000, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten_phong_ban' => 'Phòng Marketing', 'ma_phong_ban' => 'MKT', 'mo_ta' => 'Marketing và truyền thông', 'truong_phong_id' => null, 'ngan_sach' => 1000000000, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten_phong_ban' => 'Phòng Kinh Doanh', 'ma_phong_ban' => 'KD', 'mo_ta' => 'Bán hàng và CSKH', 'truong_phong_id' => null, 'ngan_sach' => 2000000000, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
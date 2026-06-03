<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhongBanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phong_ban')->insert([
            [
                'ten_phong_ban' => 'Phòng Nhân sự',
                'ma_phong_ban' => 'HR',
                'mo_ta' => 'Quản lý nhân sự và tuyển dụng',
                'truong_phong_id' => null,
                'ngan_sach' => 500000000,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten_phong_ban' => 'Phòng Kỹ thuật',
                'ma_phong_ban' => 'IT',
                'mo_ta' => 'Phát triển sản phẩm và công nghệ',
                'truong_phong_id' => null,
                'ngan_sach' => 1000000000,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten_phong_ban' => 'Phòng Kinh doanh',
                'ma_phong_ban' => 'SALE',
                'mo_ta' => 'Bán hàng và phát triển thị trường',
                'truong_phong_id' => null,
                'ngan_sach' => 800000000,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten_phong_ban' => 'Phòng Kế toán',
                'ma_phong_ban' => 'ACC',
                'mo_ta' => 'Quản lý tài chính kế toán',
                'truong_phong_id' => null,
                'ngan_sach' => 300000000,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten_phong_ban' => 'Phòng Marketing',
                'ma_phong_ban' => 'MKT',
                'mo_ta' => 'Quảng cáo và tiếp thị',
                'truong_phong_id' => null,
                'ngan_sach' => 600000000,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhuCapSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phu_cap')->insert([
            [
                'ten' => 'Phụ cấp xăng xe',
                'ma' => 'XANGXE',
                'mo_ta' => 'Phụ cấp xăng xe hàng tháng',
                'loai_phu_cap' => 'co_dinh',
                'so_tien_mac_dinh' => 1000000,
                'cach_tinh' => 'so_tien_co_dinh',
                'chiu_thue' => 0,
                'dieu_kien_ap_dung' => json_encode(['phong_ban' => 'IT, SALE']),
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Phụ cấp ăn trưa',
                'ma' => 'ANTRUA',
                'mo_ta' => 'Phụ cấp ăn trưa hàng ngày',
                'loai_phu_cap' => 'co_dinh',
                'so_tien_mac_dinh' => 650000,
                'cach_tinh' => 'so_tien_co_dinh',
                'chiu_thue' => 0,
                'dieu_kien_ap_dung' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Phụ cấp trách nhiệm',
                'ma' => 'TRACHNHIEM',
                'mo_ta' => 'Phụ cấp cho vị trí quản lý',
                'loai_phu_cap' => 'co_dinh',
                'so_tien_mac_dinh' => 2000000,
                'cach_tinh' => 'so_tien_co_dinh',
                'chiu_thue' => 1,
                'dieu_kien_ap_dung' => json_encode(['chuc_vu' => 'Trưởng phòng, Giám đốc']),
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Phụ cấp điện thoại',
                'ma' => 'DIENTHOAI',
                'mo_ta' => 'Phụ cấp điện thoại hàng tháng',
                'loai_phu_cap' => 'co_dinh',
                'so_tien_mac_dinh' => 500000,
                'cach_tinh' => 'so_tien_co_dinh',
                'chiu_thue' => 1,
                'dieu_kien_ap_dung' => json_encode(['phong_ban' => 'SALE, IT']),
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Phụ cấp độc hại',
                'ma' => 'DOCHAI',
                'mo_ta' => 'Phụ cấp môi trường độc hại',
                'loai_phu_cap' => 'co_dinh',
                'so_tien_mac_dinh' => 300000,
                'cach_tinh' => 'so_tien_co_dinh',
                'chiu_thue' => 0,
                'dieu_kien_ap_dung' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
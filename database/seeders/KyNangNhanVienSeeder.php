<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KyNangNhanVienSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ky_nang_nhan_vien')->insert([
            [
                'nguoi_dung_id' => 1,
                'ky_nang_id' => 1,
                'trinh_do' => 'xuat_sac',
                'so_nam_kinh_nghiem' => 10,
                'chung_chi' => 'Zend PHP Certified',
                'ngay_cap_chung_chi' => '2020-05-20',
                'ngay_het_han' => '2025-05-20',
                'da_xac_minh' => 1,
                'nguoi_xac_minh_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 2,
                'ky_nang_id' => 2,
                'trinh_do' => 'tot',
                'so_nam_kinh_nghiem' => 8,
                'chung_chi' => 'React Certified',
                'ngay_cap_chung_chi' => '2021-03-15',
                'ngay_het_han' => '2024-03-15',
                'da_xac_minh' => 1,
                'nguoi_xac_minh_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 3,
                'ky_nang_id' => 3,
                'trinh_do' => 'kha',
                'so_nam_kinh_nghiem' => 5,
                'chung_chi' => 'PMP',
                'ngay_cap_chung_chi' => '2022-01-10',
                'ngay_het_han' => '2027-01-10',
                'da_xac_minh' => 0,
                'nguoi_xac_minh_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 4,
                'ky_nang_id' => 4,
                'trinh_do' => 'kha',
                'so_nam_kinh_nghiem' => 4,
                'chung_chi' => 'IELTS 7.0',
                'ngay_cap_chung_chi' => '2023-06-01',
                'ngay_het_han' => '2025-06-01',
                'da_xac_minh' => 1,
                'nguoi_xac_minh_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 5,
                'ky_nang_id' => 5,
                'trinh_do' => 'tot',
                'so_nam_kinh_nghiem' => 6,
                'chung_chi' => 'CPA',
                'ngay_cap_chung_chi' => '2019-08-20',
                'ngay_het_han' => null,
                'da_xac_minh' => 1,
                'nguoi_xac_minh_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiNhanhCongTySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chi_nhanh_cong_ty')->insert([
            [
                'ten' => 'Trụ sở chính Hà Nội',
                'ma' => 'HN001',
                'email' => 'hanoi@hrflow.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Chi nhánh Hồ Chí Minh',
                'ma' => 'HCM001',
                'email' => 'hcm@hrflow.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Chi nhánh Đà Nẵng',
                'ma' => 'DN001',
                'email' => 'danang@hrflow.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
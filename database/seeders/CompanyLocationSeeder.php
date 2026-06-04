<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyLocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('company_locations')->insert([
            ['address' => 'Số 1 Lê Thái Tổ, Hoàn Kiếm, Hà Nội', 'latitude' => 21.0285110, 'longitude' => 105.8048170, 'allowed_radius' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'Số 1 Nguyễn Huệ, Quận 1, TP.HCM', 'latitude' => 10.7768890, 'longitude' => 106.7009440, 'allowed_radius' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'Số 1 Bạch Đằng, Hải Châu, Đà Nẵng', 'latitude' => 16.0544080, 'longitude' => 108.2021660, 'allowed_radius' => 100, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
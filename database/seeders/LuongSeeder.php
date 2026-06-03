<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('luong')->insert([
            [
                'nguoi_dung_id' => 1,
                'hop_dong_lao_dong_id' => 1,
                'luong_co_ban' => 30000000,
                'phu_cap' => 5000000,
                'tien_thuong' => 10000000,
                'tien_phat' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 2,
                'hop_dong_lao_dong_id' => 2,
                'luong_co_ban' => 20000000,
                'phu_cap' => 3000000,
                'tien_thuong' => 5000000,
                'tien_phat' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 3,
                'hop_dong_lao_dong_id' => 3,
                'luong_co_ban' => 10000000,
                'phu_cap' => 1000000,
                'tien_thuong' => 2000000,
                'tien_phat' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 4,
                'hop_dong_lao_dong_id' => 4,
                'luong_co_ban' => 8000000,
                'phu_cap' => 500000,
                'tien_thuong' => 0,
                'tien_phat' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 5,
                'hop_dong_lao_dong_id' => 5,
                'luong_co_ban' => 12000000,
                'phu_cap' => 1500000,
                'tien_thuong' => 3000000,
                'tien_phat' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
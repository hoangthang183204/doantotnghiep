<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BangLuongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bang_luong')->insert([
            'ma_bang_luong' => 'BL-2026-05',

            // ENUM hợp lệ
            'loai_bang_luong' => 'hang_thang',

            'nam' => 2026,
            'thang' => 5,

            // ENUM hợp lệ
            'trang_thai' => 'da_duyet',

            'nguoi_xu_ly_id' => 1,
            'nguoi_phe_duyet_id' => 1,

            'thoi_gian_xu_ly' => now(),
            'thoi_gian_phe_duyet' => now(),

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
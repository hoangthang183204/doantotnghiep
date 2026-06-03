<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BangLuongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bang_luong')->insert([
            [
                'ma_bang_luong' => 'BL202401',
                'loai_bang_luong' => 'thang',
                'nam' => 2024,
                'thang' => 1,
                'trang_thai' => 'da_duyet',
                'nguoi_xu_ly_id' => 5,
                'thoi_gian_xu_ly' => '2024-01-25 10:00:00',
                'nguoi_phe_duyet_id' => 1,
                'thoi_gian_phe_duyet' => '2024-01-28 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_bang_luong' => 'BL202402',
                'loai_bang_luong' => 'thang',
                'nam' => 2024,
                'thang' => 2,
                'trang_thai' => 'da_duyet',
                'nguoi_xu_ly_id' => 5,
                'thoi_gian_xu_ly' => '2024-02-25 10:00:00',
                'nguoi_phe_duyet_id' => 1,
                'thoi_gian_phe_duyet' => '2024-02-28 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_bang_luong' => 'BL202403',
                'loai_bang_luong' => 'thang',
                'nam' => 2024,
                'thang' => 3,
                'trang_thai' => 'cho_duyet',
                'nguoi_xu_ly_id' => 5,
                'thoi_gian_xu_ly' => '2024-03-25 10:00:00',
                'nguoi_phe_duyet_id' => null,
                'thoi_gian_phe_duyet' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_bang_luong' => 'BL202304',
                'loai_bang_luong' => 'thang',
                'nam' => 2023,
                'thang' => 12,
                'trang_thai' => 'da_khoa',
                'nguoi_xu_ly_id' => 5,
                'thoi_gian_xu_ly' => '2023-12-25 10:00:00',
                'nguoi_phe_duyet_id' => 1,
                'thoi_gian_phe_duyet' => '2023-12-28 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_bang_luong' => 'BL202401_QUY1',
                'loai_bang_luong' => 'quy',
                'nam' => 2024,
                'thang' => null,
                'trang_thai' => 'dang_tao',
                'nguoi_xu_ly_id' => 5,
                'thoi_gian_xu_ly' => '2024-03-30 09:00:00',
                'nguoi_phe_duyet_id' => null,
                'thoi_gian_phe_duyet' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
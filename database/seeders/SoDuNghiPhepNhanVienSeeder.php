<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoDuNghiPhepNhanVienSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('so_du_nghi_phep_nhan_vien')->insert([
            [
                'nguoi_dung_id' => 1,
                'loai_nghi_phep_id' => 1,
                'nam' => 2024,
                'so_ngay_duoc_cap' => 12,
                'so_ngay_da_dung' => 3,
                'so_ngay_cho_duyet' => 0,
                'so_ngay_con_lai' => 9,
                'so_ngay_chuyen_tu_nam_truoc' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 2,
                'loai_nghi_phep_id' => 1,
                'nam' => 2024,
                'so_ngay_duoc_cap' => 12,
                'so_ngay_da_dung' => 5,
                'so_ngay_cho_duyet' => 2,
                'so_ngay_con_lai' => 5,
                'so_ngay_chuyen_tu_nam_truoc' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 3,
                'loai_nghi_phep_id' => 1,
                'nam' => 2024,
                'so_ngay_duoc_cap' => 12,
                'so_ngay_da_dung' => 2,
                'so_ngay_cho_duyet' => 1,
                'so_ngay_con_lai' => 9,
                'so_ngay_chuyen_tu_nam_truoc' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 4,
                'loai_nghi_phep_id' => 1,
                'nam' => 2024,
                'so_ngay_duoc_cap' => 8,
                'so_ngay_da_dung' => 1,
                'so_ngay_cho_duyet' => 0,
                'so_ngay_con_lai' => 7,
                'so_ngay_chuyen_tu_nam_truoc' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 5,
                'loai_nghi_phep_id' => 1,
                'nam' => 2024,
                'so_ngay_duoc_cap' => 12,
                'so_ngay_da_dung' => 0,
                'so_ngay_cho_duyet' => 0,
                'so_ngay_con_lai' => 12,
                'so_ngay_chuyen_tu_nam_truoc' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
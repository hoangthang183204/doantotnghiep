<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanCongCongViecSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phan_cong_cong_viec')->insert([
            ['nguoi_giao_id' => 1, 'nguoi_nhan_id' => 4, 'cong_viec_id' => 1, 'phong_ban_id' => 4, 'vai_tro_trong_cv' => 'chu_tri', 'ghi_chu' => 'Hoàn thành trong tháng', 'ngay_bat_dau' => now(), 'deadline' => now()->addDays(30), 'ngay_hoan_thanh' => null, 'tien_do' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_giao_id' => 1, 'nguoi_nhan_id' => 4, 'cong_viec_id' => 2, 'phong_ban_id' => 4, 'vai_tro_trong_cv' => 'chu_tri', 'ghi_chu' => 'Tối ưu database', 'ngay_bat_dau' => now()->addDays(10), 'deadline' => now()->addDays(20), 'ngay_hoan_thanh' => null, 'tien_do' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CongViecSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cong_viec')->insert([
            ['ten_cong_viec' => 'Xây dựng module chấm công', 'mo_ta' => 'Phát triển module chấm công online', 'trang_thai' => 'dang_lam', 'do_uu_tien' => 'cao', 'created_at' => now(), 'updated_at' => now()],
            ['ten_cong_viec' => 'Tối ưu database', 'mo_ta' => 'Tối ưu các câu query chậm', 'trang_thai' => 'chua_bat_dau', 'do_uu_tien' => 'trung_binh', 'created_at' => now(), 'updated_at' => now()],
            ['ten_cong_viec' => 'Viết tài liệu API', 'mo_ta' => 'Document API cho dự án', 'trang_thai' => 'hoan_thanh', 'do_uu_tien' => 'thap', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
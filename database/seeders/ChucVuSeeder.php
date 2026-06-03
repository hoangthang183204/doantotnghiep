<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucVuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chuc_vu')->insert([
            [
                'ten' => 'Giám đốc',
                'ma' => 'GD',
                'mo_ta' => 'Giám đốc công ty',
                'luong_co_ban' => 30000000,
                'he_so_luong' => 3.5,
                'phong_ban_id' => 1,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Trưởng phòng',
                'ma' => 'TP',
                'mo_ta' => 'Trưởng các phòng ban',
                'luong_co_ban' => 20000000,
                'he_so_luong' => 2.5,
                'phong_ban_id' => 1,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Nhân viên chính',
                'ma' => 'NV1',
                'mo_ta' => 'Nhân viên chính thức',
                'luong_co_ban' => 10000000,
                'he_so_luong' => 1.5,
                'phong_ban_id' => 2,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Nhân viên thử việc',
                'ma' => 'NVTV',
                'mo_ta' => 'Nhân viên thời gian thử việc',
                'luong_co_ban' => 5000000,
                'he_so_luong' => 1.0,
                'phong_ban_id' => 3,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Thực tập sinh',
                'ma' => 'TTS',
                'mo_ta' => 'Thực tập sinh',
                'luong_co_ban' => 3000000,
                'he_so_luong' => 0.5,
                'phong_ban_id' => 2,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
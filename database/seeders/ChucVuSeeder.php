<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucVuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chuc_vu')->insert([
            ['ten' => 'Giám Đốc', 'ma' => 'GD', 'mo_ta' => 'Giám đốc công ty', 'luong_co_ban' => 30000000, 'he_so_luong' => 4.0, 'phong_ban_id' => 1, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Trưởng Phòng Nhân Sự', 'ma' => 'TP_HR', 'mo_ta' => 'Trưởng phòng nhân sự', 'luong_co_ban' => 18000000, 'he_so_luong' => 2.5, 'phong_ban_id' => 2, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Nhân Viên Nhân Sự', 'ma' => 'NV_HR', 'mo_ta' => 'Nhân viên nhân sự', 'luong_co_ban' => 10000000, 'he_so_luong' => 1.5, 'phong_ban_id' => 2, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Trưởng Phòng Kế Toán', 'ma' => 'TP_KT', 'mo_ta' => 'Trưởng phòng kế toán', 'luong_co_ban' => 18000000, 'he_so_luong' => 2.5, 'phong_ban_id' => 3, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Nhân Viên Kế Toán', 'ma' => 'NV_KT', 'mo_ta' => 'Nhân viên kế toán', 'luong_co_ban' => 10000000, 'he_so_luong' => 1.5, 'phong_ban_id' => 3, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Trưởng Phòng IT', 'ma' => 'TP_IT', 'mo_ta' => 'Trưởng phòng công nghệ', 'luong_co_ban' => 20000000, 'he_so_luong' => 3.0, 'phong_ban_id' => 4, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Lập Trình Viên', 'ma' => 'DEV', 'mo_ta' => 'Lập trình viên', 'luong_co_ban' => 12000000, 'he_so_luong' => 1.8, 'phong_ban_id' => 4, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Trưởng Phòng Marketing', 'ma' => 'TP_MKT', 'mo_ta' => 'Trưởng phòng marketing', 'luong_co_ban' => 18000000, 'he_so_luong' => 2.5, 'phong_ban_id' => 5, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Nhân Viên Marketing', 'ma' => 'NV_MKT', 'mo_ta' => 'Nhân viên marketing', 'luong_co_ban' => 10000000, 'he_so_luong' => 1.5, 'phong_ban_id' => 5, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Trưởng Phòng Kinh Doanh', 'ma' => 'TP_KD', 'mo_ta' => 'Trưởng phòng kinh doanh', 'luong_co_ban' => 18000000, 'he_so_luong' => 2.5, 'phong_ban_id' => 6, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Nhân Viên Kinh Doanh', 'ma' => 'NV_KD', 'mo_ta' => 'Nhân viên kinh doanh', 'luong_co_ban' => 10000000, 'he_so_luong' => 1.5, 'phong_ban_id' => 6, 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
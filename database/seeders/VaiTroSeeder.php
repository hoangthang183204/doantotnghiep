<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class VaiTroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vai_tro')->insert([
            [
                'name' => 'super_admin',
                'ten_hien_thi' => 'Super Admin',
                'mo_ta' => 'Quản trị hệ thống cấp cao',
                'la_vai_tro_he_thong' => 1,
                'trang_thai' => 1,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'ten_hien_thi' => 'Admin',
                'mo_ta' => 'Quản trị viên',
                'la_vai_tro_he_thong' => 1,
                'trang_thai' => 1,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'truong_phong',
                'ten_hien_thi' => 'Trưởng phòng',
                'mo_ta' => 'Trưởng các phòng ban',
                'la_vai_tro_he_thong' => 1,
                'trang_thai' => 1,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'nhan_vien',
                'ten_hien_thi' => 'Nhân viên',
                'mo_ta' => 'Nhân viên công ty',
                'la_vai_tro_he_thong' => 1,
                'trang_thai' => 1,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ke_toan',
                'ten_hien_thi' => 'Kế toán',
                'mo_ta' => 'Nhân viên kế toán',
                'la_vai_tro_he_thong' => 1,
                'trang_thai' => 1,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
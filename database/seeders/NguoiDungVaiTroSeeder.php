<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NguoiDungVaiTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nguoi_dung_vai_tro')->insert([
            [
                'vai_tro_id' => 1, // Super Admin
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vai_tro_id' => 2, // Admin
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vai_tro_id' => 3, // Trưởng phòng
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vai_tro_id' => 4, // Nhân viên
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vai_tro_id' => 4, // Nhân viên
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vai_tro_id' => 5, // Kế toán
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Thêm vai trò cho người dùng 2 là Admin
            [
                'vai_tro_id' => 2, // Admin
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Thêm vai trò Nhân viên cho người dùng 5 (kế toán cũng là nhân viên)
            [
                'vai_tro_id' => 4, // Nhân viên
                'model_type' => 'App\\Models\\NguoiDung',
                'nguoi_dung_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
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
        DB::table('nguoi_dung_vai_tro')->truncate();
        DB::table('nguoi_dung_vai_tro')->insert([
            // Admin
            ['nguoi_dung_id' => 1, 'vai_tro_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            
            // HR Manager
            ['nguoi_dung_id' => 2, 'vai_tro_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            
            // Nhân viên Dev (Phạm Minh Tuấn)
            ['nguoi_dung_id' => 4, 'vai_tro_id' => 3, 'created_at' => now(), 'updated_at' => now()], 
            
            // Trưởng phòng IT
            ['nguoi_dung_id' => 3, 'vai_tro_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            
            // Kế toán
            ['nguoi_dung_id' => 5, 'vai_tro_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
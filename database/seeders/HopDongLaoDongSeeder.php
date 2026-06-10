<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HopDongLaoDongSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('nguoi_dung')->get();

        foreach ($users as $user) {

            DB::table('hop_dong_lao_dong')->insert([
                'created_by' => 1,

                'nguoi_dung_id' => $user->id,

                'chuc_vu_id' => $user->chuc_vu_id,

                'so_hop_dong' => 'HD'.str_pad($user->id, 5, '0', STR_PAD_LEFT),

                // ENUM hợp lệ
                'loai_hop_dong' => 'khong_xac_dinh_thoi_han',

                'ngay_bat_dau' => now()->toDateString(),

                'ngay_ket_thuc' => null,

                'luong_co_ban' => rand(7000000, 15000000),

                'phu_cap' => rand(500000, 2000000),

                'hinh_thuc_lam_viec' => 'full_time',

                'dia_diem_lam_viec' => 'Ha Noi',

                // ENUM hợp lệ
                'trang_thai_hop_dong' => 'hieu_luc',

                // ENUM hợp lệ
                'trang_thai_ky' => 'da_ky',

                'nguoi_ky_id' => 1,

                'thoi_gian_ky' => now(),

                'created_at' => now(),

                'updated_at' => now(),
            ]);
        }
    }
}
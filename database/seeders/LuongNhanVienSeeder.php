<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuongNhanVienSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('nguoi_dung')->pluck('id');

        $bangLuongId = DB::table('bang_luong')->value('id');

        foreach ($users as $userId) {

            $luong = rand(7000000, 15000000);
            $phuCap = rand(500000, 2000000);
            $khauTru = rand(0, 500000);

            DB::table('luong_nhan_vien')->insert([
                'bang_luong_id' => $bangLuongId,

                'luong_thang' => 5,
                'luong_nam' => 2026,

                'nguoi_dung_id' => $userId,

                'luong_co_ban' => $luong,

                'tong_phu_cap' => $phuCap,

                'tong_khau_tru' => $khauTru,

                'tong_luong' => $luong + $phuCap,

                'luong_thuc_nhan' => ($luong + $phuCap) - $khauTru,

                'so_ngay_cong' => rand(24, 26),

                'gio_tang_ca' => rand(0, 20),

                'cong_tang_ca' => rand(0, 3),

                'ngay_nghi_phep' => rand(0, 2),

                'ngay_nghi_khong_phep' => 0,

                'ngay_le' => 0,

                'thue_thu_nhap_ca_nhan' => 0,

                'ghi_chu' => fake()->sentence(),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
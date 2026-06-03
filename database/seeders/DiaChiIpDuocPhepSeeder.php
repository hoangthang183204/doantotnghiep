<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiaChiIpDuocPhepSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dia_chi_ip_duoc_phep')->insert([
            [
                'dia_chi_ip' => '192.168.1.100',
                'dai_ip_bat_dau' => null,
                'dai_ip_ket_thuc' => null,
                'ten_vi_tri' => 'Văn phòng Hà Nội - Admin',
                'mo_ta' => 'Máy tính admin',
                'chi_nhanh_id' => 1,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi_ip' => null,
                'dai_ip_bat_dau' => '192.168.1.1',
                'dai_ip_ket_thuc' => '192.168.1.50',
                'ten_vi_tri' => 'Dải IP văn phòng Hà Nội',
                'mo_ta' => 'Dải IP cho nhân viên Hà Nội',
                'chi_nhanh_id' => 1,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi_ip' => '10.0.0.1',
                'dai_ip_bat_dau' => null,
                'dai_ip_ket_thuc' => null,
                'ten_vi_tri' => 'Văn phòng TP.HCM',
                'mo_ta' => 'Router chính văn phòng HCM',
                'chi_nhanh_id' => 2,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi_ip' => null,
                'dai_ip_bat_dau' => '10.0.0.1',
                'dai_ip_ket_thuc' => '10.0.0.100',
                'ten_vi_tri' => 'Dải IP văn phòng TP.HCM',
                'mo_ta' => 'IP cho nhân viên HCM',
                'chi_nhanh_id' => 2,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi_ip' => '172.16.0.10',
                'dai_ip_bat_dau' => null,
                'dai_ip_ket_thuc' => null,
                'ten_vi_tri' => 'Văn phòng Đà Nẵng',
                'mo_ta' => 'Server văn phòng Đà Nẵng',
                'chi_nhanh_id' => 3,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
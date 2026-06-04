<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChamCongSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now()->format('Y-m-d');
        
        DB::table('cham_cong')->insert([
            ['nguoi_dung_id' => 1, 'ngay_cham_cong' => $today, 'gio_vao' => '08:25:00', 'gio_ra' => '17:35:00', 'so_gio_lam' => 8.5, 'so_cong' => 1.0, 'gio_tang_ca' => 0.5, 'phut_di_muon' => 0, 'phut_ve_som' => 0, 'trang_thai' => 'dung_gio', 'dia_chi_ip' => '192.168.1.100', 'ten_wifi' => 'HRFlow_WiFi', 'dia_chi_mac' => 'AA:BB:CC:DD:EE:01', 'ten_thiet_bi' => 'PC-Admin', 'phuong_thuc_cham_cong' => 'wifi', 'ghi_chu' => null, 'nguoi_phe_duyet_id' => 1, 'trang_thai_duyet' => 1, 'ghi_chu_duyet' => 'OK', 'thoi_gian_phe_duyet' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 2, 'ngay_cham_cong' => $today, 'gio_vao' => '08:30:00', 'gio_ra' => '17:30:00', 'so_gio_lam' => 8.0, 'so_cong' => 1.0, 'gio_tang_ca' => 0, 'phut_di_muon' => 0, 'phut_ve_som' => 0, 'trang_thai' => 'dung_gio', 'dia_chi_ip' => '192.168.1.101', 'ten_wifi' => 'HRFlow_WiFi', 'dia_chi_mac' => 'AA:BB:CC:DD:EE:02', 'ten_thiet_bi' => 'PC-HR', 'phuong_thuc_cham_cong' => 'ip', 'ghi_chu' => null, 'nguoi_phe_duyet_id' => 1, 'trang_thai_duyet' => 1, 'ghi_chu_duyet' => 'OK', 'thoi_gian_phe_duyet' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 3, 'ngay_cham_cong' => $today, 'gio_vao' => '08:45:00', 'gio_ra' => '17:30:00', 'so_gio_lam' => 7.75, 'so_cong' => 0.97, 'gio_tang_ca' => 0, 'phut_di_muon' => 15, 'phut_ve_som' => 0, 'trang_thai' => 'di_muon', 'dia_chi_ip' => '192.168.1.102', 'ten_wifi' => 'HRFlow_WiFi', 'dia_chi_mac' => 'AA:BB:CC:DD:EE:03', 'ten_thiet_bi' => 'Laptop-IT', 'phuong_thuc_cham_cong' => 'mac', 'ghi_chu' => 'Kẹt xe', 'nguoi_phe_duyet_id' => null, 'trang_thai_duyet' => 0, 'ghi_chu_duyet' => null, 'thoi_gian_phe_duyet' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 4, 'ngay_cham_cong' => $today, 'gio_vao' => '08:20:00', 'gio_ra' => '17:40:00', 'so_gio_lam' => 8.5, 'so_cong' => 1.0, 'gio_tang_ca' => 0.5, 'phut_di_muon' => 0, 'phut_ve_som' => 0, 'trang_thai' => 'dung_gio', 'dia_chi_ip' => '192.168.1.103', 'ten_wifi' => 'HRFlow_WiFi', 'dia_chi_mac' => 'AA:BB:CC:DD:EE:04', 'ten_thiet_bi' => 'PC-Dev', 'phuong_thuc_cham_cong' => 'wifi', 'ghi_chu' => null, 'nguoi_phe_duyet_id' => 1, 'trang_thai_duyet' => 1, 'ghi_chu_duyet' => 'OK', 'thoi_gian_phe_duyet' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 5, 'ngay_cham_cong' => $today, 'gio_vao' => '08:30:00', 'gio_ra' => '17:30:00', 'so_gio_lam' => 8.0, 'so_cong' => 1.0, 'gio_tang_ca' => 0, 'phut_di_muon' => 0, 'phut_ve_som' => 0, 'trang_thai' => 'dung_gio', 'dia_chi_ip' => '192.168.1.104', 'ten_wifi' => 'HRFlow_WiFi', 'dia_chi_mac' => 'AA:BB:CC:DD:EE:05', 'ten_thiet_bi' => 'PC-KT', 'phuong_thuc_cham_cong' => 'manual', 'ghi_chu' => null, 'nguoi_phe_duyet_id' => 1, 'trang_thai_duyet' => 1, 'ghi_chu_duyet' => 'OK', 'thoi_gian_phe_duyet' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
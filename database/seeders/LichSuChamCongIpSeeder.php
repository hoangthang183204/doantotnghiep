<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LichSuChamCongIpSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lich_su_cham_cong_ip')->insert([
            [
                'cham_cong_id' => 1,
                'nguoi_dung_id' => 1,
                'hanh_dong' => 'check_in',
                'dia_chi_ip' => '192.168.1.100',
                'ten_wifi' => 'Company_WiFi',
                'dia_chi_mac' => 'AA:BB:CC:DD:EE:FF',
                'ten_thiet_bi' => 'PC-Admin',
                'trinh_duyet_thiet_bi' => 'Chrome 120.0',
                'duoc_phep' => 1,
                'phuong_thuc_cham_cong' => 'wifi',
                'created_at' => '2024-03-01 07:58:00',
            ],
            [
                'cham_cong_id' => 1,
                'nguoi_dung_id' => 1,
                'hanh_dong' => 'check_out',
                'dia_chi_ip' => '192.168.1.100',
                'ten_wifi' => 'Company_WiFi',
                'dia_chi_mac' => 'AA:BB:CC:DD:EE:FF',
                'ten_thiet_bi' => 'PC-Admin',
                'trinh_duyet_thiet_bi' => 'Chrome 120.0',
                'duoc_phep' => 1,
                'phuong_thuc_cham_cong' => 'wifi',
                'created_at' => '2024-03-01 17:02:00',
            ],
            [
                'cham_cong_id' => 2,
                'nguoi_dung_id' => 2,
                'hanh_dong' => 'check_in',
                'dia_chi_ip' => '192.168.1.101',
                'ten_wifi' => 'Company_WiFi',
                'dia_chi_mac' => 'AA:BB:CC:DD:EE:01',
                'ten_thiet_bi' => 'PC-TruongPhong',
                'trinh_duyet_thiet_bi' => 'Firefox 115.0',
                'duoc_phep' => 1,
                'phuong_thuc_cham_cong' => 'ip',
                'created_at' => '2024-03-01 08:15:00',
            ],
            [
                'cham_cong_id' => 3,
                'nguoi_dung_id' => 3,
                'hanh_dong' => 'check_in',
                'dia_chi_ip' => '192.168.1.102',
                'ten_wifi' => 'Company_WiFi',
                'dia_chi_mac' => 'AA:BB:CC:DD:EE:02',
                'ten_thiet_bi' => 'Laptop-NV1',
                'trinh_duyet_thiet_bi' => 'Edge 120.0',
                'duoc_phep' => 1,
                'phuong_thuc_cham_cong' => 'mac',
                'created_at' => '2024-03-01 07:59:00',
            ],
            [
                'cham_cong_id' => 5,
                'nguoi_dung_id' => 5,
                'hanh_dong' => 'check_in',
                'dia_chi_ip' => '192.168.1.105',
                'ten_wifi' => null,
                'dia_chi_mac' => null,
                'ten_thiet_bi' => 'PC-KeToan',
                'trinh_duyet_thiet_bi' => 'Chrome 119.0',
                'duoc_phep' => 1,
                'phuong_thuc_cham_cong' => 'manual',
                'created_at' => '2024-03-01 08:30:00',
            ],
        ]);
    }
}
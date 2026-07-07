<?php
// database/seeders/CauHinhChamCongSeeder.php

namespace Database\Seeders;

use App\Models\CauHinhChamCong;
use Illuminate\Database\Seeder;

class CauHinhChamCongSeeder extends Seeder
{
    public function run(): void
    {
        // ===== WIFI =====
        $wifiList = [
            'HRFlow_WiFi',
        ];

        foreach ($wifiList as $wifi) {
            CauHinhChamCong::create([
                'ten' => 'WiFi ' . $wifi,
                'loai' => 'wifi',
                'gia_tri' => $wifi,
                'mo_ta' => 'WiFi được phép chấm công',
                'trang_thai' => 1,
            ]);
        }

        // ===== IP =====
        $ipList = [
            '127.0.0.1',
        ];

        foreach ($ipList as $ip) {
            CauHinhChamCong::create([
                'ten' => 'IP ' . $ip,
                'loai' => 'ip',
                'gia_tri' => $ip,
                'mo_ta' => 'IP được phép chấm công',
                'trang_thai' => 1,
            ]);
        }

        // ===== MAC =====
        CauHinhChamCong::create([
            'ten' => 'MAC AA:BB:CC:DD:EE:01',
            'loai' => 'mac',
            'gia_tri' => 'AA:BB:CC:DD:EE:01',
            'mo_ta' => 'MAC được phép chấm công',
            'trang_thai' => 1,
        ]);

        $this->command->info('✅ Đã tạo cấu hình chấm công thành công!');
    }
}
<?php

namespace Database\Seeders;

use App\Models\CaLamViec;
use Illuminate\Database\Seeder;

class CaLamViecSeeder extends Seeder
{
    public function run()
    {
        // Ca sáng
        CaLamViec::create([
            'ten' => 'Sáng',
            'ma' => 'SANG',
            'gio_bat_dau' => '08:00:00',
            'gio_ket_thuc' => '12:00:00',
            'so_gio_lam_viec' => 4.0,
            'so_phut_cho_phep_di_tre' => 15,
            'so_phut_cho_phep_ve_som' => 15,
            'is_default' => false,
            'trang_thai' => true,
        ]);

        // Ca chiều
        CaLamViec::create([
            'ten' => 'Chiều',
            'ma' => 'CHIEU',
            'gio_bat_dau' => '13:00:00',
            'gio_ket_thuc' => '17:30:00',
            'so_gio_lam_viec' => 4.5,
            'so_phut_cho_phep_di_tre' => 15,
            'so_phut_cho_phep_ve_som' => 15,
            'is_default' => false,
            'trang_thai' => true,
        ]);

        // Ca hành chính (full day)
        CaLamViec::create([
            'ten' => 'Hành chính',
            'ma' => 'HANH_CHINH',
            'gio_bat_dau' => '08:30:00',
            'gio_ket_thuc' => '17:30:00',
            'so_gio_lam_viec' => 8.0,
            'so_phut_cho_phep_di_tre' => 15,
            'so_phut_cho_phep_ve_som' => 15,
            'is_default' => true,
            'trang_thai' => true,
        ]);
    }
}
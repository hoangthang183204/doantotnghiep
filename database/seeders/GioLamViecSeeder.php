<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GioLamViecSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gio_lam_viec')->insert([
            [
                'gio_bat_dau' => '08:00:00',
                'gio_ket_thuc' => '17:00:00',
                'gio_nghi_trua' => 1,
                'so_phut_cho_phep_di_tre' => 15,
                'so_phut_cho_phep_ve_som' => 15,
                'gio_bat_dau_tang_ca' => '17:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gio_bat_dau' => '09:00:00',
                'gio_ket_thuc' => '18:00:00',
                'gio_nghi_trua' => 1.5,
                'so_phut_cho_phep_di_tre' => 10,
                'so_phut_cho_phep_ve_som' => 10,
                'gio_bat_dau_tang_ca' => '18:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gio_bat_dau' => '07:30:00',
                'gio_ket_thuc' => '16:30:00',
                'gio_nghi_trua' => 1,
                'so_phut_cho_phep_di_tre' => 5,
                'so_phut_cho_phep_ve_som' => 5,
                'gio_bat_dau_tang_ca' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gio_bat_dau' => '08:30:00',
                'gio_ket_thuc' => '17:30:00',
                'gio_nghi_trua' => 1,
                'so_phut_cho_phep_di_tre' => 20,
                'so_phut_cho_phep_ve_som' => 20,
                'gio_bat_dau_tang_ca' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gio_bat_dau' => '10:00:00',
                'gio_ket_thuc' => '19:00:00',
                'gio_nghi_trua' => 1,
                'so_phut_cho_phep_di_tre' => 15,
                'so_phut_cho_phep_ve_som' => 15,
                'gio_bat_dau_tang_ca' => '19:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
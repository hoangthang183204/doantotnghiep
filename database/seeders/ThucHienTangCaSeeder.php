<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThucHienTangCaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('thuc_hien_tang_ca')->insert([
            [
                'dang_ky_tang_ca_id' => 1,
                'gio_bat_dau_thuc_te' => '18:00:00',
                'gio_ket_thuc_thuc_te' => '21:00:00',
                'so_gio_tang_ca_thuc_te' => 3,
                'cong_viec_da_thuc_hien' => 'Hoàn thành báo cáo tháng 2',
                'so_cong_tang_ca' => 0.375,
                'trang_thai' => 'da_hoan_thanh',
                'vi_tri_check_in' => 'Văn phòng',
                'vi_tri_check_out' => 'Văn phòng',
                'ghi_chu' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dang_ky_tang_ca_id' => 2,
                'gio_bat_dau_thuc_te' => '08:00:00',
                'gio_ket_thuc_thuc_te' => '17:00:00',
                'so_gio_tang_ca_thuc_te' => 8,
                'cong_viec_da_thuc_hien' => 'Fix bug hệ thống chấm công',
                'so_cong_tang_ca' => 1,
                'trang_thai' => 'da_hoan_thanh',
                'vi_tri_check_in' => 'Văn phòng',
                'vi_tri_check_out' => 'Văn phòng',
                'ghi_chu' => 'Hoàn thành tốt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dang_ky_tang_ca_id' => 3,
                'gio_bat_dau_thuc_te' => null,
                'gio_ket_thuc_thuc_te' => null,
                'so_gio_tang_ca_thuc_te' => 0,
                'cong_viec_da_thuc_hien' => null,
                'so_cong_tang_ca' => 0,
                'trang_thai' => 'chua_thuc_hien',
                'vi_tri_check_in' => null,
                'vi_tri_check_out' => null,
                'ghi_chu' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dang_ky_tang_ca_id' => 4,
                'gio_bat_dau_thuc_te' => null,
                'gio_ket_thuc_thuc_te' => null,
                'so_gio_tang_ca_thuc_te' => 0,
                'cong_viec_da_thuc_hien' => null,
                'so_cong_tang_ca' => 0,
                'trang_thai' => 'da_hoan_thanh',
                'vi_tri_check_in' => null,
                'vi_tri_check_out' => null,
                'ghi_chu' => 'Đã hủy đăng ký',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dang_ky_tang_ca_id' => 5,
                'gio_bat_dau_thuc_te' => null,
                'gio_ket_thuc_thuc_te' => null,
                'so_gio_tang_ca_thuc_te' => 0,
                'cong_viec_da_thuc_hien' => null,
                'so_cong_tang_ca' => 0,
                'trang_thai' => 'chua_thuc_hien',
                'vi_tri_check_in' => null,
                'vi_tri_check_out' => null,
                'ghi_chu' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
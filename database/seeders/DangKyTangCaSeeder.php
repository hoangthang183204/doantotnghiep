<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DangKyTangCaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dang_ky_tang_ca')->insert([
            [
                'nguoi_dung_id' => 2,
                'ngay_tang_ca' => '2024-03-02',
                'gio_bat_dau' => '18:00:00',
                'gio_ket_thuc' => '21:00:00',
                'so_gio_tang_ca' => 3,
                'loai_tang_ca' => 'thuong',
                'ly_do_tang_ca' => 'Cần hoàn thành báo cáo',
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => 1,
                'thoi_gian_duyet' => '2024-03-01 15:00:00',
                'ly_do_tu_choi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 3,
                'ngay_tang_ca' => '2024-03-03',
                'gio_bat_dau' => '08:00:00',
                'gio_ket_thuc' => '17:00:00',
                'so_gio_tang_ca' => 8,
                'loai_tang_ca' => 'cuoi_tuan',
                'ly_do_tang_ca' => 'Fix bug hệ thống',
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => 2,
                'thoi_gian_duyet' => '2024-03-02 10:00:00',
                'ly_do_tu_choi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 1,
                'ngay_tang_ca' => '2024-03-04',
                'gio_bat_dau' => '19:00:00',
                'gio_ket_thuc' => '22:00:00',
                'so_gio_tang_ca' => 3,
                'loai_tang_ca' => 'thuong',
                'ly_do_tang_ca' => 'Họp với đối tác',
                'trang_thai' => 'cho_duyet',
                'nguoi_duyet_id' => null,
                'thoi_gian_duyet' => null,
                'ly_do_tu_choi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 5,
                'ngay_tang_ca' => '2024-03-05',
                'gio_bat_dau' => '18:00:00',
                'gio_ket_thuc' => '20:00:00',
                'so_gio_tang_ca' => 2,
                'loai_tang_ca' => 'thuong',
                'ly_do_tang_ca' => 'Tổng kết sổ sách',
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => 1,
                'thoi_gian_duyet' => '2024-03-04 09:00:00',
                'ly_do_tu_choi' => 'Không cần thiết',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 4,
                'ngay_tang_ca' => '2024-03-10',
                'gio_bat_dau' => '08:30:00',
                'gio_ket_thuc' => '17:30:00',
                'so_gio_tang_ca' => 8,
                'loai_tang_ca' => 'le',
                'ly_do_tang_ca' => 'Trực ngày lễ',
                'trang_thai' => 'cho_duyet',
                'nguoi_duyet_id' => null,
                'thoi_gian_duyet' => null,
                'ly_do_tu_choi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
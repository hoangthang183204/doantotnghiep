<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DangKyTangCaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dang_ky_tang_ca')->insert([
            [
                'nguoi_dung_id'      => 1,
                'ngay_tang_ca'       => Carbon::today()->subDays(3),
                'gio_bat_dau'        => '18:00:00',
                'gio_ket_thuc'       => '21:00:00',
                'so_gio_tang_ca'     => 3,
                'loai_tang_ca'       => 'ngay_thuong',
                'ly_do_tang_ca'      => 'Hoàn thành báo cáo cuối tháng',
                'trang_thai'         => 'da_duyet',
                'nguoi_duyet_id'     => 2,
                'thoi_gian_duyet'    => now(),
                'ly_do_tu_choi'      => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],

            [
                'nguoi_dung_id'      => 1,
                'ngay_tang_ca'       => Carbon::today()->subDays(1),
                'gio_bat_dau'        => '18:30:00',
                'gio_ket_thuc'       => '22:00:00',
                'so_gio_tang_ca'     => 3.5,
                'loai_tang_ca'       => 'ngay_thuong',
                'ly_do_tang_ca'      => 'Xử lý công việc tồn đọng',
                'trang_thai'         => 'cho_duyet',
                'nguoi_duyet_id'     => null,
                'thoi_gian_duyet'    => null,
                'ly_do_tu_choi'      => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],

            [
                'nguoi_dung_id'      => 3,
                'ngay_tang_ca'       => Carbon::today(),
                'gio_bat_dau'        => '08:00:00',
                'gio_ket_thuc'       => '12:00:00',
                'so_gio_tang_ca'     => 4,
                'loai_tang_ca'       => 'ngay_nghi',
                'ly_do_tang_ca'      => 'Hỗ trợ triển khai hệ thống',
                'trang_thai'         => 'tu_choi',
                'nguoi_duyet_id'     => 2,
                'thoi_gian_duyet'    => now(),
                'ly_do_tu_choi'      => 'Không thuộc kế hoạch tăng ca đã phê duyệt',
                'created_at'         => now(),
                'updated_at'         => now(),
            ],

            [
                'nguoi_dung_id'      => 4,
                'ngay_tang_ca'       => Carbon::today()->addDay(),
                'gio_bat_dau'        => '19:00:00',
                'gio_ket_thuc'       => '23:00:00',
                'so_gio_tang_ca'     => 4,
                'loai_tang_ca'       => 'le_tet',
                'ly_do_tang_ca'      => 'Đảm bảo vận hành hệ thống',
                'trang_thai'         => 'huy',
                'nguoi_duyet_id'     => null,
                'thoi_gian_duyet'    => null,
                'ly_do_tu_choi'      => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}
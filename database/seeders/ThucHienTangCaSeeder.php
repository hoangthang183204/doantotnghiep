<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThucHienTangCaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('thuc_hien_tang_ca')->insert([

            [
                'dang_ky_tang_ca_id'     => 1,
                'gio_bat_dau_thuc_te'    => '18:00:00',
                'gio_ket_thuc_thuc_te'   => '21:00:00',
                'so_gio_tang_ca_thuc_te' => 3,
                'cong_viec_da_thuc_hien' => 'Hoàn thành báo cáo cuối tháng',
                'so_cong_tang_ca'        => 0.5,
                'trang_thai'             => 'hoan_thanh',
                'vi_tri_check_in'        => 'Văn phòng Hà Nội',
                'vi_tri_check_out'       => 'Văn phòng Hà Nội',
                'ghi_chu'                => 'Đã hoàn thành đúng kế hoạch',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],

            [
                'dang_ky_tang_ca_id'     => 2,
                'gio_bat_dau_thuc_te'    => '18:30:00',
                'gio_ket_thuc_thuc_te'   => null,
                'so_gio_tang_ca_thuc_te' => 1.5,
                'cong_viec_da_thuc_hien' => 'Đang xử lý công việc tồn đọng',
                'so_cong_tang_ca'        => 0,
                'trang_thai'             => 'dang_lam',
                'vi_tri_check_in'        => 'Văn phòng Hà Nội',
                'vi_tri_check_out'       => null,
                'ghi_chu'                => 'Chưa hoàn thành',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],

            [
                'dang_ky_tang_ca_id'     => 3,
                'gio_bat_dau_thuc_te'    => null,
                'gio_ket_thuc_thuc_te'   => null,
                'so_gio_tang_ca_thuc_te' => 0,
                'cong_viec_da_thuc_hien' => null,
                'so_cong_tang_ca'        => 0,
                'trang_thai'             => 'khong_hoan_thanh',
                'vi_tri_check_in'        => null,
                'vi_tri_check_out'       => null,
                'ghi_chu'                => 'Đăng ký tăng ca bị từ chối',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],

            [
                'dang_ky_tang_ca_id'     => 4,
                'gio_bat_dau_thuc_te'    => null,
                'gio_ket_thuc_thuc_te'   => null,
                'so_gio_tang_ca_thuc_te' => 0,
                'cong_viec_da_thuc_hien' => null,
                'so_cong_tang_ca'        => 0,
                'trang_thai'             => 'chua_lam',
                'vi_tri_check_in'        => null,
                'vi_tri_check_out'       => null,
                'ghi_chu'                => 'Đơn tăng ca đã bị hủy',
                'created_at'             => now(),
                'updated_at'             => now(),
            ],

        ]);
    }
}

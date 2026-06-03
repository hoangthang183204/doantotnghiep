<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanCongCongViecSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phan_cong_cong_viec')->insert([
            [
                'nguoi_giao_id' => 1,
                'nguoi_nhan_id' => 2,
                'cong_viec_id' => 1,
                'phong_ban_id' => 2,
                'vai_tro_trong_cv' => 'chinh',
                'ghi_chu' => 'Hoàn thành trong tháng 3',
                'ngay_bat_dau' => '2024-03-01 08:00:00',
                'deadline' => '2024-03-31 17:00:00',
                'ngay_hoan_thanh' => null,
                'tien_do' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_giao_id' => 2,
                'nguoi_nhan_id' => 3,
                'cong_viec_id' => 2,
                'phong_ban_id' => 2,
                'vai_tro_trong_cv' => 'chinh',
                'ghi_chu' => 'Tối ưu các bảng lớn',
                'ngay_bat_dau' => '2024-04-01 09:00:00',
                'deadline' => '2024-04-15 17:00:00',
                'ngay_hoan_thanh' => null,
                'tien_do' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_giao_id' => 1,
                'nguoi_nhan_id' => 4,
                'cong_viec_id' => 3,
                'phong_ban_id' => 2,
                'vai_tro_trong_cv' => 'ho_tro',
                'ghi_chu' => 'Đã hoàn thành',
                'ngay_bat_dau' => '2024-02-01 10:00:00',
                'deadline' => '2024-02-28 17:00:00',
                'ngay_hoan_thanh' => '2024-02-25 15:00:00',
                'tien_do' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_giao_id' => 1,
                'nguoi_nhan_id' => 2,
                'cong_viec_id' => 4,
                'phong_ban_id' => 2,
                'vai_tro_trong_cv' => 'chinh',
                'ghi_chu' => 'Khẩn cấp',
                'ngay_bat_dau' => '2024-03-10 08:00:00',
                'deadline' => '2024-03-20 17:00:00',
                'ngay_hoan_thanh' => null,
                'tien_do' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_giao_id' => 2,
                'nguoi_nhan_id' => 5,
                'cong_viec_id' => 5,
                'phong_ban_id' => 1,
                'vai_tro_trong_cv' => 'chinh',
                'ghi_chu' => 'Training cho nhân sự mới',
                'ngay_bat_dau' => '2024-03-15 14:00:00',
                'deadline' => '2024-04-30 17:00:00',
                'ngay_hoan_thanh' => null,
                'tien_do' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
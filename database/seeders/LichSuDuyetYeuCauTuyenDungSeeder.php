<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LichSuDuyetYeuCauTuyenDungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lich_su_duyet_yeu_cau_tuyen_dung')->insert([
            [
                'yeu_cau_id' => 1,
                'nguoi_duyet_id' => 1,
                'hanh_dong' => 'duyet',
                'ghi_chu' => 'Đã duyệt yêu cầu',
                'thoi_gian' => '2024-02-20 10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'yeu_cau_id' => 2,
                'nguoi_duyet_id' => 1,
                'hanh_dong' => 'duyet',
                'ghi_chu' => 'Đồng ý tuyển dụng',
                'thoi_gian' => '2024-02-25 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'yeu_cau_id' => 3,
                'nguoi_duyet_id' => 1,
                'hanh_dong' => 'yeu_cau_sua',
                'ghi_chu' => 'Cần bổ sung mô tả công việc chi tiết hơn',
                'thoi_gian' => '2024-03-02 09:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'yeu_cau_id' => 4,
                'nguoi_duyet_id' => 1,
                'hanh_dong' => 'duyet',
                'ghi_chu' => 'OK',
                'thoi_gian' => '2024-03-01 09:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'yeu_cau_id' => 5,
                'nguoi_duyet_id' => 1,
                'hanh_dong' => 'tu_choi',
                'ghi_chu' => 'Chưa có ngân sách cho vị trí này',
                'thoi_gian' => '2024-03-05 11:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LichSuDuyetDonNghiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lich_su_duyet_don_nghi')->insert([
            [
                'don_xin_nghi_id' => 1,
                'cap_duyet' => 1,
                'nguoi_duyet_id' => 1,
                'ket_qua' => 'da_duyet',
                'ghi_chu' => 'Duyệt đơn',
                'thoi_gian_duyet' => '2024-03-01 10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'don_xin_nghi_id' => 2,
                'cap_duyet' => 1,
                'nguoi_duyet_id' => 1,
                'ket_qua' => 'cho_duyet',
                'ghi_chu' => 'Đang chờ xử lý',
                'thoi_gian_duyet' => '2024-03-15 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'don_xin_nghi_id' => 3,
                'cap_duyet' => 1,
                'nguoi_duyet_id' => 2,
                'ket_qua' => 'da_duyet',
                'ghi_chu' => 'Đã duyệt cấp 1',
                'thoi_gian_duyet' => '2024-03-05 09:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'don_xin_nghi_id' => 3,
                'cap_duyet' => 2,
                'nguoi_duyet_id' => 1,
                'ket_qua' => 'da_duyet',
                'ghi_chu' => 'Đã duyệt cấp 2',
                'thoi_gian_duyet' => '2024-03-05 15:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'don_xin_nghi_id' => 4,
                'cap_duyet' => 1,
                'nguoi_duyet_id' => 1,
                'ket_qua' => 'tu_choi',
                'ghi_chu' => 'Trùng lịch công tác',
                'thoi_gian_duyet' => '2024-03-20 11:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
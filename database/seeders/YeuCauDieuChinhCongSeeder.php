<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YeuCauDieuChinhCongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('yeu_cau_dieu_chinh_cong')->insert([
            [
                'nguoi_dung_id' => 3,
                'ngay' => '2024-02-28',
                'gio_vao' => '08:00:00',
                'gio_ra' => '17:00:00',
                'ly_do' => 'Quên chấm công ra',
                'tep_dinh_kem' => null,
                'trang_thai' => 'da_duyet',
                'duyet_boi' => 2,
                'duyet_vao' => '2024-03-01 09:00:00',
                'ghi_chu_duyet' => 'Đã duyệt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 4,
                'ngay' => '2024-03-05',
                'gio_vao' => '08:30:00',
                'gio_ra' => '17:30:00',
                'ly_do' => 'Vào muộn do lý do khách quan',
                'tep_dinh_kem' => '/requests/cham_cong_4.pdf',
                'trang_thai' => 'cho_duyet',
                'duyet_boi' => null,
                'duyet_vao' => null,
                'ghi_chu_duyet' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 5,
                'ngay' => '2024-03-10',
                'gio_vao' => '09:00:00',
                'gio_ra' => '18:00:00',
                'ly_do' => 'Đi công tác về muộn',
                'tep_dinh_kem' => null,
                'trang_thai' => 'da_duyet',
                'duyet_boi' => 1,
                'duyet_vao' => '2024-03-11 10:00:00',
                'ghi_chu_duyet' => 'OK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 2,
                'ngay' => '2024-03-12',
                'gio_vao' => '08:00:00',
                'gio_ra' => '16:30:00',
                'ly_do' => 'Có việc gia đình',
                'tep_dinh_kem' => null,
                'trang_thai' => 'tu_choi',
                'duyet_boi' => 1,
                'duyet_vao' => '2024-03-13 14:00:00',
                'ghi_chu_duyet' => 'Không hợp lệ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_dung_id' => 1,
                'ngay' => '2024-03-15',
                'gio_vao' => '07:00:00',
                'gio_ra' => '17:00:00',
                'ly_do' => 'Làm thêm giờ buổi sáng',
                'tep_dinh_kem' => null,
                'trang_thai' => 'cho_duyet',
                'duyet_boi' => null,
                'duyet_vao' => null,
                'ghi_chu_duyet' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TinNhanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tin_nhan')->insert([
            [
                'nguoi_gui_id' => 1,
                'nguoi_nhan_id' => 2,
                'loai_tin_nhan' => 'text',
                'noi_dung' => 'Chào bạn, hôm nay có việc gì mới không?',
                'duong_dan_file' => null,
                'da_doc' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_gui_id' => 2,
                'nguoi_nhan_id' => 1,
                'loai_tin_nhan' => 'text',
                'noi_dung' => 'Dạ em đang làm báo cáo tuần ạ',
                'duong_dan_file' => null,
                'da_doc' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_gui_id' => 1,
                'nguoi_nhan_id' => 3,
                'loai_tin_nhan' => 'file',
                'noi_dung' => 'Đây là tài liệu họp ngày mai',
                'duong_dan_file' => '/messages/tai_lieu_hop.pdf',
                'da_doc' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_gui_id' => 3,
                'nguoi_nhan_id' => 2,
                'loai_tin_nhan' => 'text',
                'noi_dung' => 'Bạn có thể check giúp tôi lỗi này được không?',
                'duong_dan_file' => null,
                'da_doc' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nguoi_gui_id' => 1,
                'nguoi_nhan_id' => 5,
                'loai_tin_nhan' => 'system',
                'noi_dung' => 'Hệ thống sẽ bảo trì lúc 22h tối nay',
                'duong_dan_file' => null,
                'da_doc' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
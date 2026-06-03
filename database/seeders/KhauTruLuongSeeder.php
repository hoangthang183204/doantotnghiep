<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhauTruLuongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('khau_tru_luong')->insert([
            [
                'luong_nhan_vien_id' => 1,
                'loai_khau_tru' => 'bao_hiem_xa_hoi',
                'so_tien' => 2100000,
                'ghi_chu' => 'BHXH 8%',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 1,
                'loai_khau_tru' => 'bao_hiem_y_te',
                'so_tien' => 450000,
                'ghi_chu' => 'BHYT 1.5%',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 1,
                'loai_khau_tru' => 'bao_hiem_that_nghiep',
                'so_tien' => 300000,
                'ghi_chu' => 'BHTN 1%',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 1,
                'loai_khau_tru' => 'thue_thu_nhap',
                'so_tien' => 3000000,
                'ghi_chu' => 'Thuế TNCN',
                'created_at' => now(),
            ],
            [
                'luong_nhan_vien_id' => 2,
                'loai_khau_tru' => 'bao_hiem_xa_hoi',
                'so_tien' => 1400000,
                'ghi_chu' => 'BHXH 8%',
                'created_at' => now(),
            ],
        ]);
    }
}
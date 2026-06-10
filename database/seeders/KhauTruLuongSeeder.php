<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhauTruLuongSeeder extends Seeder
{
    public function run(): void
    {
        $luongNhanVienIds = DB::table('luong_nhan_vien')
            ->pluck('id');

        foreach ($luongNhanVienIds as $id) {

            DB::table('khau_tru_luong')->insert([
                'luong_nhan_vien_id' => $id,

                'loai_khau_tru' => fake()->randomElement([
                    'bhxh',
                    'bhyt',
                    'bhtn',
                    'thue_tncn',
                    'khau_tru_khac',
                ]),

                'so_tien' => rand(50000, 500000),

                'ghi_chu' => fake()->sentence(),

                'created_at' => now(),
            ]);
        }
    }
}
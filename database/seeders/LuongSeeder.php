<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuongSeeder extends Seeder
{
    public function run(): void
    {
        $nguoiDungs = DB::table('nguoi_dung')->pluck('id');

        $hopDongIds = DB::table('hop_dong_lao_dong')
            ->pluck('id')
            ->toArray();

        if (empty($hopDongIds)) {
            $this->command->error(
                'Bảng hop_dong_lao_dong chưa có dữ liệu.'
            );
            return;
        }

        foreach ($nguoiDungs as $userId) {

            DB::table('luong')->insert([
                'nguoi_dung_id' => $userId,

                'hop_dong_lao_dong_id' =>
                    fake()->randomElement($hopDongIds),

                'luong_co_ban' => rand(7000000, 20000000),

                'phu_cap' => rand(500000, 2000000),

                'tien_thuong' => rand(0, 1000000),

                'tien_phat' => rand(0, 300000),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
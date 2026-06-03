<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiNhanhCongTySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chi_nhanh_cong_ty')->insert([
            [
                'ten' => 'Chi nhánh Hà Nội',
                'ma' => 'HN001',
                'dia_chi' => 'Số 1 Lê Thái Tổ, Hoàn Kiếm, Hà Nội',
                'dien_thoai' => '0241234567',
                'email' => 'hanoi@company.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Chi nhánh Hồ Chí Minh',
                'ma' => 'HCM001',
                'dia_chi' => 'Số 1 Nguyễn Huệ, Quận 1, TP.HCM',
                'dien_thoai' => '0281234567',
                'email' => 'hcm@company.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Chi nhánh Đà Nẵng',
                'ma' => 'DN001',
                'dia_chi' => 'Số 1 Bạch Đằng, Hải Châu, Đà Nẵng',
                'dien_thoai' => '02361234567',
                'email' => 'danang@company.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Chi nhánh Cần Thơ',
                'ma' => 'CT001',
                'dia_chi' => 'Số 1 Nguyễn Trãi, Ninh Kiều, Cần Thơ',
                'dien_thoai' => '02921234567',
                'email' => 'cantho@company.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ten' => 'Chi nhánh Bình Dương',
                'ma' => 'BD001',
                'dia_chi' => 'Số 1 Lê Lợi, Thủ Dầu Một, Bình Dương',
                'dien_thoai' => '02741234567',
                'email' => 'binhduong@company.com',
                'truong_chi_nhanh_id' => null,
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
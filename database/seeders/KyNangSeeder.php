<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KyNangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ky_nang')->insert([
            ['ten' => 'Lập trình PHP', 'danh_muc' => 'Lập trình', 'mo_ta' => 'Phát triển web với PHP', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Laravel Framework', 'danh_muc' => 'Lập trình', 'mo_ta' => 'Framework PHP Laravel', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'React.js', 'danh_muc' => 'Lập trình', 'mo_ta' => 'Thư viện JavaScript cho UI', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'MySQL', 'danh_muc' => 'Database', 'mo_ta' => 'Quản trị CSDL MySQL', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Quản lý dự án', 'danh_muc' => 'Quản lý', 'mo_ta' => 'Quản lý và điều phối dự án', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Giao tiếp tiếng Anh', 'danh_muc' => 'Ngoại ngữ', 'mo_ta' => 'Giao tiếp tiếng Anh thành thạo', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ten' => 'Kế toán', 'danh_muc' => 'Tài chính', 'mo_ta' => 'Kế toán doanh nghiệp', 'trang_thai' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
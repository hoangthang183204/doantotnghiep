<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NguoiPhuThuocSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nguoi_phu_thuoc')->insert([
[
    'ho_so_id' => 1,
    'ho_ten' => 'Nguyễn Minh An',
    'ngay_sinh' => '2018-05-12',
    'quan_he' => 'con',
    'ma_so_thue' => '0123456789',
    'ngay_bat_dau' => '2026-01-01',
    'ngay_ket_thuc' => null,
    'ghi_chu' => 'Con trai',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'ho_so_id' => 2,
    'ho_ten' => 'Trần Thị Hoa',
    'ngay_sinh' => '2016-09-20',
    'quan_he' => 'con',
    'ma_so_thue' => '0123456790',
    'ngay_bat_dau' => '2026-01-01',
    'ngay_ket_thuc' => null,
    'ghi_chu' => 'Con gái',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'ho_so_id' => 3,
    'ho_ten' => 'Lê Văn Bình',
    'ngay_sinh' => '1958-03-14',
    'quan_he' => 'cha',
    'ma_so_thue' => '0123456791',
    'ngay_bat_dau' => '2026-01-01',
    'ngay_ket_thuc' => null,
    'ghi_chu' => 'Cha đã nghỉ hưu',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'ho_so_id' => 4,
    'ho_ten' => 'Phạm Thị Lan',
    'ngay_sinh' => '1962-11-28',
    'quan_he' => 'me',
    'ma_so_thue' => '0123456792',
    'ngay_bat_dau' => '2026-01-01',
    'ngay_ket_thuc' => null,
    'ghi_chu' => 'Mẹ không có thu nhập',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'ho_so_id' => 5,
    'ho_ten' => 'Nguyễn Thị Mai',
    'ngay_sinh' => '1994-08-17',
    'quan_he' => 'vo',
    'ma_so_thue' => '0123456793',
    'ngay_bat_dau' => '2026-01-01',
    'ngay_ket_thuc' => null,
    'ghi_chu' => 'Vợ không có thu nhập',
    'created_at' => now(),
    'updated_at' => now(),
],
        ]);
    }
}
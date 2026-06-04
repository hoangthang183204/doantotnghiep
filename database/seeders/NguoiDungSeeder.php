<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nguoi_dung')->insert([
            ['ten_dang_nhap' => 'admin', 'email' => 'admin@hrflow.com', 'password' => Hash::make('123456'), 'vai_tro_id' => 1, 'email_verified_at' => 1, 'remember_token' => null, 'trang_thai' => 1, 'trang_thai_cong_viec' => 'dang_lam', 'lan_dang_nhap_cuoi' => now(), 'ip_dang_nhap_cuoi' => '127.0.0.1', 'phong_ban_id' => 1, 'chuc_vu_id' => 1, 'branch_id' => 1, 'da_hoan_thanh_ho_so' => 1, 'dang_nhap_lan_dau' => 0, 'theme' => 'dark', 'created_at' => now(), 'updated_at' => now()],
            ['ten_dang_nhap' => 'hr.manager', 'email' => 'hr@hrflow.com', 'password' => Hash::make('123456'), 'vai_tro_id' => 2, 'email_verified_at' => 1, 'remember_token' => null, 'trang_thai' => 1, 'trang_thai_cong_viec' => 'dang_lam', 'lan_dang_nhap_cuoi' => now(), 'ip_dang_nhap_cuoi' => '192.168.1.1', 'phong_ban_id' => 2, 'chuc_vu_id' => 2, 'branch_id' => 1, 'da_hoan_thanh_ho_so' => 1, 'dang_nhap_lan_dau' => 0, 'theme' => 'light', 'created_at' => now(), 'updated_at' => now()],
            ['ten_dang_nhap' => 'truongphong.it', 'email' => 'it.manager@hrflow.com', 'password' => Hash::make('123456'), 'vai_tro_id' => 4, 'email_verified_at' => 1, 'remember_token' => null, 'trang_thai' => 1, 'trang_thai_cong_viec' => 'dang_lam', 'lan_dang_nhap_cuoi' => now(), 'ip_dang_nhap_cuoi' => '192.168.1.2', 'phong_ban_id' => 4, 'chuc_vu_id' => 6, 'branch_id' => 1, 'da_hoan_thanh_ho_so' => 1, 'dang_nhap_lan_dau' => 0, 'theme' => 'light', 'created_at' => now(), 'updated_at' => now()],
            ['ten_dang_nhap' => 'nhanvien.dev', 'email' => 'dev@hrflow.com', 'password' => Hash::make('123456'), 'vai_tro_id' => 3, 'email_verified_at' => 1, 'remember_token' => null, 'trang_thai' => 1, 'trang_thai_cong_viec' => 'dang_lam', 'lan_dang_nhap_cuoi' => now(), 'ip_dang_nhap_cuoi' => '192.168.1.3', 'phong_ban_id' => 4, 'chuc_vu_id' => 7, 'branch_id' => 1, 'da_hoan_thanh_ho_so' => 1, 'dang_nhap_lan_dau' => 0, 'theme' => 'light', 'created_at' => now(), 'updated_at' => now()],
            ['ten_dang_nhap' => 'ke.toan', 'email' => 'ke.toan@hrflow.com', 'password' => Hash::make('123456'), 'vai_tro_id' => 5, 'email_verified_at' => 1, 'remember_token' => null, 'trang_thai' => 1, 'trang_thai_cong_viec' => 'dang_lam', 'lan_dang_nhap_cuoi' => now(), 'ip_dang_nhap_cuoi' => '192.168.1.4', 'phong_ban_id' => 3, 'chuc_vu_id' => 5, 'branch_id' => 2, 'da_hoan_thanh_ho_so' => 1, 'dang_nhap_lan_dau' => 0, 'theme' => 'light', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
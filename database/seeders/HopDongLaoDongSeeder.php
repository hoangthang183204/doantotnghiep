<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HopDongLaoDongSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('nguoi_dung')->get();

        // Lương cơ bản cố định theo từng nhân viên (để demo lương ổn định, không random)
        $luongCoBan = [
            1 => 30000000, // admin / giám đốc
            2 => 18000000, // trưởng phòng nhân sự
            3 => 18000000, // trưởng phòng IT
            4 => 12000000, // nhân viên dev
            5 => 11000000, // nhân viên kế toán
        ];

        // Hợp đồng bắt đầu đầu năm để có hiệu lực cho các tháng tính lương demo
        $ngayBatDau = Carbon::now()->startOfYear()->toDateString();

        foreach ($users as $user) {
            DB::table('hop_dong_lao_dong')->insert([
                'created_by'          => 1,
                'nguoi_dung_id'       => $user->id,
                'chuc_vu_id'          => $user->chuc_vu_id,
                'so_hop_dong'         => 'HD' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'loai_hop_dong'       => 'khong_xac_dinh_thoi_han',
                'ngay_bat_dau'        => $ngayBatDau,
                'ngay_ket_thuc'       => null,
                'luong_co_ban'        => $luongCoBan[$user->id] ?? 10000000,
                'phu_cap_id'          => null, // phụ cấp quản lý qua bảng phu_cap_nhan_vien
                'hinh_thuc_lam_viec'  => 'full_time',
                'dia_diem_lam_viec'   => 'Hà Nội',
                'trang_thai_hop_dong' => 'hieu_luc',
                'trang_thai_ky'       => 'da_ky',
                'nguoi_ky_id'         => 1,
                'thoi_gian_ky'        => $ngayBatDau,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }
}

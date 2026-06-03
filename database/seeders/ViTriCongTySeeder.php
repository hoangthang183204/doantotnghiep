<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViTriCongTySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vi_tri_cong_ty')->insert([
            [
                'dia_chi' => 'Số 1 Lê Thái Tổ, Hoàn Kiếm, Hà Nội',
                'su_dung_kiem_tra_ip' => 1,
                'dai_ip_cho_phep' => '192.168.1.0/24',
                'yeu_cau_ten_wifi' => 'Company_Hanoi',
                'yeu_cau_loc_dia_chi_mac' => 1,
                'dia_chi_mac_duoc_phep' => json_encode(['AA:BB:CC:DD:EE:FF', 'AA:BB:CC:DD:EE:01', 'AA:BB:CC:DD:EE:02']),
                'chi_nhanh_id' => 1,
                'ban_kinh_cho_phep' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi' => 'Số 1 Nguyễn Huệ, Quận 1, TP.HCM',
                'su_dung_kiem_tra_ip' => 1,
                'dai_ip_cho_phep' => '10.0.0.0/24',
                'yeu_cau_ten_wifi' => 'Company_HCM',
                'yeu_cau_loc_dia_chi_mac' => 0,
                'dia_chi_mac_duoc_phep' => null,
                'chi_nhanh_id' => 2,
                'ban_kinh_cho_phep' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi' => 'Số 1 Bạch Đằng, Hải Châu, Đà Nẵng',
                'su_dung_kiem_tra_ip' => 1,
                'dai_ip_cho_phep' => '172.16.0.0/24',
                'yeu_cau_ten_wifi' => 'Company_Danang',
                'yeu_cau_loc_dia_chi_mac' => 0,
                'dia_chi_mac_duoc_phep' => null,
                'chi_nhanh_id' => 3,
                'ban_kinh_cho_phep' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi' => 'Số 1 Nguyễn Trãi, Ninh Kiều, Cần Thơ',
                'su_dung_kiem_tra_ip' => 0,
                'dai_ip_cho_phep' => null,
                'yeu_cau_ten_wifi' => null,
                'yeu_cau_loc_dia_chi_mac' => 0,
                'dia_chi_mac_duoc_phep' => null,
                'chi_nhanh_id' => 4,
                'ban_kinh_cho_phep' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dia_chi' => 'Số 1 Lê Lợi, Thủ Dầu Một, Bình Dương',
                'su_dung_kiem_tra_ip' => 1,
                'dai_ip_cho_phep' => '192.168.10.0/24',
                'yeu_cau_ten_wifi' => 'Company_BinhDuong',
                'yeu_cau_loc_dia_chi_mac' => 0,
                'dia_chi_mac_duoc_phep' => null,
                'chi_nhanh_id' => 5,
                'ban_kinh_cho_phep' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
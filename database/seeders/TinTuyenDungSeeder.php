<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TinTuyenDung;
use App\Models\PhongBan;
use App\Models\ChucVu;

class TinTuyenDungSeeder extends Seeder
{
    public function run()
    {
        $phongBan = PhongBan::first();
        $chucVu = ChucVu::first();

        if ($phongBan && $chucVu) {
            TinTuyenDung::create([
                'tieu_de' => 'Tuyển Dụng TTS Web',
                'ma' => 'TD' . date('Ymd') . '001',
                'phong_ban_id' => $phongBan->id,
                'chuc_vu_id' => $chucVu->id,
                'vai_tro_id' => null,
                'so_vi_tri' => 3,
                'mo_ta_cong_viec' => 'Thực tập sinh chuyên ngành Back End',
                'yeu_cau' => json_encode(['Đang là năm 3 hoặc cuối', 'Có kiến thức về PHP', 'Có tinh thần học hỏi']),
                'phuc_loi' => json_encode(['Hỗ trợ xăng xe', 'Đào tạo chuyên sâu', 'Cơ hội trở thành nhân viên chính thức']),
                'ky_nang_yeu_cau' => json_encode(['PHP', 'Laravel', 'MySQL', 'Git']),
                'han_nop_ho_so' => now()->addDays(30),
                'trang_thai' => 'dang_tuyen',
                'loai_hop_dong' => 'thu_viec',
                'cap_do_kinh_nghiem' => 'intern',
                'kinh_nghiem_toi_thieu' => 0,
                'kinh_nghiem_toi_da' => 1,
                'luong_toi_thieu' => 3000000,
                'luong_toi_da' => 5000000,
                'trinh_do_hoc_van' => 'Cao đẳng trở lên',
                'lam_viec_tu_xa' => true,
                'tuyen_gap' => true,
                'nguoi_dang_id' => 1,
                'thoi_gian_dang' => now(),
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaiLieu;

class TaiLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaiLieu::create([
            'nguoi_dung_id' => 1, // phải tồn tại user id 1
            'ung_vien_id' => null,
            'loai_tai_lieu' => 'cv',
            'tieu_de' => 'CV nhân viên test',
            'mo_ta' => null,
            'ten_file_goc' => 'cv_test.pdf',
            'duong_dan_file' => 'tai-lieu/cv_test.pdf',
            'kich_thuoc_file' => 120000,
            'loai_mime' => 'application/pdf',
            'bao_mat' => false,
            'ngay_het_han' => null,
            'nguoi_tai_len_id' => 1,
            'thoi_gian_tai_len' => now(),
            'trang_thai' => 'hop_le',
        ]);
    }
}

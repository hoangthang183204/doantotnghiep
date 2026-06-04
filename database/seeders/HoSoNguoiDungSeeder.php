<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoSoNguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ho_so_nguoi_dung')->insert([
            ['nguoi_dung_id' => 1, 'ma_nhan_vien' => 'NV001', 'ho' => 'Nguyễn', 'ten' => 'Văn Admin', 'email_cong_ty' => 'admin@hrflow.com', 'so_dien_thoai' => '0901234567', 'ngay_sinh' => '1985-01-15', 'gioi_tinh' => 'nam', 'dia_chi_hien_tai' => 'Hà Nội', 'dia_chi_thuong_tru' => 'Hà Nội', 'cmnd_cccd' => '001201000001', 'so_ho_chieu' => null, 'tinh_trang_hon_nhan' => 'da_ket_hon', 'anh_dai_dien' => null, 'lien_he_khan_cap' => 'Nguyễn Thị B', 'sdt_khan_cap' => '0987654321', 'quan_he_khan_cap' => 'Vợ', 'anh_cccd_truoc' => null, 'anh_cccd_sau' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 2, 'ma_nhan_vien' => 'NV002', 'ho' => 'Trần', 'ten' => 'Thị Mai', 'email_cong_ty' => 'hr@hrflow.com', 'so_dien_thoai' => '0901234568', 'ngay_sinh' => '1988-05-20', 'gioi_tinh' => 'nu', 'dia_chi_hien_tai' => 'Hà Nội', 'dia_chi_thuong_tru' => 'Hà Nội', 'cmnd_cccd' => '001201000002', 'so_ho_chieu' => null, 'tinh_trang_hon_nhan' => 'doc_than', 'anh_dai_dien' => null, 'lien_he_khan_cap' => 'Trần Văn C', 'sdt_khan_cap' => '0987654322', 'quan_he_khan_cap' => 'Cha', 'anh_cccd_truoc' => null, 'anh_cccd_sau' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 3, 'ma_nhan_vien' => 'NV003', 'ho' => 'Lê', 'ten' => 'Văn Hùng', 'email_cong_ty' => 'it.manager@hrflow.com', 'so_dien_thoai' => '0901234569', 'ngay_sinh' => '1986-12-25', 'gioi_tinh' => 'nam', 'dia_chi_hien_tai' => 'Hà Nội', 'dia_chi_thuong_tru' => 'Hà Nội', 'cmnd_cccd' => '001201000003', 'so_ho_chieu' => null, 'tinh_trang_hon_nhan' => 'da_ket_hon', 'anh_dai_dien' => null, 'lien_he_khan_cap' => 'Lê Thị D', 'sdt_khan_cap' => '0987654323', 'quan_he_khan_cap' => 'Vợ', 'anh_cccd_truoc' => null, 'anh_cccd_sau' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 4, 'ma_nhan_vien' => 'NV004', 'ho' => 'Phạm', 'ten' => 'Minh Tuấn', 'email_cong_ty' => 'dev@hrflow.com', 'so_dien_thoai' => '0901234570', 'ngay_sinh' => '1992-07-15', 'gioi_tinh' => 'nam', 'dia_chi_hien_tai' => 'TP.HCM', 'dia_chi_thuong_tru' => 'TP.HCM', 'cmnd_cccd' => '002201000001', 'so_ho_chieu' => null, 'tinh_trang_hon_nhan' => 'doc_than', 'anh_dai_dien' => null, 'lien_he_khan_cap' => 'Phạm Thị E', 'sdt_khan_cap' => '0987654324', 'quan_he_khan_cap' => 'Mẹ', 'anh_cccd_truoc' => null, 'anh_cccd_sau' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nguoi_dung_id' => 5, 'ma_nhan_vien' => 'NV005', 'ho' => 'Hoàng', 'ten' => 'Thị Hoa', 'email_cong_ty' => 'ke.toan@hrflow.com', 'so_dien_thoai' => '0901234571', 'ngay_sinh' => '1990-03-10', 'gioi_tinh' => 'nu', 'dia_chi_hien_tai' => 'TP.HCM', 'dia_chi_thuong_tru' => 'TP.HCM', 'cmnd_cccd' => '002201000002', 'so_ho_chieu' => null, 'tinh_trang_hon_nhan' => 'doc_than', 'anh_dai_dien' => null, 'lien_he_khan_cap' => 'Hoàng Văn F', 'sdt_khan_cap' => '0987654325', 'quan_he_khan_cap' => 'Cha', 'anh_cccd_truoc' => null, 'anh_cccd_sau' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
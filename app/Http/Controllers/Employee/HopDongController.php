<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HopDongController extends Controller
{
    public function getHopDongCuaToi()
    {
        // 1. Lấy ID tài khoản người dùng đang đăng nhập
        $userId = Auth::id(); 

        // 2. Thực hiện Query kết nối các bảng theo cấu trúc chính xác từ Seeder
        $hopDong = DB::table('hop_dong_lao_dong')
            ->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->leftJoin('ho_so_nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
            ->leftJoin('chuc_vu', 'hop_dong_lao_dong.chuc_vu_id', '=', 'chuc_vu.id')
            ->leftJoin('phong_ban', 'chuc_vu.phong_ban_id', '=', 'phong_ban.id')
            ->where('hop_dong_lao_dong.nguoi_dung_id', $userId)
            ->select(
                'hop_dong_lao_dong.*',
                'nguoi_dung.ten_dang_nhap',
                'ho_so_nguoi_dung.ma_nhan_vien as nhan_vien_ma_nv', // Cột ma_nhan_vien từ HoSoNguoiDungSeeder
                'chuc_vu.ten as ten_chuc_vu',                       // Cột ten từ ChucVuSeeder
                'phong_ban.ten_phong_ban as ten_phong_ban',         // Cột ten_phong_ban từ PhongBanSeeder
                // Kết hợp cột ho và ten từ bảng hồ sơ thành họ tên đầy đủ
                DB::raw("CONCAT(ho_so_nguoi_dung.ho, ' ', ho_so_nguoi_dung.ten) as nhan_vien_ho_ten") 
            )
            ->first(); 

        // 3. Trả dữ liệu ra giao diện hiển thị
        return view('employee.hop-dong.index', compact('hopDong'));
    }
}
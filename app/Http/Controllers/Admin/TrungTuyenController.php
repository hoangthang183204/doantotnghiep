<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UngVien;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrungTuyenController extends Controller
{
    /**
     * Hiển thị danh sách ứng viên đã TRÚNG TUYỂN (Trạng thái: dat)
     */
    public function index(Request $request)
    {
        $query = UngVien::with([
            'tinTuyenDung.phongBan'
        ])->where('trang_thai', 'dat');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('ho', 'like', "%{$keyword}%")
                  ->orWhere('ten', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$keyword}%")
                  ->orWhere('ma_ho_so', 'like', "%{$keyword}%");
            });
        }

        $ungViens = $query->latest()->paginate(10);

        return view('admin.trung-tuyen.index', compact('ungViens'));
    }

    /**
     * Chuyển đổi ứng viên thành nhân viên chính thức (Cấp tài khoản hệ thống)
     */
    public function convertToEmployee($id)
    {
        // Tìm ứng viên theo Eloquent với quan hệ tinTuyenDung và phongBan
        $ungVien = UngVien::with(['tinTuyenDung.phongBan'])->findOrFail($id);

        // Kiểm tra trùng lặp email trên bảng người dùng
        $checkUser = NguoiDung::where('email', $ungVien->email)->exists();
        if ($checkUser) {
            return redirect()->back()->with('error', 'Email ứng viên này đã tồn tại trên hệ thống người dùng.');
        }

        DB::beginTransaction();
        try {
            // Lấy phòng ban từ tin tuyển dụng hoặc từ ứng viên
            $phongBanId = null;
            
            // Ưu tiên lấy phòng ban từ tin tuyển dụng
            if ($ungVien->tinTuyenDung && $ungVien->tinTuyenDung->phong_ban_id) {
                $phongBanId = $ungVien->tinTuyenDung->phong_ban_id;
            } 
            // Nếu không có, lấy từ ứng viên
            elseif ($ungVien->phong_ban_id) {
                $phongBanId = $ungVien->phong_ban_id;
            }

            // Tạo tên đăng nhập từ email
            $tenDangNhap = explode('@', $ungVien->email)[0];
            
            // Xử lý nếu trùng tên đăng nhập
            $originalTenDangNhap = $tenDangNhap;
            $counter = 1;
            while (NguoiDung::where('ten_dang_nhap', $tenDangNhap)->exists()) {
                $tenDangNhap = $originalTenDangNhap . $counter;
                $counter++;
            }

            $matKhauMacDinh = '123456a@';

            // 1. Tạo người dùng mới - CÓ CẬP NHẬT PHÒNG BAN
            $nguoiDung = NguoiDung::create([
                'ten_dang_nhap' => $tenDangNhap,
                'email' => $ungVien->email,
                'password' => Hash::make($matKhauMacDinh),
                'vai_tro_id' => 2, // ID vai trò mặc định cho Nhân viên
                'trang_thai' => 1, // 1: Hoạt động
                'trang_thai_cong_viec' => 'dang_lam',
                'phong_ban_id' => $phongBanId, // Cập nhật phòng ban
                'chuc_vu_id' => null,
                'branch_id' => null,
                'lan_dang_nhap_cuoi' => null,
                'ip_dang_nhap_cuoi' => null,
            ]);

            // 2. Cập nhật cột nguoi_dung_id và phong_ban_id cho ứng viên
            $ungVien->update([
                'nguoi_dung_id' => $nguoiDung->id,
                'phong_ban_id' => $phongBanId, // Cập nhật phòng ban cho ứng viên
            ]);

            DB::commit();
            
            $phongBanName = $ungVien->tinTuyenDung?->phongBan?->ten_phong_ban ?? 'Chưa xác định';
            
            return redirect()->back()->with('success', 
                "Chuyển thành công ứng viên <b>{$ungVien->ho} {$ungVien->ten}</b> thành nhân viên.<br>
                Tài khoản: <b>{$tenDangNhap}</b> | Mật khẩu: <b>{$matKhauMacDinh}</b><br>
                Phòng ban: <b>{$phongBanName}</b>"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình xử lý: ' . $e->getMessage());
        }
    }
}
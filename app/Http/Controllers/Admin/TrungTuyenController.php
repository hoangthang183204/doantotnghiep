<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UngVien; // Gọi Model UngVien thay vì dùng DB table bừa bãi
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
        // Sử dụng Eloquent kèm theo Eager Loading để đồng bộ dữ liệu với View cũ của bạn
        $query = UngVien::with([
            'tinTuyenDung.phongBan'
        ])->where('trang_thai', 'dat'); // Chuỗi 'dat' chuẩn cú pháp PHP

        // Bộ lọc tìm kiếm từ khóa tương tự UngVienController
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

        // Sắp xếp giảm dần và phân trang
        $ungViens = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.trung-tuyen.index', compact('ungViens'));
    }

    /**
     * Chuyển đổi ứng viên thành nhân viên chính thức (Cấp tài khoản hệ thống)
     */
    public function convertToEmployee($id)
    {
        // Tìm ứng viên theo Eloquent
        $ungVien = UngVien::findOrFail($id);

        // Kiểm tra trùng lặp email trên bảng người dùng
        $checkUser = DB::table('nguoi_dungs')->where('email', $ungVien->email)->exists();
        if ($checkUser) {
            return redirect()->back()->with('error', 'Email ứng viên này đã tồn tại trên hệ thống người dùng.');
        }

        DB::beginTransaction();
        try {
            // Tách email lấy tên đăng nhập tự động (nguyenvanan@gmail.com -> nguyenvanan)
            $tenDangNhap = explode('@', $ungVien->email)[0];
            
            // Xử lý nếu trùng tên đăng nhập trong hệ thống người dùng
            if (DB::table('nguoi_dungs')->where('ten_dang_nhap', $tenDangNhap)->exists()) {
                $tenDangNhap = $tenDangNhap . rand(10, 99);
            }

            $matKhauMacDinh = '123456a@'; // Mật khẩu mặc định cấp phát ban đầu

            // 1. Chèn dữ liệu mới vào bảng người dùng (nguoi_dungs)
            $nguoiDungId = DB::table('nguoi_dungs')->insertGetId([
                'ten_dang_nhap' => $tenDangNhap,
                'mat_khau'      => Hash::make($matKhauMacDinh),
                'email'         => $ungVien->email,
                'so_dien_thoai' => $ungVien->so_dien_thoai,
                'vai_tro_id'    => 2,  // ID vai trò mặc định cho Nhân viên
                'is_active'     => 1,  // Kích hoạt tài khoản trực tiếp
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 2. Cập nhật cột nguoi_dung_id trực tiếp qua Eloquent Model để ghi nhận đã tiếp nhận thành công
            $ungVien->update([
                'nguoi_dung_id' => $nguoiDungId
            ]);

            DB::commit();
            return redirect()->back()->with('success', "Chuyển thành công ứng viên <b>{$ungVien->ho} {$ungVien->ten}</b> thành nhân viên.<br>Tài khoản: <b>{$tenDangNhap}</b> | Mật khẩu: <b>{$matKhauMacDinh}</b>");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình xử lý: ' . $e->getMessage());
        }
    }
}
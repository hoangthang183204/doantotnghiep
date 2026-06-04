<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BangLuong;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Models\ChamCong;
use App\Models\HopDongLaoDong;
use App\Models\PhuCap;
use App\Models\PhuCapNhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BangLuongController extends Controller
{
    // Danh sách bảng lương
    public function index()
    {
        $bangLuongs = BangLuong::with('nguoiXuLy', 'nguoiPheDuyet')
            ->orderBy('nam', 'desc')
            ->orderBy('thang', 'desc')
            ->paginate(10);
        
        return view('admin.bang-luong.index', compact('bangLuongs'));
    }
    
    // Form tạo bảng lương mới
    public function create()
    {
        $thangHienTai = Carbon::now()->month;
        $namHienTai = Carbon::now()->year;
        
        // Chỉ được tính lương cho tháng trước
        $thangTinh = $thangHienTai == 1 ? 12 : $thangHienTai - 1;
        $namTinh = $thangHienTai == 1 ? $namHienTai - 1 : $namHienTai;
        
        // Kiểm tra đã có bảng lương tháng này chưa
        $exists = BangLuong::where('thang', $thangTinh)->where('nam', $namTinh)->exists();
        
        // Lấy danh sách nhân viên chưa tính lương
        $daTinhLuong = LuongNhanVien::where('luong_thang', $thangTinh)
            ->where('luong_nam', $namTinh)
            ->pluck('nguoi_dung_id')
            ->toArray();
        
        $nhanViens = NguoiDung::with('ho_so', 'chuc_vu', 'hop_dongs')
            ->where('trang_thai', 1)
            ->whereNotIn('id', $daTinhLuong)
            ->get();
        
        return view('admin.bang-luong.create', compact('thangTinh', 'namTinh', 'exists', 'nhanViens'));
    }
    
    // Tính lương cho nhân viên
    public function tinhLuong(Request $request)
    {
        $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam' => 'required|integer',
            'nhan_vien_ids' => 'required|array',
            'nhan_vien_ids.*' => 'exists:nguoi_dung,id',
        ]);
        
        $bangLuong = BangLuong::create([
            'ma_bang_luong' => 'BL' . $request->nam . sprintf('%02d', $request->thang) . '_' . strtoupper(uniqid()),
            'loai_bang_luong' => 'thang',
            'nam' => $request->nam,
            'thang' => $request->thang,
            'trang_thai' => 'dang_tao',
            'nguoi_xu_ly_id' => auth()->id(),
            'thoi_gian_xu_ly' => now(),
        ]);
        
        foreach ($request->nhan_vien_ids as $nhanVienId) {
            $this->tinhLuongChoNhanVien($nhanVienId, $bangLuong->id, $request->thang, $request->nam);
        }
        
        return redirect()->route('admin.bang-luong.show', $bangLuong->id)
            ->with('success', 'Tính lương thành công!');
    }
    
    // Tính lương cho 1 nhân viên
    private function tinhLuongChoNhanVien($nhanVienId, $bangLuongId, $thang, $nam)
    {
        $nhanVien = NguoiDung::with('ho_so', 'chuc_vu', 'hop_dongs')->find($nhanVienId);
        
        // Lấy hợp đồng hiện tại
        $hopDong = $nhanVien->hop_dongs()->where('ngay_bat_dau', '<=', "$nam-$thang-01")
            ->where(function($q) use ($nam, $thang) {
                $q->whereNull('ngay_ket_thuc')->orWhere('ngay_ket_thuc', '>=', "$nam-$thang-01");
            })
            ->first();
        
        $luongCoBan = $hopDong->luong_co_ban ?? $nhanVien->chuc_vu->luong_co_ban ?? 0;
        
        // Tính ngày công
        $ngayTrongThang = Carbon::create($nam, $thang, 1)->daysInMonth;
        $chamCongs = ChamCong::where('nguoi_dung_id', $nhanVienId)
            ->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam)
            ->get();
        
        $soNgayCong = $chamCongs->where('trang_thai', 'dung_gio')->count();
        $soGioTangCa = $chamCongs->sum('gio_tang_ca');
        $congTangCa = $soGioTangCa / 8;
        $ngayNghiPhep = $chamCongs->where('trang_thai', 'nghi_phep')->count();
        $ngayNghiKhongPhep = $chamCongs->where('trang_thai', 'khong_phep')->count();
        
        // Tính lương theo ngày công
        $luongTheoNgay = $luongCoBan / $ngayTrongThang;
        $tongLuong = $luongTheoNgay * $soNgayCong;
        
        // Tính phụ cấp
        $phuCaps = PhuCapNhanVien::where('nguoi_dung_id', $nhanVienId)
            ->where('ngay_hieu_luc', '<=', "$nam-$thang-01")
            ->where(function($q) use ($nam, $thang) {
                $q->whereNull('ngay_ket_thuc')->orWhere('ngay_ket_thuc', '>=', "$nam-$thang-01");
            })
            ->get();
        
        $tongPhuCap = $phuCaps->sum('so_tien');
        
        // Tính khấu trừ (BHXH, BHYT, BHTN)
        $bhxh = $luongCoBan * 0.08;
        $bhyt = $luongCoBan * 0.015;
        $bhtn = $luongCoBan * 0.01;
        $tongKhauTru = $bhxh + $bhyt + $bhtn;
        
        // Tính thuế TNCN (tạm tính 5% phần vượt 11tr)
        $thueTNCN = 0;
        $thuNhapChiuThue = $tongLuong + $tongPhuCap - $tongKhauTru;
        if ($thuNhapChiuThue > 11000000) {
            $thueTNCN = ($thuNhapChiuThue - 11000000) * 0.05;
        }
        
        $tongKhauTru += $thueTNCN;
        $luongThucNhan = $tongLuong + $tongPhuCap - $tongKhauTru;
        
        // Lưu vào bảng luong_nhan_vien
        LuongNhanVien::create([
            'bang_luong_id' => $bangLuongId,
            'luong_thang' => $thang,
            'luong_nam' => $nam,
            'nguoi_dung_id' => $nhanVienId,
            'luong_co_ban' => $luongCoBan,
            'tong_phu_cap' => $tongPhuCap,
            'tong_khau_tru' => $tongKhauTru,
            'tong_luong' => $tongLuong,
            'luong_thuc_nhan' => $luongThucNhan,
            'so_ngay_cong' => $soNgayCong,
            'gio_tang_ca' => $soGioTangCa,
            'cong_tang_ca' => $congTangCa,
            'ngay_nghi_phep' => $ngayNghiPhep,
            'ngay_nghi_khong_phep' => $ngayNghiKhongPhep,
            'ngay_le' => 0,
            'thue_thu_nhap_ca_nhan' => $thueTNCN,
        ]);
    }
    
    // Xem chi tiết bảng lương
    public function show($id)
    {
        $bangLuong = BangLuong::with('luongNhanViens.nguoiDung.ho_so', 'nguoiXuLy', 'nguoiPheDuyet')
            ->findOrFail($id);
        
        return view('admin.bang-luong.show', compact('bangLuong'));
    }
    
    // Duyệt bảng lương
    public function duyet($id)
    {
        $bangLuong = BangLuong::findOrFail($id);
        $bangLuong->update([
            'trang_thai' => 'da_duyet',
            'nguoi_phe_duyet_id' => auth()->id(),
            'thoi_gian_phe_duyet' => now(),
        ]);
        
        return redirect()->route('admin.bang-luong.index')
            ->with('success', 'Đã duyệt bảng lương thành công!');
    }
    
    // Xóa bảng lương
    public function destroy($id)
    {
        $bangLuong = BangLuong::findOrFail($id);
        
        // Xóa các bảng lương nhân viên trước
        LuongNhanVien::where('bang_luong_id', $id)->delete();
        $bangLuong->delete();
        
        return redirect()->route('admin.bang-luong.index')
            ->with('success', 'Xóa bảng lương thành công!');
    }
}
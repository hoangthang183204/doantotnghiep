<?php
// app/Http/Controllers/TruongPhong/NhanVienController.php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DonXinVeSom;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);

        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }

        $phongBan = PhongBan::find($phongBanId);

        $query = NguoiDung::with(['hoSo', 'chucVu'])
            ->where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->where('id', '!=', $user->id);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhere('ma_nhan_vien', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                    });
            });
        }

        $nhanViens = $query->orderBy('id')->paginate(15);

        return view('truong-phong.nhan-vien.index', compact('nhanViens', 'phongBan'));
    }

    public function show($id, Request $request)
    {
        $userCurrent = Auth::user();
        $phongBanId = $this->getPhongBanId($userCurrent);

        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }

        // Lấy thông tin Nhân viên thuộc phòng ban
        $user = NguoiDung::with([
            'hoSo',
            'phong_ban',
            'chuc_vu',
            'vai_tro',
        ])
            ->where('phong_ban_id', $phongBanId)
            ->where('id', $id)
            ->firstOrFail();

        // LẤY HỒ SƠ CHÍNH
        $hoSoNguoiDung = $user->hoSo;
        $hoSo = $hoSoNguoiDung?->hoSo;

        // Load các quan hệ liên quan cho HoSo
        if ($hoSo) {
            $hoSo->load([
                'ky_nang',
                'chung_chi',
                'dao_tao',
                'nguoiPhuThuoc',
                'cv',
                'hop_dong',
                'khen_thuong_ky_luat',
                'du_an',
                'lich_su_luong',
            ]);
        }

        // Lấy hợp đồng hiệu lực
        $hopDongHieuLuc = $hoSo?->hop_dong
            ?->where('trang_thai_hop_dong', 'hieu_luc')
            ?->first();

        // Lấy bảng lương gần nhất
// Lấy bảng lương đã chốt gần nhất
$bangLuongGanNhat = \App\Models\BangLuong::from('bang_luong as bl')
    ->join('luong_nhan_vien as lnv', 'bl.id', '=', 'lnv.bang_luong_id')
    ->where('bl.trang_thai', 'da_chot')
    ->where('lnv.nguoi_dung_id', $user->id)
    ->orderBy('bl.nam', 'desc')
    ->orderBy('bl.thang', 'desc')
    ->select('bl.*')
    ->first();

$luongGanNhat = null;

if ($bangLuongGanNhat) {
    $luongGanNhat = \App\Models\LuongNhanVien::where('bang_luong_id', $bangLuongGanNhat->id)
        ->where('nguoi_dung_id', $user->id)
        ->first();
}

// Nếu chưa có bảng lương đã chốt thì lấy bản gần nhất
if (!$luongGanNhat) {
    $luongGanNhat = \App\Models\LuongNhanVien::where('nguoi_dung_id', $user->id)
        ->orderBy('luong_nam', 'desc')
        ->orderBy('luong_thang', 'desc')
        ->first();
}

// Lịch sử lương
$lichSuLuong = \App\Models\LuongNhanVien::where('nguoi_dung_id', $user->id)
    ->with('bangLuong')
    ->join('bang_luong', 'luong_nhan_vien.bang_luong_id', '=', 'bang_luong.id')
    ->where('bang_luong.trang_thai', 'da_chot')
    ->orderBy('bang_luong.nam', 'desc')
    ->orderBy('bang_luong.thang', 'desc')
    ->select('luong_nhan_vien.*')
    ->get();

// Kỳ lương
$kyLuu = $bangLuongGanNhat
    ? 'Tháng ' . $bangLuongGanNhat->thang . '/' . $bangLuongGanNhat->nam
    : 'Chưa có bảng lương';
        // Tính toán lương
        $luongCoBanHienTai = $hopDongHieuLuc?->luong_co_ban ?? 0;

        // Tính phụ cấp
        $tongPhuCap = 0;
        if ($hopDongHieuLuc) {
            if (!empty($hopDongHieuLuc->phu_cap)) {
                $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                    ? json_decode($hopDongHieuLuc->phu_cap, true)
                    : $hopDongHieuLuc->phu_cap;

                if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                    $tongPhuCap = \App\Models\PhuCap::whereIn('id', $phuCapIds)->sum('so_tien_mac_dinh');
                }
            }

            if ($tongPhuCap == 0) {
                $phuCapNhanVien = \App\Models\PhuCapNhanVien::where('nguoi_dung_id', $user->id)
                    ->where('trang_thai', 'hieu_luc')
                    ->where('ngay_hieu_luc', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('ngay_ket_thuc')->orWhere('ngay_ket_thuc', '>=', now());
                    })
                    ->sum('so_tien');
                $tongPhuCap = $phuCapNhanVien > 0 ? $phuCapNhanVien : 0;
            }
        }

        // Tăng ca
        $tienTangCa = $luongGanNhat?->tien_tang_ca ?? 0;
        $coTangCa = $tienTangCa > 0;

        // Tổng thu nhập
        $tongThuNhap = $luongGanNhat?->tong_luong
    ?? ($luongCoBanHienTai + $tongPhuCap + $tienTangCa);

        // Bảo hiểm (10.5%)
        $luongDongBhxh = $luongGanNhat?->luong_co_ban
        ?? $hopDongHieuLuc?->luong_co_ban
        ?? 0;
        $bhxh = $luongGanNhat?->bhxh ?? round($luongDongBhxh * 0.08,0);
        $bhyt = $luongGanNhat?->bhyt ?? round($luongDongBhxh * 0.015,0);
        $bhtn = $luongGanNhat?->bhtn ?? round($luongDongBhxh * 0.01,0);

        $tongBaoHiem = $luongGanNhat?->tong_bao_hiem
            ?? ($bhxh+$bhyt+$bhtn);

        // Giảm trừ gia cảnh
        $soNguoiPhuThuoc = $hoSo?->nguoiPhuThuoc?->count() ?? 0;
        $giamTruBanThan = 15500000;
        $giamTruGiaCanh = $giamTruBanThan + 6200000 * $soNguoiPhuThuoc;

        // Thuế TNCN
        $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruGiaCanh);

        $thueTncn = 0;
        $remaining = $thuNhapTinhThue;
        $bac = [
            ['tu' => 0, 'den' => 10000000, 'thue_suat' => 0.05],
            ['tu' => 10000000, 'den' => 30000000, 'thue_suat' => 0.1],
            ['tu' => 30000000, 'den' => 60000000, 'thue_suat' => 0.2],
            ['tu' => 60000000, 'den' => 100000000, 'thue_suat' => 0.3],
            ['tu' => 100000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
        ];
        foreach ($bac as $b) {
            if ($remaining <= 0) break;
            $khoang = min($remaining, $b['den'] - $b['tu']);
            $thueTncn += $khoang * $b['thue_suat'];
            $remaining -= $khoang;
        }
        $thueTncn = round($thueTncn, 0);

        $thucNhan = $luongGanNhat?->luong_thuc_nhan
        ?? ($tongThuNhap-$tongBaoHiem-$thueTncn);

        // Lấy chi tiết phụ cấp
        $phuCapChiTiets = collect();
        if ($hopDongHieuLuc && !empty($hopDongHieuLuc->phu_cap)) {
            $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                ? json_decode($hopDongHieuLuc->phu_cap, true)
                : $hopDongHieuLuc->phu_cap;
            if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                $phuCapChiTiets = \App\Models\PhuCap::whereIn('id', $phuCapIds)->get();
            }
        }


        // ⭐ LẤY LỊCH SỬ NGHỈ PHÉP (5 đơn/trang)
        $lichSuNghiPhep = \App\Models\DonXinNghi::where('nguoi_dung_id', $user->id)
            ->with(['loaiNghiPhep', 'nguoiDuyet.hoSo'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'nghi_phep_page')
            ->appends($request->query());

        // ⭐ LẤY LỊCH SỬ TĂNG CA (5 đơn/trang)
        $lichSuTangCa = \App\Models\DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->with(['nguoi_duyet.hoSo'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'tang_ca_page')
            ->appends($request->query());

        // ⭐ LẤY LỊCH SỬ ĐƠN XIN VỀ SỚM (5 đơn/trang) - BỔ SUNG ĐỘNG
        $lichSuVeSom = DonXinVeSom::where('nguoi_dung_id', $user->id)
            ->with(['nguoiDuyet.hoSo', 'chamCong']) // <-- Thêm 'chamCong' vào đây
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 've_som_page')
            ->appends($request->query());

        // ⭐ THỐNG KÊ ĐƠN TỪ
        $thongKeDonTu = [
            'tong_don_nghi' => \App\Models\DonXinNghi::where('nguoi_dung_id', $user->id)->count(),
            'don_nghi_cho_duyet' => \App\Models\DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_duyet')->count(),
            'don_nghi_da_duyet' => \App\Models\DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_duyet')->count(),
            'don_nghi_tu_choi' => \App\Models\DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),

            'tong_tang_ca' => \App\Models\DangKyTangCa::where('nguoi_dung_id', $user->id)->count(),
            'tong_ve_som' => DonXinVeSom::where('nguoi_dung_id', $user->id)->count(), // Đã sửa từ fix 0 thành động
        ];

        // ⭐ LẤY SỐ DƯ PHÉP
        $soDuPhep = \App\Models\SoDuPhep::where('nguoi_dung_id', $user->id)
            ->where('nam', date('Y'))
            ->first();

        return view('truong-phong.nhan-vien.show', compact(
            'user',
            'hoSo',
            'hoSoNguoiDung',
            'hopDongHieuLuc',
            'luongGanNhat',
            'luongCoBanHienTai',
            'tongPhuCap',
            'tienTangCa',
            'coTangCa',
            'tongThuNhap',
            'luongDongBhxh',
            'bhxh',
            'bhyt',
            'bhtn',
            'tongBaoHiem',
            'soNguoiPhuThuoc',
            'thuNhapChiuThue',
            'thueTncn',
            'thucNhan',
            'phuCapChiTiets',
            'lichSuNghiPhep',
            'lichSuTangCa',
            'lichSuVeSom', // <-- Đã bổ sung biến này vào compact
            'thongKeDonTu',
            'bangLuongGanNhat',
            'lichSuLuong',
            'kyLuu',
            'soDuPhep'
        ));
    }

    private function getPhongBanId($user)
    {
        if ($user->phong_ban_id) {
            return $user->phong_ban_id;
        }
        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        return $phongBan ? $phongBan->id : null;
    }
}

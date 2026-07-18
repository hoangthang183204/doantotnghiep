<?php
// app/Http/Controllers/Employee/DonNghiController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DonXinNghi;
use App\Models\LoaiNghiPhep;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonNghiController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    private function getSoDuNghiPhep($userId)
    {
        $namHienTai = Carbon::now()->year;

        // Lấy cấu hình phép từ bảng so_du_phep
        $soDuPhep = \App\Models\SoDuPhep::where('nguoi_dung_id', $userId)
            ->where('nam', $namHienTai)
            ->first();

        // Nếu dữ liệu năm nay chưa được khởi tạo, tự động tạo nhanh bản ghi mẫu để tránh crash giao diện
        if (!$soDuPhep) {
            $soDuPhep = \App\Models\SoDuPhep::create([
                'nguoi_dung_id' => $userId,
                'nam' => $namHienTai,
                'phep_nam_moi' => 12.0,
                'phep_cu_chuyen_sang' => 0.0,
                'phep_da_dung' => 0.0
            ]);
        }

        $tongPhepDuocHuong = $soDuPhep->phep_nam_moi + $soDuPhep->phep_cu_chuyen_sang;
        $soNgayDaNghi = $soDuPhep->phep_da_dung;
        $soDuConLai = max(0, $tongPhepDuocHuong - $soNgayDaNghi);

        // Bật trạng thái cảnh báo nếu số dư còn dưới hoặc bằng 3 ngày
        $canhBaoSapHet = $soDuConLai <= 3.0;

        return [
            'so_ngay_phep_nam' => $tongPhepDuocHuong,
            'phep_nam_moi' => $soDuPhep->phep_nam_moi,
            'phep_cu_chuyen_sang' => $soDuPhep->phep_cu_chuyen_sang,
            'so_ngay_da_nghi' => $soNgayDaNghi,
            'so_du_con_lai' => $soDuConLai,
            'canh_bao_sap_het' => $canhBaoSapHet,
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DonXinNghi::where('nguoi_dung_id', $user->id);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_bat_dau', '<=', $request->den_ngay);
        }

        $danhSachDon = $query->orderBy('created_at', 'desc')->paginate(10);

        $thongKe = [
            'tong' => DonXinNghi::where('nguoi_dung_id', $user->id)->count(),
            'cho_duyet' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),
            'huy_bo' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'huy_bo')->count(),
        ];

        $soDu = $this->getSoDuNghiPhep($user->id);

        return view('employee.don-nghi.index', compact('danhSachDon', 'thongKe', 'soDu'));
    }

    public function create()
    {
        $loaiNghiPheps = LoaiNghiPhep::where('trang_thai', 1)->get();
        $user = Auth::user();
        $soDu = $this->getSoDuNghiPhep($user->id);

        return view('employee.don-nghi.create', compact('loaiNghiPheps', 'soDu'));
    }

    // Thêm hàm này vào trong Class DonNghiController để tính số ngày loại trừ T7, CN
private function tinhSoNgayNghiThucTe($ngayBatDau, $ngayKetThuc)
{
    $start = Carbon::parse($ngayBatDau);
    $end = Carbon::parse($ngayKetThuc);
    $days = 0;

    while ($start->lte($end)) {
        // Nếu không phải Thứ 7 (6) và Chủ Nhật (0)
        if (!$start->isWeekend()) {
            $days++;
        }
        $start->addDay();
    }
    return $days;
}

public function store(Request $request)
{
    Log::info('🚀=== STORE FUNCTION CALLED ===🚀');

    // 1. Kiểm tra số ngày thực tế dựa trên khoảng ngày chọn (loại trừ Thứ 7, Chủ Nhật) trước
    $soNgayThucTe = $this->tinhSoNgayNghiThucTe($request->ngay_bat_dau, $request->ngay_ket_thuc);
    
    if ($soNgayThucTe == 0) {
        return back()->withInput()->withErrors(['ngay_bat_dau' => 'Khoảng thời gian chọn chỉ gồm ngày nghỉ tuần (Thứ 7, Chủ Nhật)!']);
    }

    // 2. Chạy Validation dữ liệu đầu vào cơ bản
    $request->validate([
        'loai_nghi_id' => 'required|exists:loai_nghi_phep,id',
        'ngay_bat_dau' => 'required|date|after_or_equal:today',
        'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
        'so_ngay_nghi' => 'required|numeric|min:0.5',
        'ly_do' => 'required|string|min:10',
        'ghi_chu' => 'nullable|string',
    ], [
        'so_ngay_nghi.min' => 'Số ngày nghỉ tối thiểu phải từ 0.5 ngày.',
        'ly_do.min' => 'Lý do xin nghỉ phải nhập tối thiểu 10 ký tự.',
    ]);

    $user = Auth::user();
    $loaiNghi = LoaiNghiPhep::find($request->loai_nghi_id);
    $soDu = $this->getSoDuNghiPhep($user->id);
    $tenLoaiCheck = mb_strtolower($loaiNghi->ten, 'UTF-8');
    $namHienTai = \Carbon\Carbon::now()->year;

    // --- CƠ CHẾ 1: KIỂM TRA GIỚI HẠN TỔNG NGÀY NGHỈ THEO NĂM ---

    if (str_contains($tenLoaiCheck, 'thai sản')) {
        // 1. Kiểm tra giới hạn của từng đơn lẻ
        if ($request->so_ngay_nghi > 5) {
            return back()->withInput()->withErrors(['so_ngay_nghi' => 'Đơn xin nghỉ thai sản ngắn hạn trên hệ thống tối đa là 5 ngày/đơn!']);
        }

        // 2. Tính tổng số ngày Nghỉ thai sản ĐÃ ĐƯỢC DUYỆT trong năm nay của nhân viên
        $daNghiThaiSan = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('loai_nghi_phep_id', $request->loai_nghi_id)
            ->where('trang_thai', 'da_duyet')
            ->whereYear('ngay_bat_dau', $namHienTai)
            ->sum('so_ngay_nghi');

        $gioiHanThaiSan = 5; // Hạn mức tối đa 5 ngày/năm cho loại này
        
        // Nếu tổng số ngày đã nghỉ cộng với số ngày đang xin vượt quá hạn mức năm thì chặn đứng
        if (($daNghiThaiSan + $request->so_ngay_nghi) > $gioiHanThaiSan) {
            return back()->withInput()->withErrors(['so_ngay_nghi' => 'Bạn đã nghỉ ' . $daNghiThaiSan . ' ngày thai sản trong năm nay. Đơn mới này khiến tổng ngày nghỉ vượt quá giới hạn cho phép (' . $gioiHanThaiSan . ' ngày/năm)!']);
        }

    } elseif (str_contains($tenLoaiCheck, 'không lương')) {
        // 1. Kiểm tra giới hạn của từng đơn lẻ
        if ($request->so_ngay_nghi > 5) {
            return back()->withInput()->withErrors(['so_ngay_nghi' => 'Đơn xin nghỉ không lương tối đa là 5 ngày/đơn!']);
        }

        // 2. Tính tổng số ngày Nghỉ không lương ĐÃ ĐƯỢC DUYỆT trong năm nay của nhân viên
        $daNghiKhongLuong = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('loai_nghi_phep_id', $request->loai_nghi_id)
            ->where('trang_thai', 'da_duyet')
            ->whereYear('ngay_bat_dau', $namHienTai)
            ->sum('so_ngay_nghi');

        $gioiHanKhongLuong = 10; // Giả định tối đa nhân viên được nghỉ việc riêng không lương 10 ngày/năm
        
        if (($daNghiKhongLuong + $request->so_ngay_nghi) > $gioiHanKhongLuong) {
            return back()->withInput()->withErrors(['so_ngay_nghi' => 'Bạn đã nghỉ ' . $daNghiKhongLuong . ' ngày không lương trong năm nay. Đơn mới này khiến tổng ngày nghỉ vượt quá giới hạn cho phép (' . $gioiHanKhongLuong . ' ngày/năm)!']);
        }

    } else {
        // Đối với các loại nghỉ còn lại (Phép năm, Nghỉ ốm vặt, Nghỉ lễ) -> Check trừ trực tiếp vào quỹ 12 ngày phép năm
        if ($request->so_ngay_nghi > $soDu['so_du_con_lai']) {
            return back()->withInput()->withErrors(['so_ngay_nghi' => 'Số ngày nghỉ vượt quá số dư nghỉ phép năm hiện tại (' . $soDu['so_du_con_lai'] . ' ngày)']);
        }

        // Kiểm tra bổ sung riêng cho ô Nghỉ ốm vặt ngắn ngày có lương
        if (str_contains($tenLoaiCheck, 'ốm') && $request->so_ngay_nghi > 3) {
            return back()->withInput()->withErrors(['so_ngay_nghi' => 'Nghỉ ốm thông thường (Có lương) tự động tính tối đa không quá 3 ngày/đơn!']);
        }
    }

    // --- KIỂM TRA ĐƠN TRÙNG LẶP ---
    $exists = DonXinNghi::where('nguoi_dung_id', $user->id)
        ->where('trang_thai', 'cho_duyet')
        ->where(function ($q) use ($request) {
            $q->whereBetween('ngay_bat_dau', [$request->ngay_bat_dau, $request->ngay_ket_thuc])
                ->orWhereBetween('ngay_ket_thuc', [$request->ngay_bat_dau, $request->ngay_ket_thuc]);
        })
        ->exists();

    if ($exists) {
        return back()->withInput()->withErrors(['ngay_bat_dau' => 'Bạn đã có đơn nghỉ trùng khoảng thời gian này đang chờ duyệt!']);
    }

    // --- TIẾN HÀNH LƯU DỮ LIỆU VÀO DATABASE ---
    DB::beginTransaction();
    try {
        $maDonNghi = $this->sinhMaDonNghi();

        $donNghi = DonXinNghi::create([
            'ma_don_nghi' => $maDonNghi,
            'nguoi_dung_id' => $user->id,
            'loai_nghi_phep_id' => $request->loai_nghi_id,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'so_ngay_nghi' => $request->so_ngay_nghi,
            'ly_do' => $request->ly_do,
            'ghi_chu' => $request->ghi_chu,
            'trang_thai' => 'cho_duyet',
            'cap_duyet_hien_tai' => 1,
        ]);

        DB::commit();

        return redirect()->route('employee.don-nghi.index')
            ->with('success', '🎉 Đã gửi đơn xin nghỉ phép thành công! Vui lòng đợi quản lý phê duyệt.');
            
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('❌ Lỗi tạo đơn nghỉ phép: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
    }
}

    // (Giữ nguyên phần DB::beginTransaction() tạo đơn nghỉ ở phía dưới...)

    public function show($id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::with(['loaiNghiPhep', 'nguoiDung.hoSo'])
            ->where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        return view('employee.don-nghi.show', compact('donNghi'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $loaiNghiPheps = LoaiNghiPhep::where('trang_thai', 1)->get();
        $soDu = $this->getSoDuNghiPhep($user->id);

        return view('employee.don-nghi.edit', compact('donNghi', 'loaiNghiPheps', 'soDu'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $request->validate([
            'loai_nghi_id' => 'required|exists:loai_nghi_phep,id',
            'ngay_bat_dau' => 'required|date|after_or_equal:today',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_ngay_nghi' => 'required|numeric|min:0.5',
            'ly_do' => 'required|string|min:10',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $donNghi->update([
                'loai_nghi_phep_id' => $request->loai_nghi_id,
                'ngay_bat_dau' => $request->ngay_bat_dau,
                'ngay_ket_thuc' => $request->ngay_ket_thuc,
                'so_ngay_nghi' => $request->so_ngay_nghi,
                'ly_do' => $request->ly_do,
                'ghi_chu' => $request->ghi_chu,
            ]);

            DB::commit();

            return redirect()->route('employee.don-nghi.index')
                ->with('success', '✅ Đã cập nhật đơn xin nghỉ phép!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Don nghi update error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function huy($id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $donNghi->update(['trang_thai' => 'huy_bo']);

        return redirect()->route('employee.don-nghi.index')
            ->with('success', '🚫 Đã hủy đơn xin nghỉ phép!');
    }
    private function sinhMaDonNghi()
{
    $namHienTai = \Carbon\Carbon::now()->year;
    // Tìm đơn nghỉ mới nhất trong năm nay
    $donMoiNhat = \App\Models\DonXinNghi::whereYear('created_at', $namHienTai)
        ->orderBy('id', 'desc')
        ->first();

    if ($donMoiNhat) {
        // Trích xuất số thứ tự cũ (bỏ cụm "DN2026") và tăng lên 1
        $soThuTuCu = (int) substr($donMoiNhat->ma_don_nghi, 6);
        $soThuTuMoi = str_pad($soThuTuCu + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $soThuTuMoi = '001';
    }

    return 'DN' . $namHienTai . $soThuTuMoi;
}
}
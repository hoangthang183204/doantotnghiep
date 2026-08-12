<?php
// app/Http/Controllers/Employee/TangCaController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\ThucHienTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Services\NotificationService;
use Carbon\Carbon; // ⭐ THÊM DÒNG NÀY
use App\Helpers\OvertimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TangCaController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Lấy trưởng phòng của nhân viên
     */
    private function getTruongPhong($user)
    {
        if ($user->phong_ban_id) {
            $phongBan = PhongBan::find($user->phong_ban_id);
            if ($phongBan && $phongBan->truong_phong_id) {
                return NguoiDung::find($phongBan->truong_phong_id);
            }
        }

        $phongBan = PhongBan::where('truong_phong_id', '!=', null)->first();
        if ($phongBan) {
            return NguoiDung::find($phongBan->truong_phong_id);
        }

        $truongPhong = NguoiDung::whereHas('vaiTros', function ($q) {
            $q->whereIn('name', ['truong_phong', 'quan_ly', 'manager']);
        })->first();

        return $truongPhong;
    }

    /**
     * Hiển thị danh sách tất cả (kiến nghị + đơn tăng ca)
     */
    public function index()
    {
        $user = Auth::user();

        $tangCas = DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $thongKe = [
            'tong' => DangKyTangCa::where('nguoi_dung_id', $user->id)->count(),
            'cho_duyet' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),
            'huy' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'huy')->count(),
        ];

        return view('employee.tang-ca.index', [
            'donTangCa' => $tangCas,
            'thongKe' => $thongKe,
        ]);
    }

    /**
     * Hiển thị form gửi kiến nghị tăng ca
     */
    public function create()
    {
        return view('employee.tang-ca.create');
    }

    /**
     * Lưu kiến nghị tăng ca mới
     */
    public function store(Request $request)
    {
        Log::info('🚀=== KIEN NGHI TANG CA STORE CALLED ===🚀');

        $request->validate([
            'ly_do_tang_ca' => 'required|string|min:10|max:500',
        ]);

        $user = Auth::user();

        $truongPhong = $this->getTruongPhong($user);

        if (!$truongPhong) {
            return back()
                ->withInput()
                ->with('error', '❌ Không tìm thấy trưởng phòng để gửi kiến nghị!');
        }

        DB::beginTransaction();
        try {
            $tangCa = DangKyTangCa::create([
                'nguoi_dung_id' => $user->id,
                'nguoi_tao_id' => $user->id,
                'loai_tao' => 'nhan_vien',
                'ngay_tang_ca' => null,
                'gio_bat_dau' => null,
                'gio_ket_thuc' => null,
                'so_gio_tang_ca' => 0,
                'loai_tang_ca' => null,
                'ly_do_tang_ca' => $request->ly_do_tang_ca,
                'trang_thai' => 'cho_duyet',
            ]);

            Log::info('✅ Kien nghi tang ca created: ID ' . $tangCa->id);

            try {
                $this->notificationService->notifyKienNghiTangCa($tangCa, $truongPhong);
                Log::info('📧 Đã gửi thông báo kiến nghị đến trưởng phòng: ' . $truongPhong->email);
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã gửi kiến nghị tăng ca đến trưởng phòng!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Kien nghi tang ca error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi gửi kiến nghị: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết kiến nghị/đơn tăng ca
     */
    public function show($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::with(['nguoi_duyet.hoSo'])->findOrFail($id);

        if ($donTangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền xem yêu cầu này');
        }

        return view('employee.tang-ca.show', [
            'donTangCa' => $donTangCa,
        ]);
    }

    /**
     * Hiển thị form chỉnh sửa kiến nghị
     */
    public function edit($id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        if ($tangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền chỉnh sửa kiến nghị này');
        }

        if ($tangCa->trang_thai !== 'cho_duyet') {
            abort(403, 'Chỉ có thể chỉnh sửa kiến nghị đang chờ xử lý');
        }

        return view('employee.tang-ca.edit', [
            'tangCa' => $tangCa,
        ]);
    }

    /**
     * Cập nhật kiến nghị tăng ca
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        if ($tangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền chỉnh sửa kiến nghị này');
        }

        if ($tangCa->trang_thai !== 'cho_duyet') {
            return back()->with('error', 'Chỉ có thể chỉnh sửa kiến nghị đang chờ xử lý');
        }

        $request->validate([
            'ly_do_tang_ca' => 'required|string|min:10|max:500',
        ]);

        DB::beginTransaction();
        try {
            $tangCa->update([
                'ly_do_tang_ca' => $request->ly_do_tang_ca,
            ]);

            Log::info('✅ Kien nghi tang ca updated: ID ' . $tangCa->id);

            DB::commit();

            return redirect()->route('employee.tang-ca.show', $tangCa->id)
                ->with('success', '✅ Cập nhật kiến nghị thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Kien nghi tang ca update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage());
        }
    }

    /**
     * Hủy kiến nghị tăng ca
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        if ($tangCa->nguoi_dung_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền hủy kiến nghị này'], 403);
        }

        if ($tangCa->trang_thai !== 'cho_duyet') {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể hủy kiến nghị đang chờ xử lý'], 403);
        }

        DB::beginTransaction();
        try {
            $tangCa->update(['trang_thai' => 'huy']);
            Log::info('✅ Kien nghi tang ca cancelled: ID ' . $tangCa->id);

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã hủy kiến nghị tăng ca!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Kien nghi tang ca destroy error: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra khi hủy kiến nghị: ' . $e->getMessage());
        }
    }

    /**
     * Alias cho destroy() - hủy kiến nghị tăng ca
     */
    public function huy($id)
    {
        return $this->destroy($id);
    }

    /**
     * ⭐ NHÂN VIÊN XÁC NHẬN ĐÃ LÀM TĂNG CA
     */
    public function confirmThucHien($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::with('nguoi_dung')->findOrFail($id);

        if ($donTangCa->nguoi_dung_id !== $user->id) {
            return back()->with('error', 'Không có quyền xác nhận đơn này');
        }

        if ($donTangCa->trang_thai !== 'da_duyet') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn đã duyệt');
        }

        if ($donTangCa->thuc_hien) {
            return back()->with('error', 'Đơn này đã được xác nhận trước đó');
        }

        // ⭐ KIỂM TRA THỜI GIAN CHI TIẾT
        $now = Carbon::now();
        $ngayTangCa = Carbon::parse($donTangCa->ngay_tang_ca);
        $gioBatDau = Carbon::parse($donTangCa->gio_bat_dau);

        $thoiGianBatDau = Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'));

        $thoiGianChoPhepSom = $thoiGianBatDau->copy()->subMinutes(30);
        $thoiGianKetThuc = Carbon::parse($donTangCa->gio_ket_thuc);
        $thoiGianChoPhepMuon = Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $thoiGianKetThuc->format('H:i:s'))->addHours(2);

        // ⭐ KIỂM TRA
        if ($now->lt($thoiGianChoPhepSom)) {
            $thoiGianConLai = $now->diffInMinutes($thoiGianChoPhepSom);
            $gioConLai = floor($thoiGianConLai / 60);
            $phutConLai = $thoiGianConLai % 60;

            $thongBao = "⏳ Chưa đến giờ tăng ca! Còn {$gioConLai} giờ {$phutConLai} phút nữa mới được xác nhận.";
            return back()->with('error', $thongBao);
        }

        if ($now->gt($thoiGianChoPhepMuon)) {
            return back()->with('error', '⛔ Đã quá thời gian cho phép xác nhận tăng ca!');
        }

        DB::beginTransaction();
        try {
            $thucHien = ThucHienTangCa::create([
                'dang_ky_tang_ca_id' => $donTangCa->id,
                'gio_bat_dau_thuc_te' => $donTangCa->gio_bat_dau,
                'gio_ket_thuc_thuc_te' => $donTangCa->gio_ket_thuc,
                'so_gio_tang_ca_thuc_te' => $donTangCa->so_gio_tang_ca,
                'so_cong_tang_ca' => 1,
                'trang_thai' => 'nhan_vien_xac_nhan',
            ]);

            Log::info('✅ Employee confirmed overtime: DangKyTangCa ID ' . $donTangCa->id);

            try {
                $this->notificationService->notifyOvertime($donTangCa, 'employee_confirmed');
                Log::info('📧 Đã gửi thông báo xác nhận làm tăng ca đến Admin/Quản lý');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('employee.tang-ca.show', $donTangCa->id)
                ->with('success', '✅ Xác nhận đã làm tăng ca! Chờ quản lý xác nhận hoàn thành.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Confirm thuc hien error: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra khi xác nhận: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ NHÂN VIÊN TỪ CHỐI ĐƠN TĂNG CA DO TRƯỞNG PHÒNG TẠO
     */
    public function tuChoiDon(Request $request, $id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        // Kiểm tra quyền
        if ($tangCa->nguoi_dung_id !== $user->id) {
            return back()->with('error', 'Không có quyền từ chối đơn này');
        }

        // Chỉ cho phép từ chối đơn do trưởng phòng tạo và đã duyệt
        if ($tangCa->loai_tao !== 'truong_phong') {
            return back()->with('error', 'Chỉ có thể từ chối đơn tăng ca do trưởng phòng tạo');
        }

        if ($tangCa->trang_thai !== 'da_duyet') {
            return back()->with('error', 'Chỉ có thể từ chối đơn tăng ca đã được duyệt');
        }

        // Kiểm tra đã thực hiện tăng ca chưa
        if ($tangCa->thuc_hien) {
            return back()->with('error', 'Đơn tăng ca đã được thực hiện, không thể từ chối');
        }

        // Validate lý do từ chối
        $request->validate([
            'ly_do_tu_choi' => 'required|string|min:10|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật trạng thái thành 'tu_choi' và lưu lý do
            $tangCa->update([
                'trang_thai' => 'tu_choi',
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            Log::info('✅ Nhan vien tu choi don tang ca: ID ' . $tangCa->id . ' - Ly do: ' . $request->ly_do_tu_choi);

            // Gửi thông báo cho trưởng phòng
            try {
                $this->notificationService->notifyOvertime($tangCa, 'employee_rejected');
                Log::info('📧 Đã gửi thông báo từ chối đơn tăng ca đến trưởng phòng');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã từ chối đơn tăng ca! Lý do đã được gửi đến trưởng phòng.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Tu choi don tang ca error: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra khi từ chối đơn: ' . $e->getMessage());
        }
    }
}

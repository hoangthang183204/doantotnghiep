<?php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\NguoiDung;
use App\Models\XinVeSomTangCa;
use App\Models\PhongBan;
use App\Models\ThucHienTangCa;
use App\Helpers\OvertimeHelper;
use App\Services\NotificationService;
use Carbon\Carbon;
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
     * Lấy danh sách nhân viên trong phòng ban của trưởng phòng
     */
    private function getNhanVienTrongPhong()
    {
        $user = Auth::user();

        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();

        if (!$phongBan) {
            if ($user->phong_ban_id) {
                $phongBan = PhongBan::find($user->phong_ban_id);
            }
        }

        if (!$phongBan) {
            return collect([]);
        }

        return NguoiDung::where('phong_ban_id', $phongBan->id)
            ->where('trang_thai', 1)
            ->where('id', '!=', $user->id)
            ->with('hoSo')
            ->get();
    }

    /**
     * Lấy ID nhân viên trong phòng ban
     */
    private function getNhanVienIdsTrongPhong()
    {
        return $this->getNhanVienTrongPhong()->pluck('id')->toArray();
    }

    /**
     * 📋 Danh sách đơn tăng ca và kiến nghị trong phòng ban
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $query = DangKyTangCa::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'nguoi_duyet.hoSo',
            'thuc_hien'
        ])->whereIn('nguoi_dung_id', $nhanVienIds);

        if ($request->filled('ten_nhan_vien')) {
            $keyword = trim($request->ten_nhan_vien);
            $query->whereHas('nguoi_dung', function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                    });
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
        }

        $donTangCa = $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->appends($request->query());

        $thongKeQuery = clone $query;
        $thongKe = [
            'tong' => (clone $thongKeQuery)->count(),
            'cho_duyet' => (clone $thongKeQuery)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => (clone $thongKeQuery)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => (clone $thongKeQuery)->where('trang_thai', 'tu_choi')->count(),
            'huy' => (clone $thongKeQuery)->where('trang_thai', 'huy')->count(),
        ];

        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        if (!$phongBan) {
            $phongBan = PhongBan::find($user->phong_ban_id);
        }

        return view('truong-phong.tang-ca.index', compact('donTangCa', 'thongKe', 'phongBan'));
    }

    /**
     * 📝 Form tạo đơn tăng ca cho nhân viên
     */
    public function create(Request $request)
    {
        $nhanViens = $this->getNhanVienTrongPhong();

        $phongBan = PhongBan::where('truong_phong_id', Auth::id())->first();
        if (!$phongBan) {
            $phongBan = PhongBan::find(Auth::user()->phong_ban_id);
        }

        $kienNghi = null;
        $kienNghiId = $request->query('kien_nghi_id');
        if ($kienNghiId) {
            $kienNghi = DangKyTangCa::where('id', $kienNghiId)
                ->whereNull('ngay_tang_ca')
                ->where('trang_thai', 'da_duyet')
                ->with(['nguoi_dung.hoSo', 'nguoi_dung.chucVu'])
                ->first();
        }

        return view('truong-phong.tang-ca.create', [
            'nhanViens' => $nhanViens,
            'phongBan' => $phongBan,
            'loaiLabels' => DangKyTangCa::$loaiLabels,
            'kienNghi' => $kienNghi,
        ]);
    }

    /**
     * 💾 Lưu đơn tăng ca cho nhân viên
     */
    public function store(Request $request)
    {
        Log::info('🚀=== TRUONG PHONG TANG CA STORE CALLED ===🚀');
        Log::info('📝 Request data: ', $request->all());

        $request->validate([
            'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
            'ngay_tang_ca' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'loai_tang_ca' => 'required|in:ngay_thuong,ngay_nghi,le_tet',
            'ly_do_tang_ca' => 'required|string|min:10|max:500',
        ]);

        $user = Auth::user();
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        if (!in_array($request->nguoi_dung_id, $nhanVienIds)) {
            return back()
                ->withInput()
                ->withErrors(['nguoi_dung_id' => 'Nhân viên không thuộc phòng ban của bạn']);
        }

        // ⭐⭐ KIỂM TRA NGÀY CUỐI TUẦN ⭐⭐
        $ngayTangCa = Carbon::parse($request->ngay_tang_ca);
        $isWeekend = $ngayTangCa->isWeekend(); // Thứ 7 hoặc Chủ Nhật

        // Nếu là cuối tuần nhưng loại_tang_ca là 'ngay_thuong' thì báo lỗi
        if ($isWeekend && $request->loai_tang_ca == 'ngay_thuong') {
            return back()
                ->withInput()
                ->withErrors(['loai_tang_ca' => '⚠️ Ngày ' . $ngayTangCa->format('d/m/Y') . ' là ngày cuối tuần, vui lòng chọn loại "Ngày cuối tuần (200%)"']);
        }

        // Nếu không phải cuối tuần nhưng loại_tang_ca là 'ngay_nghi' thì báo lỗi
        if (!$isWeekend && $request->loai_tang_ca == 'ngay_nghi') {
            return back()
                ->withInput()
                ->withErrors(['loai_tang_ca' => '⚠️ Ngày ' . $ngayTangCa->format('d/m/Y') . ' không phải ngày cuối tuần, vui lòng chọn loại "Ngày thường (150%)"']);
        }

        // ⭐ KIỂM TRA GIỜ TĂNG CA HỢP LỆ
        // Nếu là ngày cuối tuần, bỏ qua kiểm tra giờ hành chính
        $kiemTraGioHopLe = OvertimeHelper::kiemTraGioTangCaHopLe(
            $request->gio_bat_dau,
            $request->gio_ket_thuc,
            $isWeekend
        );

        if (!$kiemTraGioHopLe['valid']) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $kiemTraGioHopLe['message']]);
        }

        // ⭐ KIỂM TRA KHÔNG ĐƯỢC TẠO ĐƠN CHO THỜI GIAN ĐÃ QUA
        $thoiGianBatDau = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . Carbon::parse($request->gio_bat_dau)->format('H:i:s')
        );
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        if ($thoiGianBatDau->lt($now)) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => '⛔ Không thể tạo đơn tăng ca cho thời gian đã qua! Vui lòng chọn giờ bắt đầu trong tương lai.']);
        }

        $gioBatDau = Carbon::parse($request->gio_bat_dau);
        $gioKetThuc = Carbon::parse($request->gio_ket_thuc);
        $soGioTangCa = $gioBatDau->diffInHours($gioKetThuc);

        $kiemTraGioiHan = OvertimeHelper::kiemTraGioiHan(
            $request->nguoi_dung_id,
            $request->ngay_tang_ca,
            $soGioTangCa
        );

        if (!$kiemTraGioiHan['valid']) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $kiemTraGioiHan['message']]);
        }

        $validation = $this->validateOvertime(
            $request->nguoi_dung_id,
            $request->ngay_tang_ca,
            $request->gio_bat_dau,
            $request->gio_ket_thuc
        );

        if (!$validation['valid']) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $validation['message']]);
        }

        DB::beginTransaction();
        try {
            $kienNghiId = $request->input('kien_nghi_id');
            $tangCa = null;

            if ($kienNghiId) {
                $kienNghi = DangKyTangCa::where('id', $kienNghiId)
                    ->whereNull('ngay_tang_ca')
                    ->first();

                if ($kienNghi) {
                    Log::info('✅ Found kien nghi: ID ' . $kienNghi->id);

                    $kienNghi->update([
                        'ngay_tang_ca' => $request->ngay_tang_ca,
                        'gio_bat_dau' => $request->gio_bat_dau,
                        'gio_ket_thuc' => $request->gio_ket_thuc,
                        'so_gio_tang_ca' => $soGioTangCa,
                        'loai_tang_ca' => $request->loai_tang_ca,
                        'ly_do_tang_ca' => $request->ly_do_tang_ca,
                        'nguoi_duyet_id' => $user->id,
                        'thoi_gian_duyet' => now(),
                        'trang_thai' => 'da_duyet',
                    ]);

                    $tangCa = $kienNghi;
                    Log::info('✅ Updated kien nghi to don tang ca: ID ' . $tangCa->id);
                } else {
                    Log::error('❌ Khong tim thay kien nghi voi ID: ' . $kienNghiId);
                    $tangCa = DangKyTangCa::create([
                        'nguoi_dung_id' => $request->nguoi_dung_id,
                        'nguoi_tao_id' => $user->id,
                        'loai_tao' => 'truong_phong',
                        'ngay_tang_ca' => $request->ngay_tang_ca,
                        'gio_bat_dau' => $request->gio_bat_dau,
                        'gio_ket_thuc' => $request->gio_ket_thuc,
                        'so_gio_tang_ca' => $soGioTangCa,
                        'loai_tang_ca' => $request->loai_tang_ca,
                        'ly_do_tang_ca' => $request->ly_do_tang_ca,
                        'trang_thai' => 'da_duyet',
                        'nguoi_duyet_id' => $user->id,
                        'thoi_gian_duyet' => now(),
                    ]);
                    Log::info('✅ Created new don tang ca: ID ' . $tangCa->id);
                }
            } else {
                $tangCa = DangKyTangCa::create([
                    'nguoi_dung_id' => $request->nguoi_dung_id,
                    'nguoi_tao_id' => $user->id,
                    'loai_tao' => 'truong_phong',
                    'ngay_tang_ca' => $request->ngay_tang_ca,
                    'gio_bat_dau' => $request->gio_bat_dau,
                    'gio_ket_thuc' => $request->gio_ket_thuc,
                    'so_gio_tang_ca' => $soGioTangCa,
                    'loai_tang_ca' => $request->loai_tang_ca,
                    'ly_do_tang_ca' => $request->ly_do_tang_ca,
                    'trang_thai' => 'da_duyet',
                    'nguoi_duyet_id' => $user->id,
                    'thoi_gian_duyet' => now(),
                ]);
                Log::info('✅ Created new don tang ca: ID ' . $tangCa->id);
            }

            try {
                $this->notificationService->notifyOvertime($tangCa, 'created_by_manager');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('truong-phong.tang-ca.index')
                ->with('success', '✅ Đã tạo đơn tăng ca cho nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Tang ca error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo đơn: ' . $e->getMessage());
        }
    }

    /**
     * 👁️ Chi tiết đơn tăng ca
     */
    public function show($id)
    {
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $tangCa = DangKyTangCa::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'nguoi_duyet.hoSo',
            'thuc_hien',
        ])->whereIn('nguoi_dung_id', $nhanVienIds)
            ->findOrFail($id);

        return view('truong-phong.tang-ca.show', compact('tangCa'));
    }

    /**
     * ✅ Phê duyệt đơn tăng ca
     */
    public function duyet(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $dangKy = DangKyTangCa::where('trang_thai', 'cho_duyet')
                ->whereNotNull('ngay_tang_ca')
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->findOrFail($id);

            $dangKy->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'approved');

            return redirect()
                ->route('truong-phong.tang-ca.index')
                ->with('success', '✅ Đã duyệt đơn tăng ca thành công!');
        } catch (\Exception $e) {
            Log::error('❌ Duyet tang ca error: ' . $e->getMessage());
            return back()->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ❌ Từ chối đơn tăng ca
     */
    public function tuChoi(Request $request, $id)
    {
        try {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|max:500',
            ]);

            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $dangKy = DangKyTangCa::where('trang_thai', 'cho_duyet')
                ->whereNotNull('ngay_tang_ca')
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->findOrFail($id);

            $dangKy->update([
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'rejected');

            return response()->json([
                'success' => true,
                'message' => '✅ Đã từ chối đơn tăng ca!'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Tu choi tang ca error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ⭐ DUYỆT KIẾN NGHỊ TĂNG CA
     */
    public function duyetKienNghi(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $kienNghi = DangKyTangCa::where('trang_thai', 'cho_duyet')
                ->whereNull('ngay_tang_ca')
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->with('nguoi_dung')
                ->findOrFail($id);

            $kienNghi->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            try {
                $this->notificationService->notifyOvertime($kienNghi, 'kien_nghi_approved');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            Log::info('✅ Truong phong duyet kien nghi: ID ' . $kienNghi->id);

            // ⭐ REDIRECT VỀ TRANG DANH SÁCH KÈM THEO THÔNG BÁO
            return redirect()
                ->route('truong-phong.tang-ca.index')
                ->with('success', '✅ Đã duyệt kiến nghị tăng ca! Vui lòng tạo đơn tăng ca.');
        } catch (\Exception $e) {
            Log::error('❌ Duyet kien nghi error: ' . $e->getMessage());
            return back()->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ TỪ CHỐI KIẾN NGHỊ TĂNG CA
     */
    public function tuChoiKienNghi(Request $request, $id)
    {
        try {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|min:10|max:500',
            ]);

            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $kienNghi = DangKyTangCa::where('trang_thai', 'cho_duyet')
                ->whereNull('ngay_tang_ca')
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->with('nguoi_dung')
                ->findOrFail($id);

            $kienNghi->update([
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            try {
                $this->notificationService->notifyOvertime($kienNghi, 'kien_nghi_rejected');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            Log::info('✅ Truong phong tu choi kien nghi: ID ' . $kienNghi->id);

            // ⭐ REDIRECT VỀ TRANG DANH SÁCH
            return redirect()
                ->route('truong-phong.tang-ca.index')
                ->with('success', '✅ Đã từ chối kiến nghị tăng ca!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->with('error', '⚠️ Vui lòng nhập lý do từ chối (tối thiểu 10 ký tự)');
        } catch (\Exception $e) {
            Log::error('❌ Tu choi kien nghi error: ' . $e->getMessage());
            return back()->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kiểm tra đơn tăng ca trùng lặp
     */
    private function validateOvertime($userId, $ngayTangCa, $gioBatDau, $gioKetThuc, $excludeId = null)
    {
        $trangThaiKiemTra = ['cho_duyet', 'da_duyet'];

        $query = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->where('ngay_tang_ca', $ngayTangCa)
            ->whereIn('trang_thai', $trangThaiKiemTra);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingRecords = $query->get();

        foreach ($existingRecords as $record) {
            $existingStart = Carbon::parse($record->gio_bat_dau);
            $existingEnd = Carbon::parse($record->gio_ket_thuc);
            $newStart = Carbon::parse($gioBatDau);
            $newEnd = Carbon::parse($gioKetThuc);

            if ($newStart < $existingEnd && $newEnd > $existingStart) {
                $trangThaiLabel = DangKyTangCa::$trangThaiLabels[$record->trang_thai] ?? $record->trang_thai;
                return [
                    'valid' => false,
                    'don_trung' => $record,
                    'message' => sprintf(
                        '❌ Đơn tăng ca bị trùng với ca làm việc từ %s đến %s (Trạng thái: %s)',
                        $record->gio_bat_dau,
                        $record->gio_ket_thuc,
                        $trangThaiLabel
                    )
                ];
            }
        }

        return ['valid' => true, 'message' => 'Đơn hợp lệ'];
    }

    /**
     * ✅ TRƯỞNG PHÒNG XÁC NHẬN HOÀN THÀNH TĂNG CA (TÍNH LƯƠNG CHÍNH THỨC)
     */
    public function approveThucHien(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $tangCa = DangKyTangCa::with(['nguoi_dung', 'thuc_hien'])
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->findOrFail($id);

            $thucHien = ThucHienTangCa::where('dang_ky_tang_ca_id', $tangCa->id)->firstOrFail();

            // Chỉ cho phép xác nhận khi nhân viên đã xác nhận
            if ($thucHien->trang_thai !== 'nhan_vien_xac_nhan') {
                return back()->with('error', 'Nhân viên chưa xác nhận đã làm tăng ca');
            }

            // Lấy số giờ thực tế
            $soGioThucTe = $thucHien->so_gio_tang_ca_thuc_te ?? $tangCa->so_gio_tang_ca;

            // Đảm bảo số giờ thực tế không vượt quá số giờ đăng ký
            $soGioThucTe = min($soGioThucTe, $tangCa->so_gio_tang_ca);

            DB::beginTransaction();

            // Cập nhật thực hiện tăng ca
            $thucHien->update([
                'trang_thai' => 'quan_ly_xac_nhan',
            ]);

            // ⭐ TÍNH LƯƠNG TĂNG CA CHÍNH THỨC
            $userId = $tangCa->nguoi_dung_id;
            $type = $tangCa->loai_tang_ca;
            $luongTangCa = OvertimeHelper::tinhLuongTangCa($userId, $soGioThucTe, $type);

            // Cập nhật lương vào đơn tăng ca
            $tangCa->luong_tang_ca = $luongTangCa;
            $tangCa->da_hoan_thanh = true;
            $tangCa->thoi_gian_hoan_thanh = now();
            $tangCa->save();

            Log::info('✅ Truong phong approved overtime: ID ' . $tangCa->id .
                ' - So gio thuc te: ' . $soGioThucTe .
                ' - Luong chinh thuc: ' . $luongTangCa);

            // Gửi thông báo cho nhân viên
            try {
                $this->notificationService->notifyOvertime($tangCa, 'manager_approved');
                Log::info('📧 Đã gửi thông báo xác nhận hoàn thành đến nhân viên');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('truong-phong.tang-ca.show', $tangCa->id)
                ->with('success', '✅ Xác nhận hoàn thành tăng ca thành công. Lương: ' . number_format($luongTangCa) . 'đ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ approveThucHien error: ' . $e->getMessage());
            return redirect()
                ->route('truong-phong.tang-ca.show', $id)
                ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Duyệt đơn xin về sớm
     */
    public function duyetXinVeSom(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $xinVeSom = XinVeSomTangCa::with(['dang_ky_tang_ca', 'nguoi_dung'])
                ->where('trang_thai', 'cho_duyet')
                ->whereHas('dang_ky_tang_ca', function ($q) use ($nhanVienIds) {
                    $q->whereIn('nguoi_dung_id', $nhanVienIds);
                })
                ->findOrFail($id);

            $xinVeSom->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            // Gửi thông báo cho nhân viên
            try {
                // $this->notificationService->notifyXinVeSomTangCa($xinVeSom, $xinVeSom->nguoi_dung, 'approved');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            return redirect()
                ->route('truong-phong.tang-ca.index')
                ->with('success', '✅ Đã duyệt đơn xin về sớm! Nhân viên có thể về sớm lúc ' . $xinVeSom->gio_ve_som_du_kien);
        } catch (\Exception $e) {
            Log::error('❌ Duyet xin ve som error: ' . $e->getMessage());
            return back()->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ❌ Từ chối đơn xin về sớm
     */
    public function tuChoiXinVeSom(Request $request, $id)
    {
        try {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|max:500',
            ]);

            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $xinVeSom = XinVeSomTangCa::with(['dang_ky_tang_ca', 'nguoi_dung'])
                ->where('trang_thai', 'cho_duyet')
                ->whereHas('dang_ky_tang_ca', function ($q) use ($nhanVienIds) {
                    $q->whereIn('nguoi_dung_id', $nhanVienIds);
                })
                ->findOrFail($id);

            $xinVeSom->update([
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            // Gửi thông báo cho nhân viên
            try {
                // $this->notificationService->notifyXinVeSomTangCa($xinVeSom, $xinVeSom->nguoi_dung, 'rejected');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            return redirect()
                ->route('truong-phong.tang-ca.index')
                ->with('success', '✅ Đã từ chối đơn xin về sớm!');
        } catch (\Exception $e) {
            Log::error('❌ Tu choi xin ve som error: ' . $e->getMessage());
            return back()->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ TRƯỞNG PHÒNG XÁC NHẬN SỬA CHỮA CÔNG
     */
  public function approveSuaChuaCong(Request $request, $id)
{
    try {
        $user = Auth::user();
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $tangCa = DangKyTangCa::with(['nguoi_dung', 'thuc_hien'])
            ->whereIn('nguoi_dung_id', $nhanVienIds)
            ->findOrFail($id);

        $thucHien = ThucHienTangCa::where('dang_ky_tang_ca_id', $tangCa->id)
            ->where('trang_thai', 'cho_xac_nhan_sua_chua')
            ->first();

        if (!$thucHien) {
            return back()->with('error', 'Không tìm thấy yêu cầu sửa chữa công');
        }

        $request->validate([
            'so_gio_thuc_te' => 'required|numeric|min:0.5|max:' . $tangCa->so_gio_tang_ca,
        ]);

        $soGioThucTe = $request->so_gio_thuc_te;

        DB::beginTransaction();

        // Cập nhật thực hiện tăng ca
        $thucHien->update([
            'so_gio_tang_ca_thuc_te' => $soGioThucTe,
            'trang_thai' => 'quan_ly_xac_nhan',
        ]);

        // Tính lương
        $userId = $tangCa->nguoi_dung_id;
        $type = $tangCa->loai_tang_ca;
        $luongTangCa = OvertimeHelper::tinhLuongTangCa($userId, $soGioThucTe, $type);

        // ⭐ CẬP NHẬT: Không sửa trang_thai, chỉ cập nhật các trường khác
        $tangCa->luong_tang_ca = $luongTangCa;
        $tangCa->da_hoan_thanh = true;
        $tangCa->thoi_gian_hoan_thanh = now();
        $tangCa->thieu_cham_cong_ra = false;
        // KHÔNG CÓ: $tangCa->trang_thai = 'da_duyet';
        $tangCa->save();

        Log::info('✅ Truong phong approved sua chua cong: ID ' . $tangCa->id);

        // Gửi thông báo
        try {
            $this->notificationService->notifyOvertime($tangCa, 'correction_approved');
        } catch (\Exception $e) {
            Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
        }

        DB::commit();

        return redirect()
            ->route('truong-phong.tang-ca.show', $tangCa->id)
            ->with('success', '✅ Xác nhận sửa chữa công thành công. Lương: ' . number_format($luongTangCa) . 'đ');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ approveSuaChuaCong error: ' . $e->getMessage());
        return redirect()
            ->route('truong-phong.tang-ca.show', $id)
            ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
    }
}

// Sửa phương thức rejectSuaChuaCong()
public function rejectSuaChuaCong(Request $request, $id)
{
    try {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $tangCa = DangKyTangCa::with(['nguoi_dung', 'thuc_hien'])
            ->whereIn('nguoi_dung_id', $nhanVienIds)
            ->findOrFail($id);

        $thucHien = ThucHienTangCa::where('dang_ky_tang_ca_id', $tangCa->id)
            ->where('trang_thai', 'cho_xac_nhan_sua_chua')
            ->first();

        if (!$thucHien) {
            return back()->with('error', 'Không tìm thấy yêu cầu sửa chữa công');
        }

        DB::beginTransaction();

        $thucHien->update([
            'trang_thai' => 'tu_choi_sua_chua',
            'ghi_chu' => $request->ly_do_tu_choi,
        ]);

        // ⭐ CẬP NHẬT: Chỉ đánh dấu thiếu chấm công ra, KHÔNG sửa trang_thai
        $tangCa->update([
            'thieu_cham_cong_ra' => true,
        ]);

        // Gửi thông báo
        try {
            $this->notificationService->notifyOvertime($tangCa, 'correction_rejected');
        } catch (\Exception $e) {
            Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
        }

        DB::commit();

        return redirect()
            ->route('truong-phong.tang-ca.show', $tangCa->id)
            ->with('success', '✅ Đã từ chối yêu cầu sửa chữa công.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ rejectSuaChuaCong error: ' . $e->getMessage());
        return redirect()
            ->route('truong-phong.tang-ca.show', $id)
            ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
    }
}
}
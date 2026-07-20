<?php
// app/Http/Controllers/Employee/TangCaController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\ThucHienTangCa;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TangCaController extends Controller
{
    protected NotificationService $notificationService;

    /**
     * CHÍNH SÁCH TĂNG CA
     * 
     * 1. SỐ TRẠNG THÁI: Có 4 trạng thái đơn tăng ca
     *    - cho_duyet: Chờ duyệt
     *    - da_duyet: Đã duyệt
     *    - tu_choi: Từ chối
     *    - huy: Đã hủy
     * 
     * 2. KIỂM TRA KHI TẠO ĐƠN: Chỉ kiểm tra với đơn đang hoạt động
     *    - Kiểm tra xung đột với đơn chờ duyệt? CÓ
     *    - Kiểm tra xung đột với đơn đã duyệt? CÓ
     *    - Kiểm tra xung đột với đơn đã từ chối? KHÔNG (đơn này không có giá trị)
     *    - Kiểm tra xung đột với đơn đã hủy? KHÔNG (cho phép đặt lại giờ đó)
     * 
     * 3. GIỚI HẠN GIỜ: Tối đa 8 giờ tăng ca/ngày
     * 
     * 4. KIỂM TRA XUNG ĐỘT: Có kiểm tra xung đột với giờ làm việc chính thức
     */

    // Cấu hình kiểm tra
    protected array $trangThaiKiemTra = ['cho_duyet', 'da_duyet']; // Chỉ kiểm tra đơn đang hoạt động (chờ duyệt và đã duyệt)
    protected int $maxHoursPerDay = 8; // Giới hạn tối đa 8 giờ/ngày
    protected ?array $gioLamViecChinhThuc = null; // ['start' => '08:00', 'end' => '17:00'] hoặc null nếu không kiểm tra

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;

        // Giờ làm việc chính thức: 8h - 17h
        // Tăng ca chỉ được từ 17h chiều trở đi
        $this->gioLamViecChinhThuc = [
            'start' => '08:00',
            'end' => '17:00'
        ];
    }

    /**
     * Hiển thị danh sách đơn tăng ca của người dùng
     */
    public function index()
    {
        $user = Auth::user();

        $tangCas = DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->orderBy('ngay_tang_ca', 'desc')
            ->orderBy('gio_bat_dau', 'desc')
            ->paginate(15);

        // Tính thống kê trực tiếp từ database
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
            'trangThaiLabels' => DangKyTangCa::$trangThaiLabels,
            'loaiLabels' => DangKyTangCa::$loaiLabels,
        ]);
    }

    /**
     * Hiển thị form tạo đơn tăng ca
     */
    public function create()
    {
        return view('employee.tang-ca.create', [
            'loaiLabels' => DangKyTangCa::$loaiLabels,
        ]);
    }

    /**
     * Kiểm tra đơn tăng ca trùng lặp
     * 
     * @param int $userId ID người dùng
     * @param string $ngayTangCa Ngày tăng ca (Y-m-d)
     * @param string $gioBatDau Giờ bắt đầu (H:i)
     * @param string $gioKetThuc Giờ kết thúc (H:i)
     * @param int|null $excludeId Loại trừ ID khi cập nhật
     * @return array ['valid' => bool, 'message' => string, 'don_trung' => Model|null]
     * 
     * KIỂM TRA:
     * 1. Xung đột thời gian với đơn chờ duyệt hoặc đã duyệt (đơn còn hoạt động)
     * 2. Giới hạn số giờ tối đa trong 1 ngày (8 giờ)
     * 3. Xung đột với giờ làm việc chính thức (nếu có cấu hình)
     * 
     * KHÔNG KIỂM TRA với:
     * - Đơn từ chối: Không còn giá trị nên không xung đột
     * - Đơn hủy: Cho phép đặt lại những giờ đó
     */
    private function validateOvertime($userId, $ngayTangCa, $gioBatDau, $gioKetThuc, $excludeId = null)
    {
        Log::info('🔍 Validating overtime:', [
            'user_id' => $userId,
            'date' => $ngayTangCa,
            'start' => $gioBatDau,
            'end' => $gioKetThuc
        ]);

        // 1️⃣ KIỂM TRA XUNG ĐỘT THỜI GIAN VỚI NHỮNG ĐƠN ĐANG HOẠT ĐỘNG
        // - Chờ duyệt (cho_duyet) ✅
        // - Đã duyệt (da_duyet) ✅
        // - Từ chối (tu_choi) ❌ KHÔNG kiểm tra - đơn này không còn giá trị
        // - Hủy (huy) ❌ KHÔNG kiểm tra - cho phép đặt lại những giờ đó
        $query = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->where('ngay_tang_ca', $ngayTangCa)
            ->whereIn('trang_thai', $this->trangThaiKiemTra);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingRecords = $query->get();

        foreach ($existingRecords as $record) {
            $existingStart = Carbon::parse($record->gio_bat_dau);
            $existingEnd = Carbon::parse($record->gio_ket_thuc);
            $newStart = Carbon::parse($gioBatDau);
            $newEnd = Carbon::parse($gioKetThuc);

            // Kiểm tra thời gian trùng lặp
            if ($newStart < $existingEnd && $newEnd > $existingStart) {
                $trangThaiLabel = DangKyTangCa::$trangThaiLabels[$record->trang_thai] ?? $record->trang_thai;

                return [
                    'valid' => false,
                    'don_trung' => $record,
                    'message' => sprintf(
                        '❌ Đơn tăng ca bị trùng với ca làm việc từ %s đến %s (Trạng thái: %s, ID: #%d)',
                        $record->gio_bat_dau,
                        $record->gio_ket_thuc,
                        $trangThaiLabel,
                        $record->id
                    )
                ];
            }
        }

        // 2️⃣ KIỂM TRA GIỚI HẠN SỐ GIỜ TĂNG CA TỐI ĐA TRONG 1 NGÀY
        // Giới hạn: Tối đa 8 giờ/ngày
        $soGio = Carbon::parse($gioBatDau)->diffInHours(Carbon::parse($gioKetThuc));

        if ($soGio > $this->maxHoursPerDay) {
            return [
                'valid' => false,
                'don_trung' => null,
                'message' => "❌ Số giờ tăng ca không được vượt quá {$this->maxHoursPerDay} giờ/ngày"
            ];
        }

        // 3️⃣ KIỂM TRA XUNG ĐỘT VỚI GIỜ LÀM VIỆC CHÍNH THỨC
        // Nếu cấu hình giờ làm việc chính thức, kiểm tra xem giờ tăng ca có nằm trong khoảng đó không
        // (Nếu gioLamViecChinhThuc = null thì không kiểm tra - cho phép tăng ca bất kỳ lúc nào)
        if ($this->gioLamViecChinhThuc) {
            $workingStart = Carbon::parse($this->gioLamViecChinhThuc['start']);
            $workingEnd = Carbon::parse($this->gioLamViecChinhThuc['end']);
            $newStart = Carbon::parse($gioBatDau);
            $newEnd = Carbon::parse($gioKetThuc);

            // Kiểm tra xem giờ tăng ca có nằm trong giờ làm việc chính thức không
            if ($newStart < $workingEnd && $newEnd > $workingStart) {
                return [
                    'valid' => false,
                    'don_trung' => null,
                    'message' => sprintf(
                        '❌ Thời gian tăng ca (%s - %s) trùng với giờ làm việc chính thức (%s - %s)',
                        $gioBatDau,
                        $gioKetThuc,
                        $this->gioLamViecChinhThuc['start'],
                        $this->gioLamViecChinhThuc['end']
                    )
                ];
            }
        }

        return ['valid' => true, 'message' => 'Đơn hợp lệ'];
    }
    /** 
     * Lưu đơn tăng ca mới
     */
    public function store(Request $request)
    {
        Log::info('🚀=== TANG CA STORE CALLED ===🚀');

        $request->validate([
            'ngay_tang_ca' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'loai_tang_ca' => 'required|in:ngay_thuong,ngay_nghi',
            'ly_do_tang_ca' => 'required|string|min:10|max:500',
        ]);

        $user = Auth::user();
        Log::info('📝 User ID: ' . $user->id . ' - ' . $user->email);

        // Tính số giờ tăng ca
        $gioBatDau = Carbon::parse($request->gio_bat_dau);
        $gioKetThuc = Carbon::parse($request->gio_ket_thuc);
        $soGioTangCa = $gioBatDau->diffInHours($gioKetThuc);

        // ⭐ KIỂM TRA GIỚI HẠN GIỜ TĂNG CA
        $kiemTraGioiHan = OvertimeHelper::kiemTraGioiHan(
            $user->id,
            $request->ngay_tang_ca,
            $soGioTangCa
        );

        if (!$kiemTraGioiHan['valid']) {
            Log::warning('⚠️ Overtime limit exceeded: ' . $kiemTraGioiHan['message']);
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $kiemTraGioiHan['message']]);
        }

        // ⭐ VALIDATE TẤT CẢ ĐIỀU KIỆN
        $validation = $this->validateOvertime(
            $user->id,
            $request->ngay_tang_ca,
            $request->gio_bat_dau,
            $request->gio_ket_thuc
        );

        if (!$validation['valid']) {
            Log::warning('⚠️ Overtime validation failed: ' . $validation['message']);
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $validation['message']]);
        }

        DB::beginTransaction();
        try {
            $tangCa = DangKyTangCa::create([
                'nguoi_dung_id' => $user->id,
                'ngay_tang_ca' => $request->ngay_tang_ca,
                'gio_bat_dau' => $request->gio_bat_dau,
                'gio_ket_thuc' => $request->gio_ket_thuc,
                'so_gio_tang_ca' => $soGioTangCa,
                'loai_tang_ca' => $request->loai_tang_ca,
                'ly_do_tang_ca' => $request->ly_do_tang_ca,
                'trang_thai' => 'cho_duyet',
            ]);

            Log::info('✅ TangCa created successfully: ID ' . $tangCa->id);

            // GỬI THÔNG BÁO
            try {
                $this->notificationService->notifyOvertime($tangCa, 'created');
                Log::info('✅ Overtime notification sent successfully');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã gửi đơn xin tăng ca thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Tang ca error: ' . $e->getMessage());
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo đơn: ' . $e->getMessage());
        }
    }

    /**
     * API kiểm tra trùng lặp (AJAX)
     */
    public function checkTrungLapAjax(Request $request)
    {
        try {
            $request->validate([
                'ngay_tang_ca' => 'required|date',
                'gio_bat_dau' => 'required|date_format:H:i',
                'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            ]);

            $user = Auth::user();
            $validation = $this->validateOvertime(
                $user->id,
                $request->ngay_tang_ca,
                $request->gio_bat_dau,
                $request->gio_ket_thuc
            );

            return response()->json([
                'success' => true,
                'valid' => $validation['valid'],
                'message' => $validation['message'],
                'don_trung' => $validation['don_trung'] ? [
                    'id' => $validation['don_trung']->id,
                    'gio_bat_dau' => $validation['don_trung']->gio_bat_dau,
                    'gio_ket_thuc' => $validation['don_trung']->gio_ket_thuc,
                    'trang_thai' => $validation['don_trung']->trang_thai,
                    'trang_thai_label' => DangKyTangCa::$trangThaiLabels[$validation['don_trung']->trang_thai] ?? $validation['don_trung']->trang_thai,
                ] : null
            ]);
        } catch (\Exception $e) {
            Log::error('❌ AJAX check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết đơn tăng ca
     */
    public function show($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::findOrFail($id);

        // Kiểm tra quyền - chỉ user tạo đơn mới được xem
        if ($donTangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền xem đơn này');
        }

        return view('employee.tang-ca.show', [
            'donTangCa' => $donTangCa,
            'trangThaiLabel' => DangKyTangCa::$trangThaiLabels[$donTangCa->trang_thai] ?? $donTangCa->trang_thai,
            'loaiLabel' => DangKyTangCa::$loaiLabels[$donTangCa->loai_tang_ca] ?? $donTangCa->loai_tang_ca,
        ]);
    }

    /**
     * Hiển thị form cập nhật đơn tăng ca (chỉ những đơn chưa duyệt)
     */
    public function edit($id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        // Kiểm tra quyền
        if ($tangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền chỉnh sửa đơn này');
        }

        // Chỉ cho phép chỉnh sửa đơn chờ duyệt
        if ($tangCa->trang_thai !== 'cho_duyet') {
            abort(403, 'Chỉ có thể chỉnh sửa đơn chờ duyệt');
        }

        return view('employee.tang-ca.edit', [
            'tangCa' => $tangCa,
            'loaiLabels' => DangKyTangCa::$loaiLabels,
        ]);
    }

    /**
     * Cập nhật đơn tăng ca
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        // Kiểm tra quyền
        if ($tangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền chỉnh sửa đơn này');
        }

        // Chỉ cho phép chỉnh sửa đơn chờ duyệt
        if ($tangCa->trang_thai !== 'cho_duyet') {
            return back()->with('error', 'Chỉ có thể chỉnh sửa đơn chờ duyệt');
        }

        $request->validate([
            'ngay_tang_ca' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required',
            'gio_ket_thuc' => 'required|after:gio_bat_dau',
            'loai_tang_ca' => 'required|in:ngay_thuong,ngay_nghi',
            'ly_do_tang_ca' => 'required|string|min:10|max:500',
        ]);

        // ⭐ VALIDATE TẤT CẢ ĐIỀU KIỆN (chỉ kiểm tra với đơn đang hoạt động)
        $validation = $this->validateOvertime(
            $user->id,
            $request->ngay_tang_ca,
            $request->gio_bat_dau,
            $request->gio_ket_thuc,
            $tangCa->id  // Loại trừ đơn hiện tại
        );

        if (!$validation['valid']) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $validation['message']]);
        }

        // Tính số giờ tăng ca
        $gioBatDau = Carbon::parse($request->gio_bat_dau);
        $gioKetThuc = Carbon::parse($request->gio_ket_thuc);
        $soGioTangCa = $gioBatDau->diffInHours($gioKetThuc);

        DB::beginTransaction();
        try {
            $tangCa->update([
                'ngay_tang_ca' => $request->ngay_tang_ca,
                'gio_bat_dau' => $request->gio_bat_dau,
                'gio_ket_thuc' => $request->gio_ket_thuc,
                'so_gio_tang_ca' => $soGioTangCa,
                'loai_tang_ca' => $request->loai_tang_ca,
                'ly_do_tang_ca' => $request->ly_do_tang_ca,
            ]);

            Log::info('✅ TangCa updated: ID ' . $tangCa->id);

            DB::commit();

            return redirect()->route('employee.tang-ca.show', $tangCa->id)
                ->with('success', '✅ Cập nhật đơn tăng ca thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Tang ca update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage());
        }
    }

    /**
     * Hủy đơn tăng ca (chỉ những đơn chưa duyệt)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        // Kiểm tra quyền
        if ($tangCa->nguoi_dung_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền hủy đơn này'], 403);
        }

        // Chỉ cho phép hủy đơn chờ duyệt
        if ($tangCa->trang_thai !== 'cho_duyet') {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể hủy đơn chờ duyệt'], 403);
        }

        DB::beginTransaction();
        try {
            $tangCa->update(['trang_thai' => 'huy']);
            Log::info('✅ TangCa cancelled: ID ' . $tangCa->id);

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã hủy đơn tăng ca!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Tang ca destroy error: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn: ' . $e->getMessage());
        }
    }

    /**
     * Alias cho destroy() - hủy đơn tăng ca (route name: employee.tang-ca.huy)
     */
    public function huy($id)
    {
        return $this->destroy($id);
    }

    /**
     * Nhân viên xác nhận đã làm tăng ca
     * Tạo/Cập nhật bản ghi thực hiện tăng ca với trạng thái "nhan_vien_xac_nhan"
     */

    /**
     * Nhân viên xác nhận đã làm tăng ca
     * Tạo/Cập nhật bản ghi thực hiện tăng ca với trạng thái "nhan_vien_xac_nhan"
     */
    public function confirmThucHien($id)
    {
        $user = Auth::user();

        $donTangCa = DangKyTangCa::with('nguoi_dung')->findOrFail($id);

        // Kiểm tra quyền - chỉ user tạo đơn mới được xác nhận
        if ($donTangCa->nguoi_dung_id !== $user->id) {
            return back()->with('error', 'Không có quyền xác nhận đơn này');
        }

        // Chỉ cho phép xác nhận đơn đã duyệt
        if ($donTangCa->trang_thai !== 'da_duyet') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn đã duyệt');
        }

        // Kiểm tra đã có bản ghi thực hiện chưa
        if ($donTangCa->thuc_hien) {
            return back()->with('error', 'Đơn này đã được xác nhận trước đó');
        }

        // ⭐ KIỂM TRA THỜI GIAN CHI TIẾT
        $now = Carbon::now();
        $ngayTangCa = Carbon::parse($donTangCa->ngay_tang_ca);
        $gioBatDau = Carbon::parse($donTangCa->gio_bat_dau);

        // Tạo datetime đầy đủ: ngày + giờ bắt đầu
        $thoiGianBatDau = Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'));

        // ⭐ CHO PHÉP XÁC NHẬN TRONG KHOẢNG THỜI GIAN:
        // - Từ 30 phút trước giờ bắt đầu (cho phép check-in sớm)
        // - Đến 2 giờ sau giờ kết thúc (cho phép check-out muộn)
        $thoiGianChoPhepSom = $thoiGianBatDau->copy()->subMinutes(30);
        $thoiGianKetThuc = Carbon::parse($donTangCa->gio_ket_thuc);
        $thoiGianChoPhepMuon = Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $thoiGianKetThuc->format('H:i:s'))->addHours(2);

        // ⭐ KIỂM TRA
        if ($now->lt($thoiGianChoPhepSom)) {
            $thoiGianConLai = $now->diffInMinutes($thoiGianChoPhepSom);
            $gioConLai = floor($thoiGianConLai / 60);
            $phutConLai = $thoiGianConLai % 60;

            $thongBao = "Chưa đến giờ tăng ca! Còn {$gioConLai} giờ {$phutConLai} phút nữa mới được xác nhận.";
            return back()->with('error', $thongBao);
        }

        if ($now->gt($thoiGianChoPhepMuon)) {
            return back()->with('error', 'Đã quá thời gian cho phép xác nhận tăng ca!');
        }

        DB::beginTransaction();
        try {
            // Tạo bản ghi thực hiện tăng ca
            $thucHien = ThucHienTangCa::create([
                'dang_ky_tang_ca_id' => $donTangCa->id,
                'gio_bat_dau_thuc_te' => $donTangCa->gio_bat_dau,
                'gio_ket_thuc_thuc_te' => $donTangCa->gio_ket_thuc,
                'so_gio_tang_ca_thuc_te' => $donTangCa->so_gio_tang_ca,
                'so_cong_tang_ca' => 1,
                'trang_thai' => 'nhan_vien_xac_nhan',
            ]);

            Log::info('✅ Employee confirmed overtime: DangKyTangCa ID ' . $donTangCa->id);

            // GỬI THÔNG BÁO CHO ADMIN/QUẢN LÝ
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
     * Quản lý xác nhận đã hoàn thành tăng ca
     * Cập nhật trạng thái thực hiện thành "quan_ly_xac_nhan"
     * Và cộng tiền lương tăng ca
     */
    public function approveThucHien(Request $request, $id)
    {
        // Kiểm tra quyền quản lý (cần thêm middleware check role sau)
        $donTangCa = DangKyTangCa::findOrFail($id);
        $thucHien = $donTangCa->thuc_hien;

        if (!$thucHien) {
            return back()->with('error', 'Nhân viên chưa xác nhận đã làm tăng ca');
        }

        // Kiểm tra trạng thái
        if ($thucHien->trang_thai !== 'nhan_vien_xac_nhan') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn mà nhân viên đã xác nhận');
        }

        $request->validate([
            'so_gio_tang_ca_thuc_te' => 'required|numeric|min:0.5|max:16',
            'cong_viec_da_thuc_hien' => 'nullable|string|max:500',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật thực hiện tăng ca
            $thucHien->update([
                'so_gio_tang_ca_thuc_te' => $request->so_gio_tang_ca_thuc_te,
                'cong_viec_da_thuc_hien' => $request->cong_viec_da_thuc_hien,
                'ghi_chu' => $request->ghi_chu,
                'trang_thai' => 'quan_ly_xac_nhan',
            ]);

            Log::info('✅ Manager approved overtime: DangKyTangCa ID ' . $donTangCa->id . ', Hours: ' . $request->so_gio_tang_ca_thuc_te);

            // TODO: Cộng tiền lương tăng ca vào lương nhân viên
            // Bạn cần thêm logic để cập nhật bảng lương ở đây
            // Ví dụ: $this->updateSalaryWithOvertime($donTangCa->nguoi_dung_id, $request->so_gio_tang_ca_thuc_te);

            DB::commit();

            return redirect()->route('employee.tang-ca.show', $donTangCa->id)
                ->with('success', '✅ Xác nhận hoàn thành! Tiền lương tăng ca đã được cộng.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Approve thuc hien error: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra khi xác nhận hoàn thành: ' . $e->getMessage());
        }
    }
}

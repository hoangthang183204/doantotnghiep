<?php
// app/Http/Controllers/Employee/TangCaController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\ThucHienTangCa;
use App\Models\XinVeSomTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Services\NotificationService;
use Carbon\Carbon;
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

    public function create()
    {
        return view('employee.tang-ca.create');
    }

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

    public function show($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::with(['nguoi_duyet.hoSo', 'thuc_hien'])->findOrFail($id);

        if ($donTangCa->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền xem yêu cầu này');
        }

        return view('employee.tang-ca.show', [
            'donTangCa' => $donTangCa,
        ]);
    }

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

    public function huy($id)
    {
        return $this->destroy($id);
    }

    public function tuChoiDon(Request $request, $id)
    {
        $user = Auth::user();
        $tangCa = DangKyTangCa::findOrFail($id);

        if ($tangCa->nguoi_dung_id !== $user->id) {
            return back()->with('error', 'Không có quyền từ chối đơn này');
        }

        if ($tangCa->loai_tao !== 'truong_phong') {
            return back()->with('error', 'Chỉ có thể từ chối đơn tăng ca do trưởng phòng tạo');
        }

        if ($tangCa->trang_thai !== 'da_duyet') {
            return back()->with('error', 'Chỉ có thể từ chối đơn tăng ca đã được duyệt');
        }

        $thucHien = ThucHienTangCa::where('dang_ky_tang_ca_id', $tangCa->id)->first();
        if ($thucHien && $thucHien->thoi_gian_ket_thuc) {
            return back()->with('error', 'Đơn tăng ca đã được check-out, không thể từ chối');
        }

        $request->validate([
            'ly_do_tu_choi' => 'required|string|min:10|max:500',
        ]);

        DB::beginTransaction();
        try {
            $tangCa->update([
                'trang_thai' => 'tu_choi',
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            Log::info('✅ Nhan vien tu choi don tang ca: ID ' . $tangCa->id);

            try {
                $this->notificationService->notifyOvertime($tangCa, 'employee_rejected');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã từ chối đơn tăng ca!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Tu choi don tang ca error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi từ chối đơn: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ NHÂN VIÊN CHECK-OUT TĂNG CA
     */
    public function checkout($id)
    {
        try {
            $user = Auth::user();
            $donTangCa = DangKyTangCa::with(['thuc_hien', 'xin_ve_som' => function ($q) {
                $q->where('trang_thai', 'da_duyet');
            }])->findOrFail($id);

            // Kiểm tra quyền
            if ($donTangCa->nguoi_dung_id !== $user->id) {
                return back()->with('error', 'Không có quyền check-out đơn này');
            }

            // Kiểm tra trạng thái đơn
            if ($donTangCa->trang_thai !== 'da_duyet') {
                return back()->with('error', 'Đơn tăng ca chưa được duyệt');
            }

            if ($donTangCa->da_hoan_thanh) {
                return back()->with('error', 'Đơn tăng ca đã hoàn thành');
            }

            $thucHien = $donTangCa->thuc_hien;

            // Kiểm tra đã check-out chưa
            if ($thucHien && $thucHien->thoi_gian_ket_thuc) {
                return back()->with('error', 'Bạn đã check-out từ lúc ' . Carbon::parse($thucHien->thoi_gian_ket_thuc)->format('H:i'));
            }

            // Kiểm tra có thể check-out không
            $canCheckout = DangKyTangCa::canCheckout($donTangCa->id);

            if (!$canCheckout['valid']) {
                return back()->with('error', $canCheckout['message']);
            }

            $soGioThucTe = $canCheckout['so_gio_thuc_te'] ?? 0;
            $isEarly = $canCheckout['is_early'] ?? false;
            $soPhutVeSom = $canCheckout['so_phut_ve_som'] ?? 0;
            $hasXinVeSom = $canCheckout['has_xin_ve_som'] ?? false;

            DB::beginTransaction();
            try {
                // Tạo hoặc cập nhật bản ghi thực hiện
                if (!$thucHien) {
                    // Tạo bản ghi mới với giờ bắt đầu là giờ đăng ký
                    $ngayTangCa = Carbon::parse($donTangCa->ngay_tang_ca);
                    $gioBatDau = Carbon::parse($donTangCa->gio_bat_dau);
                    $thoiGianBatDau = Carbon::parse(
                        $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s')
                    );

                    $thucHien = ThucHienTangCa::create([
                        'dang_ky_tang_ca_id' => $donTangCa->id,
                        'nguoi_dung_id' => $user->id,
                        'thoi_gian_bat_dau' => $thoiGianBatDau,
                        'thoi_gian_ket_thuc' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'so_gio_tang_ca_thuc_te' => $soGioThucTe,
                        'trang_thai' => 'nhan_vien_xac_nhan',
                    ]);
                } else {
                    $thucHien->update([
                        'thoi_gian_ket_thuc' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'so_gio_tang_ca_thuc_te' => $soGioThucTe,
                        'trang_thai' => 'nhan_vien_xac_nhan',
                    ]);
                }

                // ⭐ TẠM THỜI LƯU LƯƠNG TẠM TÍNH
                $userId = $donTangCa->nguoi_dung_id;
                $type = $donTangCa->loai_tang_ca;
                $luongTangCa = OvertimeHelper::tinhLuongTangCa($userId, $soGioThucTe, $type);

                $donTangCa->luong_tang_ca = $luongTangCa;
                $donTangCa->save();

                Log::info('Employee checkout: ThucHien ID ' . $thucHien->id .
                    ' - So gio thuc te: ' . $soGioThucTe .
                    ' - Luong tam tinh: ' . $luongTangCa);

                // ⭐ GỬI THÔNG BÁO CHO TRƯỞNG PHÒNG
                try {
                    $truongPhong = $this->getTruongPhong($user);
                    if ($truongPhong) {
                        $this->notificationService->notifyOvertime($donTangCa, 'employee_confirmed');
                    }
                } catch (\Exception $e) {
                    Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
                }

                DB::commit();

                $message = "✅ Check-out thành công! Số giờ: " . number_format($soGioThucTe, 1, ',', '') . " giờ.";
                if ($isEarly && $hasXinVeSom) {
                    $message .= " (Đã được duyệt về sớm {$soPhutVeSom} phút)";
                } elseif ($isEarly) {
                    $message .= " (Check-out sớm trong 1 tiếng cuối)";
                }
                $message .= " Đang chờ trưởng phòng xác nhận.";

                return redirect()
                    ->route('employee.tang-ca.show', $donTangCa->id)
                    ->with('success', $message);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Checkout error: ' . $e->getMessage());
                return back()->with('error', 'Có lỗi xảy ra khi check-out: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('checkout error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    /**
     * ⭐ NHÂN VIÊN CHECK-OUT (GỬI THÔNG BÁO CHO TRƯỞNG PHÒNG)
     */
    public function confirmThucHien($id)
    {
        try {
            $user = Auth::user();
            $donTangCa = DangKyTangCa::with('nguoi_dung', 'thuc_hien')->findOrFail($id);

            Log::info('=== confirmThucHien START ===');
            Log::info('Don ID: ' . $id . ', User ID: ' . $user->id);

            // Kiểm tra quyền
            if ($donTangCa->nguoi_dung_id !== $user->id) {
                return back()->with('error', 'Không có quyền xác nhận đơn này');
            }

            // Kiểm tra trạng thái đơn
            if ($donTangCa->trang_thai !== 'da_duyet') {
                return back()->with('error', 'Chỉ có thể check-out đơn đã duyệt');
            }

            if ($donTangCa->da_hoan_thanh) {
                return back()->with('error', 'Đơn tăng ca đã hoàn thành');
            }

            $thucHien = $donTangCa->thuc_hien;
            $now = Carbon::now('Asia/Ho_Chi_Minh');

            // ⭐ KIỂM TRA CÓ THỂ CHECK-OUT KHÔNG
            $canCheckout = DangKyTangCa::canCheckout($donTangCa->id);

            if (!$canCheckout['valid']) {
                return back()->with('error', $canCheckout['message']);
            }

            $soGioThucTe = $canCheckout['so_gio_thuc_te'] ?? $donTangCa->so_gio_tang_ca;
            $isCheckoutSom = $canCheckout['is_early'] ?? false;
            $soPhutConLai = $canCheckout['early_minutes'] ?? 0;

            DB::beginTransaction();
            try {
                // Tạo hoặc cập nhật bản ghi thực hiện
                if (!$thucHien) {
                    // Tự động tạo bản ghi thực hiện với giờ bắt đầu là giờ bắt đầu tăng ca
                    $ngayTangCa = Carbon::parse($donTangCa->ngay_tang_ca);
                    $gioBatDau = Carbon::parse($donTangCa->gio_bat_dau);
                    $thoiGianBatDau = Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'));

                    $thucHien = ThucHienTangCa::create([
                        'dang_ky_tang_ca_id' => $donTangCa->id,
                        'nguoi_dung_id' => $user->id,
                        'thoi_gian_bat_dau' => $thoiGianBatDau,
                        'thoi_gian_ket_thuc' => $now,
                        'so_gio_tang_ca_thuc_te' => $soGioThucTe,
                        'trang_thai' => 'nhan_vien_xac_nhan',
                    ]);
                } else {
                    $thucHien->update([
                        'thoi_gian_ket_thuc' => $now,
                        'so_gio_tang_ca_thuc_te' => $soGioThucTe,
                        'trang_thai' => 'nhan_vien_xac_nhan',
                    ]);
                }

                // ⭐ TẠM THỜI LƯU LƯƠNG TẠM TÍNH (SẼ CẬP NHẬT LẠI KHI TRƯỞNG PHÒNG XÁC NHẬN)
                $userId = $donTangCa->nguoi_dung_id;
                $type = $donTangCa->loai_tang_ca;
                $luongTangCa = OvertimeHelper::tinhLuongTangCa($userId, $soGioThucTe, $type);

                $donTangCa->luong_tang_ca = $luongTangCa;
                $donTangCa->save();

                Log::info('Employee checkout: ThucHien ID ' . $thucHien->id .
                    ' - So gio thuc te: ' . $soGioThucTe .
                    ' - Luong tam tinh: ' . $luongTangCa);

                // ⭐ GỬI THÔNG BÁO CHO TRƯỞNG PHÒNG
                try {
                    $truongPhong = $this->getTruongPhong($user);
                    if ($truongPhong) {
                        $this->notificationService->notifyOvertime($donTangCa, 'employee_confirmed');
                        Log::info('📧 Đã gửi thông báo check-out đến trưởng phòng: ' . $truongPhong->email);
                    }
                } catch (\Exception $e) {
                    Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
                }

                DB::commit();

                $message = $isCheckoutSom
                    ? "✅ Check-out sớm thành công! Đang chờ trưởng phòng xác nhận. Số giờ: {$soGioThucTe} giờ. (Sớm {$soPhutConLai} phút)"
                    : "✅ Check-out thành công! Đang chờ trưởng phòng xác nhận. Số giờ: {$soGioThucTe} giờ.";

                return redirect()->route('employee.tang-ca.index')
                    ->with('success', $message);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Checkout error: ' . $e->getMessage());
                return back()->with('error', 'Có lỗi xảy ra khi check-out: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('confirmThucHien error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function xinVeSom($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::with(['thuc_hien', 'xin_ve_som'])
            ->where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        // Kiểm tra điều kiện
        if ($donTangCa->trang_thai !== 'da_duyet') {
            return back()->with('error', 'Đơn tăng ca chưa được duyệt');
        }

        if ($donTangCa->da_hoan_thanh) {
            return back()->with('error', 'Đơn tăng ca đã hoàn thành');
        }

        // Kiểm tra đã có đơn xin về sớm chưa
        $xinVeSom = $donTangCa->xin_ve_som;
        if ($xinVeSom && in_array($xinVeSom->trang_thai, ['cho_duyet', 'da_duyet'])) {
            $trangThaiText = XinVeSomTangCa::$trangThaiLabels[$xinVeSom->trang_thai] ?? $xinVeSom->trang_thai;
            return back()->with('error', "Bạn đã gửi đơn xin về sớm (Trạng thái: {$trangThaiText})");
        }

        return view('employee.tang-ca.xin-ve-som', compact('donTangCa'));
    }

    /**
     * 💾 Lưu đơn xin về sớm
     */
    public function storeXinVeSom(Request $request, $id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        $request->validate([
            'gio_ve_som' => 'required|date_format:H:i',
            'ly_do' => 'nullable|string|max:500',
        ]);

        // Kiểm tra điều kiện
        if ($donTangCa->trang_thai !== 'da_duyet') {
            return back()->with('error', 'Đơn tăng ca chưa được duyệt');
        }

        if ($donTangCa->da_hoan_thanh) {
            return back()->with('error', 'Đơn tăng ca đã hoàn thành');
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $ngayTangCa = Carbon::parse($donTangCa->ngay_tang_ca)->startOfDay();
        $gioVeSom = Carbon::parse($request->gio_ve_som);

        $thoiGianBatDau = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . $donTangCa->gio_bat_dau
        );
        $thoiGianKetThuc = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . $donTangCa->gio_ket_thuc
        );
        $thoiGianVeSom = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . $gioVeSom->format('H:i:s')
        );

        // Kiểm tra giờ về sớm phải trong khoảng thời gian tăng ca
        if ($thoiGianVeSom->lte($thoiGianBatDau)) {
            return back()->with('error', '⛔ Giờ về sớm phải sau giờ bắt đầu tăng ca');
        }

        if ($thoiGianVeSom->gte($thoiGianKetThuc)) {
            return back()->with('error', '⛔ Giờ về sớm phải trước giờ kết thúc tăng ca');
        }

        // Tính số phút về sớm
        $soPhutVeSom = $thoiGianKetThuc->diffInMinutes($thoiGianVeSom);

        // Kiểm tra về sớm tối đa 3 tiếng
        if ($soPhutVeSom > 180) {
            return back()->with('error', '⛔ Chỉ được xin về sớm tối đa 3 tiếng (hiện tại ' . $soPhutVeSom . ' phút)');
        }

        // Kiểm tra xem đã có đơn xin về sớm chưa
        $exists = XinVeSomTangCa::where('dang_ky_tang_ca_id', $donTangCa->id)
            ->whereIn('trang_thai', ['cho_duyet', 'da_duyet'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã có đơn xin về sớm đang chờ xử lý hoặc đã được duyệt');
        }

        DB::beginTransaction();
        try {
            $xinVeSom = XinVeSomTangCa::create([
                'dang_ky_tang_ca_id' => $donTangCa->id,
                'nguoi_dung_id' => $user->id,
                'gio_ve_som_du_kien' => $request->gio_ve_som,
                'so_phut_ve_som' => $soPhutVeSom,
                'ly_do' => $request->ly_do,
                'trang_thai' => 'cho_duyet',
            ]);

            // Gửi thông báo cho trưởng phòng
            try {
                $truongPhong = $this->getTruongPhong($user);
                if ($truongPhong) {
                    // Gửi thông báo
                }
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to send notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('employee.tang-ca.show', $donTangCa->id)
                ->with('success', '✅ Đã gửi đơn xin về sớm ' . $soPhutVeSom . ' phút, chờ trưởng phòng duyệt!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Xin ve som error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

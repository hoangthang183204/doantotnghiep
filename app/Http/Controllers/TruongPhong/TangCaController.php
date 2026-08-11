<?php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
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
                ->whereNotNull('ngay_tang_ca') // ⭐ Chỉ duyệt đơn tăng ca (không phải kiến nghị)
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->findOrFail($id);

            $dangKy->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'approved');

            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt đơn tăng ca thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Duyet tang ca error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
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
                ->whereNotNull('ngay_tang_ca') // ⭐ Chỉ từ chối đơn tăng ca (không phải kiến nghị)
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
     * (Dành cho kiến nghị có ngay_tang_ca = NULL)
     */
    public function duyetKienNghi(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            // Tìm kiến nghị (ngay_tang_ca = null) trong phòng ban
            $kienNghi = DangKyTangCa::where('trang_thai', 'cho_duyet')
                ->whereNull('ngay_tang_ca')
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->findOrFail($id);

            // Cập nhật trạng thái kiến nghị thành đã duyệt
            $kienNghi->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            // Gửi thông báo cho nhân viên
            $this->notificationService->notifyKienNghiTangCa($kienNghi, $kienNghi->nguoi_dung, 'approved');

            Log::info('✅ Truong phong duyet kien nghi: ID ' . $kienNghi->id);

            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt kiến nghị tăng ca! Vui lòng tạo đơn tăng ca.'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Duyet kien nghi error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ⭐ TỪ CHỐI KIẾN NGHỊ TĂNG CA
     */
    public function tuChoiKienNghi(Request $request, $id)
    {
        try {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|max:500',
            ]);

            $user = Auth::user();
            $nhanVienIds = $this->getNhanVienIdsTrongPhong();

            $kienNghi = DangKyTangCa::where('trang_thai', 'cho_duyet')
                ->whereNull('ngay_tang_ca')
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->findOrFail($id);

            $kienNghi->update([
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            $this->notificationService->notifyKienNghiTangCa($kienNghi, $kienNghi->nguoi_dung, 'rejected');

            return response()->json([
                'success' => true,
                'message' => '✅ Đã từ chối kiến nghị tăng ca!'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Tu choi kien nghi error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
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
}
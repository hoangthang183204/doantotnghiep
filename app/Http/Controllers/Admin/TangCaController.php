<?php
// app/Http/Controllers/Admin/TangCaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\ThucHienTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Helpers\SalaryHelper;
use App\Services\NotificationService;
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
     * ⭐ Lấy ID phòng ban của trưởng phòng (nếu là trưởng phòng)
     */
    private function getPhongBanId($user)
    {
        if ($user->phong_ban_id) {
            return $user->phong_ban_id;
        }
        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        return $phongBan ? $phongBan->id : null;
    }

    /**
     * ⭐ Kiểm tra user có phải trưởng phòng không
     */
    private function isTruongPhong($user)
    {
        if ($user->chucVu && in_array($user->chucVu->ten, ['Trưởng Phòng', 'Trưởng phòng', 'Quản lý', 'Manager'])) {
            return true;
        }
        if (PhongBan::where('truong_phong_id', $user->id)->exists()) {
            return true;
        }
        if ($user->vaiTros()->whereIn('name', ['truong_phong', 'quan_ly'])->exists()) {
            return true;
        }
        return false;
    }

    /**
     * ⭐ Lấy danh sách ID nhân viên trong phòng (nếu là trưởng phòng)
     */
    private function getNhanVienIdsByScope($user)
    {
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();

        if ($isAdmin) {
            return null;
        }

        $phongBanId = $this->getPhongBanId($user);
        if (!$phongBanId) {
            return [];
        }

        return NguoiDung::where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->pluck('id')
            ->toArray();
    }

    /**
     * 📋 Danh sách đăng ký tăng ca
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
        $isTruongPhong = $this->isTruongPhong($user);
        $nhanVienIds = $this->getNhanVienIdsByScope($user);

        $query = DangKyTangCa::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'nguoi_duyet.hoSo',
            'thuc_hien'
        ]);

        if (!$isAdmin && $isTruongPhong) {
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

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

        if ($request->filled('phong_ban_id')) {
            $query->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban_id);
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('ngay_tang_ca')) {
            $query->whereDate('ngay_tang_ca', $request->ngay_tang_ca);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
        }

        $donTangCa = $query
            ->orderBy('ngay_tang_ca', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->appends($request->query());

        $thongKeQuery = DangKyTangCa::query();

        if (!$isAdmin && $isTruongPhong) {
            if (!empty($nhanVienIds)) {
                $thongKeQuery->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                $thongKeQuery->whereRaw('1 = 0');
            }
        }

        if ($request->filled('ten_nhan_vien')) {
            $keyword = trim($request->ten_nhan_vien);
            $thongKeQuery->whereHas('nguoi_dung', function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                    });
            });
        }

        if ($request->filled('phong_ban_id')) {
            $thongKeQuery->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban_id);
            });
        }

        if ($request->filled('tu_ngay')) {
            $thongKeQuery->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $thongKeQuery->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
        }

        $thongKe = [
            'tong' => (clone $thongKeQuery)->count(),
            'cho_duyet' => (clone $thongKeQuery)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => (clone $thongKeQuery)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => (clone $thongKeQuery)->where('trang_thai', 'tu_choi')->count(),
            'huy' => (clone $thongKeQuery)->where('trang_thai', 'huy')->count(),
        ];

        $phongBans = PhongBan::all();

        $phongBanInfo = null;
        if (!$isAdmin && $isTruongPhong) {
            $phongBanId = $this->getPhongBanId($user);
            if ($phongBanId) {
                $phongBanInfo = PhongBan::find($phongBanId);
            }
        }

        if ($isAdmin) {
            return view('admin.tang-ca.index', compact(
                'donTangCa',
                'thongKe',
                'phongBans',
                'phongBanInfo',
                'isAdmin'
            ));
        } else {
            return view('truong-phong.tang-ca.index', compact(
                'donTangCa',
                'thongKe',
                'phongBans',
                'phongBanInfo',
                'isAdmin'
            ));
        }
    }

    /**
     * 👁️ Chi tiết đơn tăng ca
     */
    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
        $isTruongPhong = $this->isTruongPhong($user);
        $nhanVienIds = $this->getNhanVienIdsByScope($user);

        $query = DangKyTangCa::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'nguoi_duyet.hoSo',
            'thuc_hien',
        ]);

        if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
            $query->whereIn('nguoi_dung_id', $nhanVienIds);
        }

        $tangCa = $query->findOrFail($id);

        if ($isAdmin) {
            return view('admin.tang-ca.show', compact('tangCa'));
        } else {
            return view('truong-phong.tang-ca.show', compact('tangCa'));
        }
    }

    /**
     * ✅ Phê duyệt đơn tăng ca
     */
    public function duyet(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
            $isTruongPhong = $this->isTruongPhong($user);
            $nhanVienIds = $this->getNhanVienIdsByScope($user);

            $query = DangKyTangCa::where('trang_thai', 'cho_duyet');

            if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            }

            $dangKy = $query->findOrFail($id);

            $dangKy->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'approved');

            // ⭐ CHUYỂN VỀ INDEX THAY VÌ SHOW
            return redirect()
                ->route('admin.tang-ca.index')
                ->with('success', '✅ Đã duyệt đơn tăng ca thành công!');
        } catch (\Exception $e) {
            Log::error('❌ Duyet tang ca error: ' . $e->getMessage());
            return redirect()
                ->route('admin.tang-ca.index')
                ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
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
            $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
            $isTruongPhong = $this->isTruongPhong($user);
            $nhanVienIds = $this->getNhanVienIdsByScope($user);

            $query = DangKyTangCa::where('trang_thai', 'cho_duyet');

            if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            }

            $dangKy = $query->findOrFail($id);

            $dangKy->update([
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'rejected');

            // ⭐ CHUYỂN VỀ INDEX THAY VÌ SHOW
            return redirect()
                ->route('admin.tang-ca.index')
                ->with('success', '✅ Đã từ chối đơn tăng ca!');
        } catch (\Exception $e) {
            Log::error('❌ Tu choi tang ca error: ' . $e->getMessage());
            return redirect()
                ->route('admin.tang-ca.index')
                ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    /**
     * 📊 Duyệt hàng loạt - Trả về JSON cho AJAX
     */
    public function duyetHangLoat(Request $request)
    {
        try {
            $ids = $request->ids;
            if (is_string($ids)) {
                $ids = json_decode($ids, true);
            }

            if (empty($ids) || !is_array($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có ID nào được chọn'
                ], 400);
            }

            $user = Auth::user();
            $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
            $isTruongPhong = $this->isTruongPhong($user);
            $nhanVienIds = $this->getNhanVienIdsByScope($user);

            $query = DangKyTangCa::whereIn('id', $ids)
                ->where('trang_thai', 'cho_duyet');

            if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            }

            $tangCaList = $query->get();

            if ($tangCaList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có đơn nào ở trạng thái chờ duyệt'
                ], 404);
            }

            $soLuong = 0;
            foreach ($tangCaList as $tangCa) {
                $tangCa->update([
                    'trang_thai' => 'da_duyet',
                    'nguoi_duyet_id' => $user->id,
                    'thoi_gian_duyet' => now(),
                ]);

                $this->notificationService->notifyOvertime($tangCa, 'approved');
                $soLuong++;
            }

            return response()->json([
                'success' => true,
                'message' => "✅ Đã phê duyệt {$soLuong} đơn tăng ca."
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Duyet hang loat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 👁️ Hiển thị form xác nhận hoàn thành tăng ca
     */
    public function showApproveThucHien($id)
    {
        try {
            $user = Auth::user();
            $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
            $isTruongPhong = $this->isTruongPhong($user);
            $nhanVienIds = $this->getNhanVienIdsByScope($user);

            $query = DangKyTangCa::with([
                'nguoi_dung.hoSo',
                'nguoi_dung.phongBan',
                'nguoi_duyet.hoSo',
                'thuc_hien',
            ]);

            if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            }

            $tangCa = $query->findOrFail($id);

            // Tính lương
            $luongCoBan = 0;
            $luongTheoGio = 0;

            if ($tangCa->nguoi_dung) {
                $luongCoBan = SalaryHelper::getBaseSalary($tangCa->nguoi_dung_id);
                $luongTheoGio = $luongCoBan > 0 ? round($luongCoBan / (26 * 8), 0) : 0;
            }

            // Chỉ cho phép xác nhận đơn đã duyệt và đã có xác nhận của nhân viên
            if ($tangCa->trang_thai !== 'da_duyet') {
                return redirect()->route('admin.tang-ca.show', $id)
                    ->with('error', 'Chỉ có thể xác nhận đơn đã duyệt');
            }

            if (!$tangCa->thuc_hien || $tangCa->thuc_hien->trang_thai !== 'nhan_vien_xac_nhan') {
                return redirect()->route('admin.tang-ca.show', $id)
                    ->with('error', 'Nhân viên chưa xác nhận đã làm tăng ca');
            }

            return view('admin.tang-ca.approve-thuc-hien', compact('tangCa', 'luongCoBan', 'luongTheoGio'));
        } catch (\Exception $e) {
            Log::error('❌ showApproveThucHien error: ' . $e->getMessage());
            return redirect()->route('admin.tang-ca.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Quản lý xác nhận hoàn thành tăng ca
     */
    public function approveThucHien(Request $request, $id)
    {
        try {
            $request->validate([
                'so_gio_tang_ca_thuc_te' => 'required|numeric|min:0.5|max:16',
                'cong_viec_da_thuc_hien' => 'nullable|string',
                'ghi_chu' => 'nullable|string',
            ]);

            $tangCa = DangKyTangCa::findOrFail($id);
            $thucHien = ThucHienTangCa::where('dang_ky_tang_ca_id', $tangCa->id)->firstOrFail();

            DB::beginTransaction();

            // Cập nhật thực hiện tăng ca
            $thucHien->update([
                'so_gio_tang_ca_thuc_te' => $request->so_gio_tang_ca_thuc_te,
                'cong_viec_da_thuc_hien' => $request->cong_viec_da_thuc_hien,
                'ghi_chu' => $request->ghi_chu,
                'trang_thai' => 'quan_ly_xac_nhan',
            ]);

            // Tính lương tăng ca
            $userId = $tangCa->nguoi_dung_id;
            $hours = $request->so_gio_tang_ca_thuc_te;
            $type = $tangCa->loai_tang_ca;

            $luongTangCa = SalaryHelper::calculateOvertimeSalary($userId, $hours, $type);

            // Log để debug
            Log::info('💰 Overtime salary calculation:', [
                'user_id' => $userId,
                'hours' => $hours,
                'type' => $type,
                'overtime_salary' => $luongTangCa,
            ]);

            // Lưu lương vào đơn tăng ca
            $tangCa->luong_tang_ca = $luongTangCa;
            $tangCa->da_hoan_thanh = true;
            $tangCa->thoi_gian_hoan_thanh = now();
            $tangCa->save();

            DB::commit();

            return redirect()
                ->route('admin.tang-ca.show', $tangCa->id)
                ->with('success', '✅ Xác nhận hoàn thành tăng ca thành công. Lương: ' . number_format($luongTangCa) . 'đ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ approveThucHien error: ' . $e->getMessage());
            return redirect()
                ->route('admin.tang-ca.show', $id)
                ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

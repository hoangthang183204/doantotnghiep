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
use Illuminate\Support\Facades\DB;      // ⭐ THÊM DÒNG NÀY
use Illuminate\Support\Facades\Log;     // ⭐ THÊM DÒNG NÀY

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
        // Kiểm tra từ chức vụ
        if ($user->chucVu && in_array($user->chucVu->ten, ['Trưởng Phòng', 'Trưởng phòng', 'Quản lý', 'Manager'])) {
            return true;
        }
        // Kiểm tra từ bảng phong_ban
        if (PhongBan::where('truong_phong_id', $user->id)->exists()) {
            return true;
        }
        // Kiểm tra từ vai trò
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

        // Admin: xem tất cả
        if ($isAdmin) {
            return null;
        }

        // Trưởng phòng: lấy ID nhân viên trong phòng
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
     * 📋 Danh sách đăng ký tăng ca - DÙNG CHUNG
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

        // ⭐ LỌC THEO PHÒNG BAN (CHO TRƯỞNG PHÒNG)
        if (!$isAdmin && $isTruongPhong) {
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Lọc theo tên nhân viên
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

        // Lọc theo phòng ban
        if ($request->filled('phong_ban_id')) {
            $query->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban_id);
            });
        }

        // Lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo ngày tăng ca
        if ($request->filled('ngay_tang_ca')) {
            $query->whereDate('ngay_tang_ca', $request->ngay_tang_ca);
        }

        // Lọc từ ngày / đến ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
        }

        // Lấy danh sách
        $donTangCa = $query
            ->orderBy('ngay_tang_ca', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // ⭐⭐⭐ THỐNG KÊ - CẬP NHẬT ĐÚNG SỐ LƯỢNG ⭐⭐⭐
        $thongKeQuery = DangKyTangCa::query();

        // Áp dụng filter phòng ban cho thống kê
        if (!$isAdmin && $isTruongPhong) {
            if (!empty($nhanVienIds)) {
                $thongKeQuery->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                $thongKeQuery->whereRaw('1 = 0');
            }
        }

        // Áp dụng filter tên nhân viên cho thống kê
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

        // Áp dụng filter phòng ban cho thống kê
        if ($request->filled('phong_ban_id')) {
            $thongKeQuery->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban_id);
            });
        }

        // Áp dụng filter ngày tháng cho thống kê
        if ($request->filled('tu_ngay')) {
            $thongKeQuery->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $thongKeQuery->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
        }

        // Tính toán thống kê
        $thongKe = [
            'tong' => (clone $thongKeQuery)->count(),
            'cho_duyet' => (clone $thongKeQuery)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => (clone $thongKeQuery)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => (clone $thongKeQuery)->where('trang_thai', 'tu_choi')->count(),
            'huy' => (clone $thongKeQuery)->where('trang_thai', 'huy')->count(),
        ];

        // Lấy danh sách phòng ban cho bộ lọc
        $phongBans = PhongBan::all();

        // Lấy thông tin phòng ban của trưởng phòng
        $phongBanInfo = null;
        if (!$isAdmin && $isTruongPhong) {
            $phongBanId = $this->getPhongBanId($user);
            if ($phongBanId) {
                $phongBanInfo = PhongBan::find($phongBanId);
            }
        }

        // Xác định view dùng
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
     * 👁️ Chi tiết đơn tăng ca - DÙNG CHUNG
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

        // Kiểm tra quyền xem
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
     * ✅ Phê duyệt đơn tăng ca - DÙNG CHUNG
     */
    public function duyet(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
        $isTruongPhong = $this->isTruongPhong($user);
        $nhanVienIds = $this->getNhanVienIdsByScope($user);

        $query = DangKyTangCa::where('trang_thai', 'cho_duyet');

        // Kiểm tra quyền duyệt
        if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
            $query->whereIn('nguoi_dung_id', $nhanVienIds);
        }

        $dangKy = $query->findOrFail($id);

        try {
            $dangKy->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'approved');

            // ⭐ SỬA: Trả về message tiếng Việt đúng encoding
            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt đơn tăng ca thành công.'
            ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ❌ Từ chối đơn tăng ca - DÙNG CHUNG
     */

    public function tuChoi(Request $request, $id)
    {
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

        try {
            $dangKy->update([
                'trang_thai' => 'tu_choi',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]);

            $this->notificationService->notifyOvertime($dangKy, 'rejected');

            // ⭐ TRẢ VỀ RESPONSE JSON ĐÚNG
            return response()->json([
                'success' => true,
                'message' => '✅ Đã từ chối đơn tăng ca.'
            ], 200, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        }
    }

    /**
     * 📊 Duyệt hàng loạt - DÙNG CHUNG
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
                ], 400)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
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
                ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
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
            ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    // ⭐⭐⭐ THÊM METHOD NÀY ⭐⭐⭐
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

            // Kiểm tra quyền xem
            if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            }

            $tangCa = $query->findOrFail($id);

            // ⭐ KIỂM TRA VÀ TÍNH LƯƠNG THEO GIỜ NẾU CHƯA CÓ
            $luongCoBan = null;
            $luongTheoGio = null;

            if ($tangCa->nguoi_dung) {
                $luongCoBan = SalaryHelper::getBaseSalary($tangCa->nguoi_dung_id);
                // Tính lương theo giờ từ lương cơ bản: lương cơ bản / (26 ngày * 8 giờ)
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
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.tang-ca.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }




    // ... trong hàm approveThucHien() ...

    public function approveThucHien(Request $request, $id)
    {
        $request->validate([
            'so_gio_tang_ca_thuc_te' => 'required|numeric|min:0.5|max:16',
            'cong_viec_da_thuc_hien' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Lấy đơn tăng ca
            $tangCa = DangKyTangCa::findOrFail($id);

            // Lấy thực hiện tăng ca
            $thucHien = ThucHienTangCa::where('dang_ky_tang_ca_id', $tangCa->id)->firstOrFail();

            // Cập nhật thực hiện tăng ca
            $thucHien->update([
                'so_gio_tang_ca_thuc_te' => $request->so_gio_tang_ca_thuc_te,
                'cong_viec_da_thuc_hien' => $request->cong_viec_da_thuc_hien,
                'ghi_chu' => $request->ghi_chu,
                'trang_thai' => 'quan_ly_xac_nhan',
            ]);

            // ⭐ TÍNH LƯƠNG TĂNG CA SỬ DỤNG HELPER
            $userId = $tangCa->nguoi_dung_id;
            $hours = $request->so_gio_tang_ca_thuc_te;
            $type = $tangCa->loai_tang_ca;

            $luongTangCa = SalaryHelper::calculateOvertimeSalary($userId, $hours, $type);

            // Log để debug
            Log::info('💰 Overtime salary calculation:', [
                'user_id' => $userId,
                'hours' => $hours,
                'type' => $type,
                'hourly_rate' => SalaryHelper::getHourlyRate($userId),
                'base_salary' => SalaryHelper::getBaseSalary($userId),
                'overtime_salary' => $luongTangCa,
            ]);

            // Lưu lương vào đơn tăng ca
            $tangCa->luong_tang_ca = $luongTangCa;
            $tangCa->da_hoan_thanh = true;
            $tangCa->thoi_gian_hoan_thanh = now();
            $tangCa->save();

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.tang-ca.show', $tangCa->id)
                ->with('success', '✅ Xác nhận hoàn thành tăng ca thành công. Lương: ' . number_format($luongTangCa) . 'đ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ approveThucHien error: ' . $e->getMessage());
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.tang-ca.show', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

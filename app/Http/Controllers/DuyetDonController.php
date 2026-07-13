<?php
// app/Http/Controllers/DuyetDonController.php

namespace App\Http\Controllers;

use App\Models\DonXinNghi;
use App\Models\NguoiDung;
use App\Models\LoaiNghiPhep;
use App\Models\PhongBan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DuyetDonController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * ⭐ Xác định scope dữ liệu dựa trên role
     */
    private function getScope(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
        $isTruongPhong = $this->isTruongPhong($user);

        return [
            'isAdmin' => $isAdmin,
            'isTruongPhong' => $isTruongPhong,
            'user' => $user,
            'phongBanId' => $isTruongPhong ? $this->getPhongBanId($user) : null,
        ];
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
     * ⭐ Lấy ID phòng ban của trưởng phòng
     */
    private function getPhongBanId($user)
    {
        // Từ user
        if ($user->phong_ban_id) {
            return $user->phong_ban_id;
        }

        // Từ bảng phong_ban
        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        if ($phongBan) {
            return $phongBan->id;
        }

        return null;
    }

    /**
     * ⭐ Lấy danh sách ID nhân viên trong phòng
     */
    private function getNhanVienIdsInPhong($phongBanId)
    {
        if (!$phongBanId) {
            return [];
        }

        return NguoiDung::where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->pluck('id')
            ->toArray();
    }

    /**
     * 📋 DANH SÁCH ĐƠN - DÙNG CHUNG
     * - Admin: Xem tất cả
     * - Trưởng phòng: Chỉ xem đơn của nhân viên trong phòng
     */
    public function index(Request $request)
    {
        $scope = $this->getScope($request);
        $user = $scope['user'];
        $isAdmin = $scope['isAdmin'];
        $isTruongPhong = $scope['isTruongPhong'];
        $phongBanId = $scope['phongBanId'];

        $query = DonXinNghi::with([
            'nguoiDung.hoSo',
            'nguoiDung.phongBan',
            'loaiNghiPhep',
            'nguoiDuyet.hoSo'
        ]);

        // ⭐ LỌC THEO PHÒNG BAN (CHO TRƯỞNG PHÒNG)
        if ($isTruongPhong && !$isAdmin) {
            $nhanVienIds = $this->getNhanVienIdsInPhong($phongBanId);
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                $query->whereRaw('1 = 0'); // Không có nhân viên nào
            }
        }

        // BỘ LỌC
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('loai_nghi_id')) {
            $query->where('loai_nghi_phep_id', $request->loai_nghi_id);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_ket_thuc', '<=', $request->den_ngay);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ma_don_nghi', 'like', "%{$keyword}%")
                    ->orWhereHas('nguoiDung', function ($sub) use ($keyword) {
                        $sub->where('ten_dang_nhap', 'like', "%{$keyword}%")
                            ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                                $hs->where('ho', 'like', "%{$keyword}%")
                                    ->orWhere('ten', 'like', "%{$keyword}%")
                                    ->orWhere('ma_nhan_vien', 'like', "%{$keyword}%")
                                    ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                            });
                    });
            });
        }

        $danhSach = $query->orderBy('created_at', 'desc')->paginate(15);

        // THỐNG KÊ
        $statsQuery = clone $query;
        $thongKe = [
            'tong' => DonXinNghi::count(),
            'cho_duyet' => DonXinNghi::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DonXinNghi::where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DonXinNghi::where('trang_thai', 'tu_choi')->count(),
        ];

        // Nếu là trưởng phòng, thống kê trong phòng
        if ($isTruongPhong && !$isAdmin && !empty($nhanVienIds)) {
            $thongKe = [
                'tong' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)->count(),
                'cho_duyet' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)->where('trang_thai', 'cho_duyet')->count(),
                'da_duyet' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)->where('trang_thai', 'da_duyet')->count(),
                'tu_choi' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)->where('trang_thai', 'tu_choi')->count(),
            ];
        }

        $loaiNghiPheps = LoaiNghiPhep::where('trang_thai', 1)->get();

        // Xác định view
        $view = $isAdmin ? 'admin.don-nghi.index' : 'truong-phong.don-nghi.index';

        return view($view, compact('danhSach', 'thongKe', 'loaiNghiPheps', 'isAdmin', 'isTruongPhong'));
    }

    /**
     * 👁️ CHI TIẾT ĐƠN - DÙNG CHUNG
     */
    public function show($id)
    {
        $scope = $this->getScope(request());
        $user = $scope['user'];
        $isAdmin = $scope['isAdmin'];
        $isTruongPhong = $scope['isTruongPhong'];
        $phongBanId = $scope['phongBanId'];

        $query = DonXinNghi::with([
            'nguoiDung.hoSo',
            'nguoiDung.phongBan',
            'nguoiDung.chucVu',
            'loaiNghiPhep',
            'nguoiDuyet.hoSo'
        ]);

        // ⭐ KIỂM TRA QUYỀN XEM
        if ($isTruongPhong && !$isAdmin) {
            $nhanVienIds = $this->getNhanVienIdsInPhong($phongBanId);
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                abort(403, 'Bạn không có quyền xem đơn này.');
            }
        }

        $donNghi = $query->findOrFail($id);

        $view = $isAdmin ? 'admin.don-nghi.show' : 'truong-phong.don-nghi.show';

        return view($view, compact('donNghi', 'isAdmin'));
    }

    /**
     * ✅ DUYỆT ĐƠN - DÙNG CHUNG
     */
   // app/Http/Controllers/DuyetDonController.php

    /**
     * ✅ DUYỆT ĐƠN - Trả về JSON cho AJAX
     */
    public function duyet(Request $request, $id)
    {
        $scope = $this->getScope($request);
        $user = $scope['user'];
        $isAdmin = $scope['isAdmin'];
        $isTruongPhong = $scope['isTruongPhong'];
        $phongBanId = $scope['phongBanId'];

        $query = DonXinNghi::where('trang_thai', 'cho_duyet');

        if ($isTruongPhong && !$isAdmin) {
            $nhanVienIds = $this->getNhanVienIdsInPhong($phongBanId);
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền duyệt đơn này.'
                ], 403);
            }
        }

        $donNghi = $query->findOrFail($id);

        DB::beginTransaction();
        try {
            $donNghi->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
            ]);

            $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;
            $soDuPhep = \App\Models\SoDuPhep::firstOrCreate(
                ['nguoi_dung_id' => $donNghi->nguoi_dung_id, 'nam' => $namDonNghi],
                ['phep_nam_moi' => 12.0, 'phep_cu_chuyen_sang' => 0.0, 'phep_da_dung' => 0.0]
            );
            $soDuPhep->increment('phep_da_dung', $donNghi->so_ngay_nghi);

            $this->notificationService->notifyLeaveRequest($donNghi, 'approved');

            DB::commit();

            // 🔥 SỬA: Trả về JSON thay vì redirect
            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt đơn nghỉ phép thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ❌ TỪ CHỐI ĐƠN - Trả về JSON cho AJAX
     */
    public function tuChoi(Request $request, $id)
    {
        $request->validate([
            'ly_do' => 'required|string|max:500'
        ]);

        $scope = $this->getScope($request);
        $user = $scope['user'];
        $isAdmin = $scope['isAdmin'];
        $isTruongPhong = $scope['isTruongPhong'];
        $phongBanId = $scope['phongBanId'];

        $query = DonXinNghi::where('trang_thai', 'cho_duyet');

        if ($isTruongPhong && !$isAdmin) {
            $nhanVienIds = $this->getNhanVienIdsInPhong($phongBanId);
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền từ chối đơn này.'
                ], 403);
            }
        }

        $donNghi = $query->findOrFail($id);

        $donNghi->update([
            'trang_thai' => 'tu_choi',
            'nguoi_duyet_id' => $user->id,
            'thoi_gian_duyet' => now(),
            'ghi_chu' => $request->ly_do,
        ]);

        $this->notificationService->notifyLeaveRequest($donNghi, 'rejected');

        // 🔥 SỬA: Trả về JSON thay vì redirect
        return response()->json([
            'success' => true,
            'message' => '✅ Đã từ chối đơn nghỉ phép.'
        ]);
    }

    /**
     * 📊 DUYỆT HÀNG LOẠT - DÙNG CHUNG
     */
    public function duyetHangLoat(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:don_xin_nghi,id',
            'action' => 'required|in:da_duyet,tu_choi'
        ]);

        $scope = $this->getScope($request);
        $user = $scope['user'];
        $isAdmin = $scope['isAdmin'];
        $isTruongPhong = $scope['isTruongPhong'];
        $phongBanId = $scope['phongBanId'];

        $query = DonXinNghi::whereIn('id', $request->ids)
            ->where('trang_thai', 'cho_duyet');

        if ($isTruongPhong && !$isAdmin) {
            $nhanVienIds = $this->getNhanVienIdsInPhong($phongBanId);
            if (!empty($nhanVienIds)) {
                $query->whereIn('nguoi_dung_id', $nhanVienIds);
            } else {
                return response()->json(['success' => false, 'message' => 'Không có đơn nào để duyệt.'], 403);
            }
        }

        $donNghiList = $query->get();

        if ($donNghiList->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có đơn nào ở trạng thái chờ duyệt.']);
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($donNghiList as $donNghi) {
                $donNghi->update([
                    'trang_thai' => $request->action,
                    'nguoi_duyet_id' => $user->id,
                    'thoi_gian_duyet' => now(),
                ]);

                if ($request->action == 'da_duyet') {
                    $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;
                    $soDuPhep = \App\Models\SoDuPhep::firstOrCreate(
                        ['nguoi_dung_id' => $donNghi->nguoi_dung_id, 'nam' => $namDonNghi],
                        ['phep_nam_moi' => 12.0, 'phep_cu_chuyen_sang' => 0.0, 'phep_da_dung' => 0.0]
                    );
                    $soDuPhep->increment('phep_da_dung', $donNghi->so_ngay_nghi);
                }

                $action = $request->action === 'da_duyet' ? 'approved' : 'rejected';
                $this->notificationService->notifyLeaveRequest($donNghi, $action);
                $count++;
            }

            DB::commit();

            $message = $request->action == 'da_duyet' ? 'duyệt' : 'từ chối';
            return response()->json([
                'success' => true,
                'message' => "✅ Đã {$message} {$count} đơn thành công!"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}

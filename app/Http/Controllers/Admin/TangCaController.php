<?php
// app/Http/Controllers/Admin/TangCaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Thống kê
        $thongKe = [
            'tong' => DangKyTangCa::count(),
            'cho_duyet' => DangKyTangCa::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DangKyTangCa::where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DangKyTangCa::where('trang_thai', 'tu_choi')->count(),
        ];

        // Nếu là trưởng phòng, thống kê trong phòng
        if (!$isAdmin && $isTruongPhong && !empty($nhanVienIds)) {
            $thongKe = [
                'tong' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)->count(),
                'cho_duyet' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)->where('trang_thai', 'cho_duyet')->count(),
                'da_duyet' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)->where('trang_thai', 'da_duyet')->count(),
                'tu_choi' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)->where('trang_thai', 'tu_choi')->count(),
            ];
        }

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
    /**
     * 👁️ Chi tiết đơn tăng ca - DÙNG CHUNG
     */
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

        // ⭐ Lấy dữ liệu và đặt tên biến là $tangCa cho cả 2 view
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

        $dangKy->update([
            'trang_thai' => 'da_duyet',
            'nguoi_duyet_id' => $user->id,
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi' => null,
        ]);

        $this->notificationService->notifyOvertime($dangKy, 'approved');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã duyệt đơn tăng ca!']);
        }
        return redirect()->back()->with('success', '✅ Đã duyệt đơn tăng ca.');
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

        $dangKy->update([
            'trang_thai' => 'tu_choi',
            'nguoi_duyet_id' => $user->id,
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
        ]);

        $this->notificationService->notifyOvertime($dangKy, 'rejected');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã từ chối đơn tăng ca!']);
        }
        return redirect()->back()->with('success', '✅ Đã từ chối đơn tăng ca.');
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
                ]);
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
            return response()->json([
                'success' => false,
                'message' => '❌ Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}

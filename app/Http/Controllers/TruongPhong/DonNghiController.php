<?php
// app/Http/Controllers/TruongPhong/DonNghiController.php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\DonXinNghi;
use App\Models\NguoiDung;
use App\Services\NotificationService;
use App\Notifications\LeaveRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DonNghiController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Danh sách đơn nghỉ của nhân viên trong phòng ban
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Lấy danh sách nhân viên trong phòng ban của trưởng phòng
        $nhanVienIds = NguoiDung::where('phong_ban_id', $user->phong_ban_id)
            ->where('id', '!=', $user->id)
            ->pluck('id')
            ->toArray();

        $query = DonXinNghi::with(['nguoiDung.hoSo', 'loaiNghiPhep'])
            ->whereIn('nguoi_dung_id', $nhanVienIds);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_bat_dau', '<=', $request->den_ngay);
        }

        $danhSachDon = $query->orderBy('created_at', 'desc')->paginate(20);

        $thongKe = [
            'tong' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)->count(),
            'cho_duyet' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                ->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                ->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                ->where('trang_thai', 'tu_choi')->count(),
        ];

        // ✅ ĐỔI TÊN BIẾN THÀNH $danhSach ĐỂ KHỚP VỚI VIEW
        $danhSach = $danhSachDon;

        return view('truong-phong.don-nghi.index', compact('danhSach', 'thongKe'));
    }

    /**
     * Chi tiết đơn nghỉ
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $donNghi = DonXinNghi::with(['nguoiDung.hoSo', 'loaiNghiPhep'])
            ->findOrFail($id);

        // Kiểm tra nhân viên có thuộc phòng ban của trưởng phòng không
        if ($donNghi->nguoiDung->phong_ban_id != $user->phong_ban_id) {
            abort(403, 'Bạn không có quyền xem đơn này');
        }

        return view('truong-phong.don-nghi.show', compact('donNghi'));
    }

    /**
     * Duyệt đơn nghỉ
     */
    public function duyet($id)
    {
        try {
            $user = Auth::user();
            $donNghi = DonXinNghi::findOrFail($id);

            // Kiểm tra quyền
            if ($donNghi->nguoiDung->phong_ban_id != $user->phong_ban_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền duyệt đơn này'
                ], 403);
            }

            if ($donNghi->trang_thai != 'cho_duyet') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn này đã được xử lý'
                ], 400);
            }

            $donNghi->trang_thai = 'da_duyet';
            $donNghi->nguoi_duyet_id = $user->id;
            $donNghi->thoi_gian_duyet = now();
            $donNghi->save();

            // Gửi thông báo cho nhân viên
            $this->notificationService->sendToUser(
                $donNghi->nguoiDung,
                new LeaveRequestNotification($donNghi, 'approved')
            );

            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt đơn nghỉ phép!'
            ]);

        } catch (\Exception $e) {
            Log::error('Duyệt đơn nghỉ lỗi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Từ chối đơn nghỉ
     */
    public function tuChoi(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $donNghi = DonXinNghi::findOrFail($id);

            $request->validate([
                'ly_do_tu_choi' => 'required|string|min:10'
            ]);

            // Kiểm tra quyền
            if ($donNghi->nguoiDung->phong_ban_id != $user->phong_ban_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền từ chối đơn này'
                ], 403);
            }

            if ($donNghi->trang_thai != 'cho_duyet') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn này đã được xử lý'
                ], 400);
            }

            $donNghi->trang_thai = 'tu_choi';
            $donNghi->nguoi_duyet_id = $user->id;
            $donNghi->thoi_gian_duyet = now();
            $donNghi->ghi_chu = $request->ly_do_tu_choi;
            $donNghi->save();

            // Gửi thông báo cho nhân viên
            $this->notificationService->sendToUser(
                $donNghi->nguoiDung,
                new LeaveRequestNotification($donNghi, 'rejected')
            );

            return response()->json([
                'success' => true,
                'message' => '❌ Đã từ chối đơn nghỉ phép!'
            ]);

        } catch (\Exception $e) {
            Log::error('Từ chối đơn nghỉ lỗi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
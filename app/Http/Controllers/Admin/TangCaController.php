<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Services\NotificationService;
use App\Models\PhongBan;
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
     * Danh sách đăng ký tăng ca
     */
    public function index(Request $request)
    {
        $query = DangKyTangCa::with(['nguoi_dung.hoSo', 'nguoi_dung.phongBan', 'nguoi_duyet.hoSo', 'thuc_hien']);

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

        // Thống kê nhanh
        $soLuongDangKyTangCa = DangKyTangCa::count();
        $trangThaiThongKe = [
            'cho_duyet' => DangKyTangCa::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet'  => DangKyTangCa::where('trang_thai', 'da_duyet')->count(),
            'tu_choi'   => DangKyTangCa::where('trang_thai', 'tu_choi')->count(),
        ];

        // Lấy danh sách phòng ban cho bộ lọc
        $phongBans = PhongBan::all();

        return view('admin.tang-ca.index', compact(
            'donTangCa',           // Tên biến này phải khớp với view
            'soLuongDangKyTangCa',
            'trangThaiThongKe',
            'phongBans'
        ));
    }

    /**
     * Chi tiết đơn tăng ca
     */
    public function show($id)
    {
        $dangKy = DangKyTangCa::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'nguoi_duyet.hoSo',
            'thuc_hien',
        ])->findOrFail($id);

        return view('admin.tang-ca.show', compact('dangKy'));
    }

    /**
     * Phê duyệt đơn tăng ca
     */
    public function duyet(Request $request, $id)
    {
        $dangKy = DangKyTangCa::findOrFail($id);

        if ($dangKy->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Đơn này không ở trạng thái chờ duyệt.');
        }

        $dangKy->update([
            'trang_thai'      => 'da_duyet',
            'nguoi_duyet_id'  => Auth::id(),
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi'   => null,
        ]);

        // ⭐ GỬI THÔNG BÁO CHO NHÂN VIÊN
        $this->notificationService->notifyOvertime($dangKy, 'approved');

        return redirect()->back()->with('success', 'Đã phê duyệt đơn tăng ca thành công.');
    }

    /**
     * Từ chối đơn tăng ca
     */
    public function tuChoi(Request $request, $id)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ]);

        $dangKy = DangKyTangCa::findOrFail($id);

        if ($dangKy->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Đơn này không ở trạng thái chờ duyệt.');
        }

        $dangKy->update([
            'trang_thai'      => 'tu_choi',
            'nguoi_duyet_id'  => Auth::id(),
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi'   => $request->ly_do_tu_choi,
        ]);

        // ⭐ GỬI THÔNG BÁO CHO NHÂN VIÊN
        $this->notificationService->notifyOvertime($dangKy, 'rejected');

        return redirect()->back()->with('success', 'Đã từ chối đơn tăng ca.');
    }

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

            $tangCaList = DangKyTangCa::whereIn('id', $ids)
                ->where('trang_thai', 'cho_duyet')
                ->get();

            $soLuong = 0;
            foreach ($tangCaList as $tangCa) {
                $tangCa->update([
                    'trang_thai'      => 'da_duyet',
                    'nguoi_duyet_id'  => Auth::id(),
                    'thoi_gian_duyet' => now(),
                ]);

                // ⭐ GỬI THÔNG BÁO CHO TỪNG NHÂN VIÊN
                $this->notificationService->notifyOvertime($tangCa, 'approved');
                $soLuong++;
            }

            if ($soLuong == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có đơn nào ở trạng thái chờ duyệt'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Đã phê duyệt {$soLuong} đơn tăng ca."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}

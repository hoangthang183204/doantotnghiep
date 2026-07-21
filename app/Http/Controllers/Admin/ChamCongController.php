<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\PhongBan;
use App\Models\DonXinVeSom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChamCongController extends Controller
{
    /**
     * Danh sách chấm công
     */
    public function index(Request $request)
    {
        $query = ChamCong::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan'
        ]);

        $this->applyFilters($query, $request);

        $chamCongs = $query
            ->orderBy('ngay_cham_cong', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->appends($request->query());

        // ========== THỐNG KÊ ==========
        $tongSoBanGhi = ChamCong::count();
        $tongDungGio = ChamCong::where('trang_thai', 'dung_gio')->count();
        $tyLeDungGio = $tongSoBanGhi > 0 ? round(($tongDungGio / $tongSoBanGhi) * 100) : 0;
        $homNay = ChamCong::whereDate('ngay_cham_cong', Carbon::today())->count();
        $diMuonHomNay = ChamCong::whereDate('ngay_cham_cong', Carbon::today())
            ->where('trang_thai', 'di_muon')
            ->count();
        
        // ⭐ THỐNG KÊ ĐƠN XIN VỀ SỚM
        $donVeSomChoDuyet = DonXinVeSom::where('trang_thai', 'cho_duyet')->count();
        $donVeSomDaDuyet = DonXinVeSom::where('trang_thai', 'da_duyet')->count();

        $phongBan = PhongBan::all();

        return view('admin.cham-cong.index', compact(
            'chamCongs',
            'tongSoBanGhi',
            'tyLeDungGio',
            'homNay',
            'diMuonHomNay',
            'phongBan',
            'donVeSomChoDuyet',
            'donVeSomDaDuyet'
        ));
    }

    /**
     * Chi tiết chấm công
     */
    public function show($id)
    {
        $chamCong = ChamCong::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan'
        ])->findOrFail($id);

        return view('admin.cham-cong.show', compact('chamCong'));
    }

    /**
     * Danh sách đơn xin về sớm
     */
    public function danhSachDonVeSom(Request $request)
    {
        $query = DonXinVeSom::with([
            'nguoiDung.hoSo',
            'nguoiDung.phongBan',
            'chamCong'
        ]);

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay', '<=', $request->den_ngay);
        }

        // Lọc theo nhân viên
        if ($request->filled('ten_nhan_vien')) {
            $keyword = trim($request->ten_nhan_vien);
            $query->whereHas('nguoiDung', function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                    });
            });
        }

        $donVeSoms = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê
        $soChoDuyet = DonXinVeSom::where('trang_thai', 'cho_duyet')->count();
        $soDaDuyet = DonXinVeSom::where('trang_thai', 'da_duyet')->count();
        $soTuChoi = DonXinVeSom::where('trang_thai', 'tu_choi')->count();

        return view('admin.cham-cong.don-ve-som', compact(
            'donVeSoms',
            'soChoDuyet',
            'soDaDuyet',
            'soTuChoi'
        ));
    }

    /**
     * Duyệt đơn xin về sớm
     */
    public function duyetDonVeSom($id)
    {
        try {
            $don = DonXinVeSom::findOrFail($id);
            
            if ($don->trang_thai != 'cho_duyet') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn này đã được xử lý!'
                ], 400);
            }

            $don->trang_thai = 'da_duyet';
            $don->nguoi_duyet_id = auth()->id();
            $don->thoi_gian_duyet = now();
            $don->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt đơn xin về sớm!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Từ chối đơn xin về sớm
     */
    public function tuChoiDonVeSom(Request $request, $id)
    {
        try {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|min:10'
            ]);

            $don = DonXinVeSom::findOrFail($id);
            
            if ($don->trang_thai != 'cho_duyet') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn này đã được xử lý!'
                ], 400);
            }

            $don->trang_thai = 'tu_choi';
            $don->ly_do_tu_choi = $request->ly_do_tu_choi;
            $don->nguoi_duyet_id = auth()->id();
            $don->thoi_gian_duyet = now();
            $don->save();

            return response()->json([
                'success' => true,
                'message' => '❌ Đã từ chối đơn xin về sớm!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xuất CSV
     */
    public function export(Request $request)
    {
        $query = ChamCong::with([
            'nguoi_dung.hoSo'
        ]);

        $this->applyFilters($query, $request);

        $fileName = 'cham_cong_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nhân viên',
                'Ngày chấm công',
                'Giờ vào',
                'Giờ ra',
                'Số giờ làm',
                'Số công',
                'Tăng ca',
                'Đi muộn',
                'Về sớm',
                'Trạng thái',
            ]);

            $query->chunk(500, function ($records) use ($file) {
                foreach ($records as $item) {
                    $hoTen = $item->nguoi_dung->hoSo
                        ? trim(($item->nguoi_dung->hoSo->ho ?? '') . ' ' . ($item->nguoi_dung->hoSo->ten ?? ''))
                        : ($item->nguoi_dung->ten_dang_nhap ?? 'N/A');

                    if (empty($hoTen)) {
                        $hoTen = 'NV#' . ($item->nguoi_dung_id ?? '?');
                    }

                    fputcsv($file, [
                        $item->id,
                        $hoTen,
                        optional($item->ngay_cham_cong)->format('d/m/Y'),
                        $item->gio_vao,
                        $item->gio_ra,
                        $item->so_gio_lam,
                        $item->so_cong,
                        $item->gio_tang_ca,
                        $item->phut_di_muon,
                        $item->phut_ve_som,
                        $item->trang_thai,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bộ lọc chung
     */
    private function applyFilters($query, Request $request): void
    {
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

        if ($request->filled('ngay_cham_cong')) {
            $query->whereDate('ngay_cham_cong', $request->ngay_cham_cong);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_cham_cong', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_cham_cong', '<=', $request->den_ngay);
        }

        if ($request->filled('thang')) {
            $query->whereMonth('ngay_cham_cong', $request->thang);
        }

        if ($request->filled('nam')) {
            $query->whereYear('ngay_cham_cong', $request->nam);
        }
    }
}
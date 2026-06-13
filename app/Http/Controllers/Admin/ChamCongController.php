<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\PhongBan;
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
        // Tổng số bản ghi (không bị lọc)
        $tongSoBanGhi = ChamCong::count();
        
        // Tỷ lệ đúng giờ
        $tongDungGio = ChamCong::where('trang_thai', 'dung_gio')->count();
        $tyLeDungGio = $tongSoBanGhi > 0 ? round(($tongDungGio / $tongSoBanGhi) * 100) : 0;
        
        // Hôm nay
        $homNay = ChamCong::whereDate('ngay_cham_cong', Carbon::today())->count();
        
        // Chờ phê duyệt (trang_thai_duyet = 3)
        $donDuyet = ChamCong::where('trang_thai_duyet', 3)->count();
        
        // Danh sách phòng ban cho bộ lọc
        $phongBan = PhongBan::all();

        return view('admin.cham-cong.index', compact(
            'chamCongs', 
            'tongSoBanGhi', 
            'tyLeDungGio', 
            'homNay', 
            'donDuyet',
            'phongBan'
        ));
    }

    /**
     * Chi tiết chấm công
     */
    public function show($id)
    {
        $chamCong = ChamCong::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'nguoi_phe_duyet'
        ])->findOrFail($id);

        return view('admin.cham-cong.show', compact('chamCong'));
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
                        ? $item->nguoi_dung->hoSo->ho . ' ' . $item->nguoi_dung->hoSo->ten
                        : $item->nguoi_dung->ten_dang_nhap;

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

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo trạng thái duyệt
        if ($request->filled('trang_thai_duyet')) {
            $query->where('trang_thai_duyet', $request->trang_thai_duyet);
        }

        // Lọc theo ngày
        if ($request->filled('ngay_cham_cong')) {
            $query->whereDate('ngay_cham_cong', $request->ngay_cham_cong);
        }

        // Lọc theo khoảng ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_cham_cong', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_cham_cong', '<=', $request->den_ngay);
        }

        // Lọc theo tháng
        if ($request->filled('thang')) {
            $query->whereMonth('ngay_cham_cong', $request->thang);
        }

        // Lọc theo năm
        if ($request->filled('nam')) {
            $query->whereYear('ngay_cham_cong', $request->nam);
        }
    }

    /**
     * Phê duyệt hàng loạt
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:cham_cong,id',
            'action' => 'required|in:1,2,4',
            'reason' => 'nullable|string'
        ]);

        $ids = is_array($request->ids) ? $request->ids : json_decode($request->ids, true);
        $action = $request->action;
        $reason = $request->reason;

        $count = ChamCong::whereIn('id', $ids)->update([
            'trang_thai_duyet' => $action,
            'ghi_chu_duyet' => $reason,
            'nguoi_phe_duyet_id' => auth()->id(),
            'thoi_gian_phe_duyet' => now(),
        ]);

        $message = match ($action) {
            1 => 'Phê duyệt',
            2 => 'Từ chối',
            4 => 'Hủy',
            default => 'Cập nhật'
        };

        return response()->json([
            'success' => true,
            'message' => "{$message} {$count} bản ghi thành công!",
            'affected_count' => $count
        ]);
    }

    /**
     * Phê duyệt đơn lẻ
     */
    public function pheDuyetDonLe(Request $request, $id)
    {
        $request->validate([
            'trang_thai_duyet' => 'required|in:1,2,4',
            'ghi_chu_phe_duyet' => 'nullable|string'
        ]);

        $chamCong = ChamCong::findOrFail($id);
        
        $chamCong->update([
            'trang_thai_duyet' => $request->trang_thai_duyet,
            'ghi_chu_duyet' => $request->ghi_chu_phe_duyet,
            'nguoi_phe_duyet_id' => auth()->id(),
            'thoi_gian_phe_duyet' => now(),
        ]);

        $message = match ($request->trang_thai_duyet) {
            1 => 'Phê duyệt',
            2 => 'Từ chối',
            4 => 'Hủy',
            default => 'Cập nhật'
        };

        return redirect()->back()->with('success', "{$message} thành công!");
    }
}
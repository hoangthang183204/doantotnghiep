<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use Illuminate\Http\Request;

class ChamCongController extends Controller
{
    /**
     * Danh sách chấm công
     */
    public function index(Request $request)
    {
        $query = ChamCong::with([
            'nguoi_dung.hoSo'
        ]);

        $this->applyFilters($query, $request);

        $chamCongs = $query
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.cham-cong.index', compact('chamCongs'));
    }

    /**
     * Chi tiết chấm công
     */
    public function show($id)
    {
        $chamCong = ChamCong::with([
            'nguoi_dung.hoSo',
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
        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->whereHas('nguoi_dung', function ($q) use ($keyword) {

                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {

                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw(
                                "CONCAT(ho, ' ', ten) LIKE ?",
                                ["%{$keyword}%"]
                            );
                    });
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('ngay_cham_cong')) {
            $query->whereDate(
                'ngay_cham_cong',
                $request->ngay_cham_cong
            );
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate(
                'ngay_cham_cong',
                '>=',
                $request->tu_ngay
            );
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate(
                'ngay_cham_cong',
                '<=',
                $request->den_ngay
            );
        }
    }
}

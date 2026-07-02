<?php

namespace App\Exports;

use App\Models\KhenThuongKyLuatNhanVien;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KhenThuongKyLuatExport implements FromView
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'nguoiKy'
        ]);

        // ===== apply filter giống index =====
        if (!empty($this->filters['search'])) {
            $keyword = $this->filters['search'];

            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($keyword) {
                $q->where('ma_nhan_vien', 'like', "%$keyword%")
                  ->orWhere('ho', 'like', "%$keyword%")
                  ->orWhere('ten', 'like', "%$keyword%");
            });
        }

        if (!empty($this->filters['loai'])) {
            $query->where('loai', $this->filters['loai']);
        }

        if (!empty($this->filters['phong_ban'])) {
            $query->whereHas('hoSo.nguoi_dung', function ($q) {
                $q->where('phong_ban_id', $this->filters['phong_ban']);
            });
        }

        if (!empty($this->filters['thang'])) {
            $query->whereMonth('ngay', $this->filters['thang']);
        }

        if (!empty($this->filters['nam'])) {
            $query->whereYear('ngay', $this->filters['nam']);
        }

        $data = $query->orderByDesc('ngay')->get();

        return view('admin.khen-thuong-ky-luat.export', [
            'data' => $data
        ]);
    }
}
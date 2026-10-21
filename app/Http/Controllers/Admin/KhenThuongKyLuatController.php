<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Models\KhenThuongKyLuatNhanVien;
use App\Models\LuongNhanVien;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhenThuongKyLuatExport;

class KhenThuongKyLuatController extends Controller
{
    /**
     * Danh sách khen thưởng / kỷ luật
     */
    public function index(Request $request)
    {
        $query = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'nguoiKy'
        ]);

        // Tìm kiếm theo tên hoặc mã nhân viên
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($keyword) {
                $q->where('ma_nhan_vien', 'like', "%$keyword%")
                    ->orWhere('ho', 'like', "%$keyword%")
                    ->orWhere('ten', 'like', "%$keyword%")
                    ->orWhereRaw("CONCAT(ho,' ',ten) LIKE ?", ["%$keyword%"]);
            });
        }

        // Lọc theo loại
        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        // Lọc theo phòng ban
        if ($request->filled('phong_ban')) {
            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban);
            });
        }

        // Lọc theo tháng
        if ($request->filled('thang')) {
            $query->whereMonth('ngay', $request->thang);
        }

        // Lọc theo năm
        if ($request->filled('nam')) {
            $query->whereYear('ngay', $request->nam);
        }

        $ds = (clone $query)
            ->latest('ngay')
            ->paginate(10)
            ->withQueryString();

        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        // Thống kê
        $tongQuyetDinh = (clone $query)->count();
        $tongKhenThuong = (clone $query)->where('loai', 'khen_thuong')->count();
        $tongKyLuat = (clone $query)->where('loai', 'ky_luat')->count();
        $tongTienThuong = (clone $query)->where('loai', 'khen_thuong')->sum('so_tien');
        $tongTienPhat = (clone $query)->where('loai', 'ky_luat')->sum('so_tien');

        return view('admin.khen-thuong-ky-luat.index', compact(
            'ds',
            'phongBans',
            'tongQuyetDinh',
            'tongKhenThuong',
            'tongKyLuat',
            'tongTienThuong',
            'tongTienPhat'
        ));
    }

    /**
     * Form tạo khen thưởng
     */
    public function createKhenThuong()
    {
        $hoSos = HoSo::with('nguoi_dung.phongBan')
            ->whereHas('nguoi_dung', function ($q) {
                $q->where('trang_thai_cong_viec', 'dang_lam');
            })
            ->orderBy('ho')
            ->orderBy('ten')
            ->get();

        return view('admin.khen-thuong-ky-luat.create_khen_thuong', compact('hoSos'));
    }

    /**
     * Form tạo kỷ luật
     */
    public function createKyLuat()
    {
        $hoSos = HoSo::with('nguoi_dung.phongBan')
            ->whereHas('nguoi_dung', function ($q) {
                $q->where('trang_thai_cong_viec', 'dang_lam');
            })
            ->orderBy('ho')
            ->orderBy('ten')
            ->get();

        return view('admin.khen-thuong-ky-luat.create_ky_luat', compact('hoSos'));
    }

    /**
     * Lưu khen thưởng
     */
    public function storeKhenThuong(Request $request)
    {
        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|string|max:255',
            'ngay' => 'required|date',
            'muc_do' => 'required|in:kha,gioi,xuat_sac',
            'so_tien' => 'nullable|numeric|min:0',
            'hinh_thuc' => 'nullable|string|max:255',
            'quyet_dinh_so' => 'nullable|string|max:255',
            'noi_dung' => 'nullable|string',
            'bang_chung' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'quyet_dinh_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data['loai'] = 'khen_thuong';
        $data['nguoi_ky_id'] = auth()->id(); // Tự động lấy người đang đăng nhập

        if ($request->hasFile('bang_chung')) {
            $data['bang_chung'] = $request->file('bang_chung')->store('bang-chung', 'public');
        }

        if ($request->hasFile('quyet_dinh_file')) {
            $data['quyet_dinh_file'] = $request->file('quyet_dinh_file')->store('quyet-dinh', 'public');
        }

        KhenThuongKyLuatNhanVien::create($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Thêm khen thưởng thành công');
    }

    /**
     * Lưu kỷ luật
     */
    public function storeKyLuat(Request $request)
    {
        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|string|max:255',
            'ngay' => 'required|date',
            'muc_do' => 'required|in:canh_cao,khien_trach,sa_thai',
            'so_tien' => 'nullable|numeric|min:0', // 👈 Cho phép nhập số tiền phạt
            'hinh_thuc' => 'nullable|string|max:255',
            'quyet_dinh_so' => 'nullable|string|max:255',
            'noi_dung' => 'nullable|string',
        ]);

        $data['loai'] = 'ky_luat';
        $data['nguoi_ky_id'] = auth()->id(); // Tự động lấy người đang đăng nhập

        KhenThuongKyLuatNhanVien::create($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Thêm kỷ luật thành công');
    }

    /**
     * Hiển thị chi tiết
     */
    public function show($id)
    {
        $ktkl = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'nguoiKy'
        ])->findOrFail($id);

        return view('admin.khen-thuong-ky-luat.show', compact('ktkl'));
    }

    /**
     * Form sửa
     */
    public function edit($id)
    {
        $ktkl = KhenThuongKyLuatNhanVien::findOrFail($id);

        $hoSos = HoSo::with('nguoi_dung.phongBan')
            ->whereHas('nguoi_dung', function ($q) {
                $q->where('trang_thai_cong_viec', 'dang_lam');
            })
            ->get();

        return view('admin.khen-thuong-ky-luat.edit', compact('ktkl', 'hoSos'));
    }

    /**
     * Cập nhật khen thưởng
     */
    public function updateKhenThuong(Request $request, $id)
    {
        $kt = KhenThuongKyLuatNhanVien::findOrFail($id);

        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|string|max:255',
            'ngay' => 'required|date',
            'muc_do' => 'required|in:kha,gioi,xuat_sac',
            'so_tien' => 'nullable|numeric|min:0',
            'hinh_thuc' => 'nullable|string|max:255',
            'quyet_dinh_so' => 'nullable|string|max:255',
            'noi_dung' => 'nullable|string',
            'bang_chung' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'quyet_dinh_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('bang_chung')) {
            // Xóa file cũ nếu có
            if ($kt->bang_chung) {
                \Storage::disk('public')->delete($kt->bang_chung);
            }
            $data['bang_chung'] = $request->file('bang_chung')->store('bang-chung', 'public');
        }

        if ($request->hasFile('quyet_dinh_file')) {
            if ($kt->quyet_dinh_file) {
                \Storage::disk('public')->delete($kt->quyet_dinh_file);
            }
            $data['quyet_dinh_file'] = $request->file('quyet_dinh_file')->store('quyet-dinh', 'public');
        }

        $kt->update($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Cập nhật khen thưởng thành công');
    }

    /**
     * Cập nhật kỷ luật
     */
    public function updateKyLuat(Request $request, $id)
    {
        $kt = KhenThuongKyLuatNhanVien::findOrFail($id);

        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|string|max:255',
            'ngay' => 'required|date',
            'muc_do' => 'required|in:canh_cao,khien_trach,sa_thai',
            'so_tien' => 'nullable|numeric|min:0', // 👈 Cho phép cập nhật số tiền phạt
            'hinh_thuc' => 'nullable|string|max:255',
            'quyet_dinh_so' => 'nullable|string|max:255',
            'noi_dung' => 'nullable|string',
        ]);

        $kt->update($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Cập nhật kỷ luật thành công');
    }

    /**
     * Xóa
     */
    public function destroy($id)
    {
        $kt = KhenThuongKyLuatNhanVien::findOrFail($id);

        // Xóa file đính kèm nếu có
        if ($kt->bang_chung) {
            \Storage::disk('public')->delete($kt->bang_chung);
        }
        if ($kt->quyet_dinh_file) {
            \Storage::disk('public')->delete($kt->quyet_dinh_file);
        }

        $kt->delete();

        return back()->with('success', 'Đã xóa thành công');
    }

    /**
     * Xuất Excel
     */
    public function export(Request $request)
    {
        return Excel::download(
            new KhenThuongKyLuatExport($request->all()),
            'khen-thuong-ky-luat.xlsx'
        );
    }

    /**
     * Thống kê
     */
    public function thongKe(Request $request)
    {
        $query = KhenThuongKyLuatNhanVien::query();

        if ($request->filled('nam')) {
            $query->whereYear('ngay', $request->nam);
        }

        if ($request->filled('thang')) {
            $query->whereMonth('ngay', $request->thang);
        }

        if ($request->filled('phong_ban')) {
            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban);
            });
        }

        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        $tongQuyetDinh = (clone $query)->count();
        $tongKhenThuong = (clone $query)->where('loai', 'khen_thuong')->count();
        $tongKyLuat = (clone $query)->where('loai', 'ky_luat')->count();
        $tongTienThuong = (clone $query)->where('loai', 'khen_thuong')->sum('so_tien');
        $tongTienPhat = (clone $query)->where('loai', 'ky_luat')->sum('so_tien');

        $chartTheoThang = (clone $query)
            ->selectRaw('MONTH(ngay) as thang, COUNT(*) as tong')
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();

        $chartPhongBan = KhenThuongKyLuatNhanVien::with('hoSo.nguoi_dung.phongBan')
            ->get()
            ->groupBy(fn($item) => $item->hoSo?->nguoi_dung?->phongBan?->ten_phong_ban)
            ->map(fn($group, $key) => [
                'ten' => $key ?? 'Không xác định',
                'tong' => $group->count()
            ])
            ->values();

        return view('admin.khen-thuong-ky-luat.thong-ke', compact(
            'phongBans',
            'tongQuyetDinh',
            'tongKhenThuong',
            'tongKyLuat',
            'tongTienThuong',
            'tongTienPhat',
            'chartTheoThang',
            'chartPhongBan'
        ));
    }

    /**
     * Tính điểm cho 1 quyết định
     */
    private function tinhDiem($quyetDinh)
    {
        if ($quyetDinh->loai === 'khen_thuong') {
            return match ($quyetDinh->muc_do) {
                'kha' => 5,
                'gioi' => 10,
                'xuat_sac' => 20,
                default => 0,
            };
        }

        return match ($quyetDinh->muc_do) {
            'khien_trach' => -5,
            'canh_cao' => -10,
            'sa_thai' => -20,
            default => 0,
        };
    }

    /**
     * Tính thưởng cuối năm
     */
    public function tinhThuong(Request $request)
    {
        $nam = $request->input('nam', now()->year);

        $hoSos = HoSo::with([
            'nguoi_dung.phongBan',
            'khen_thuong_ky_luat'
        ])->whereHas('nguoi_dung', function ($q) {
            $q->where('trang_thai_cong_viec', 'dang_lam');
        })->get();

        $ketQua = [];

        foreach ($hoSos as $hoSo) {
            $quyetDinhs = $hoSo->khen_thuong_ky_luat
                ->whereBetween('ngay', [
                    $nam . '-01-01',
                    $nam . '-12-31'
                ]);

            $tongDiem = 0;
            $tongTienPhat = 0;

            foreach ($quyetDinhs as $item) {
                $tongDiem += $this->tinhDiem($item);
                if ($item->loai === 'ky_luat' && $item->so_tien) {
                    $tongTienPhat += $item->so_tien;
                }
            }

            $luong = LuongNhanVien::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('luong_nam', $nam)
                ->orderByDesc('luong_thang')
                ->first();

            $thuongCoBan = $luong?->tong_luong ?? 0;
            $diemKpi = \App\Models\KetQuaDanhGia::diemTrungBinhNam($hoSo->nguoi_dung_id, (int) $nam);
            $heSoKpi = $diemKpi === null ? 1 : round($diemKpi / 100, 4);
            $thuongCuoiNam = $thuongCoBan * (1 + ($tongDiem / 100)) * $heSoKpi;

            if ($thuongCuoiNam < 0) {
                $thuongCuoiNam = 0;
            }

            $ketQua[] = [
                'hoSo' => $hoSo,
                'tong_diem' => $tongDiem,
                'thuong_co_ban' => $thuongCoBan,
                'thuong_cuoi_nam' => $thuongCuoiNam,
                'diem_kpi' => $diemKpi,
                'he_so_kpi' => $heSoKpi,
                'tong_khen_thuong' => $quyetDinhs->where('loai', 'khen_thuong')->count(),
                'tong_ky_luat' => $quyetDinhs->where('loai', 'ky_luat')->count(),
                'tong_tien_phat' => $tongTienPhat,
            ];
        }

        return view(
            'admin.khen-thuong-ky-luat.thuong-cuoi-nam',
            compact('ketQua', 'nam')
        );
    }
}
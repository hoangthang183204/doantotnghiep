<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use App\Models\KhenThuongKyLuatNhanVien;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Exports\KhenThuongKyLuatExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class KhenThuongKyLuatController extends Controller
{
    /**
     * Danh sách
     */
    public function index(Request $request)
    {
        $query = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'nguoiKy'
        ]);

        // ================= FILTER =================

        // search: mã nhân viên / tên
        if ($request->filled('search')) {
            $keyword = $request->search;

            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($keyword) {
                $q->where('ma_nhan_vien', 'like', "%{$keyword}%")
                    ->orWhere('ho', 'like', "%{$keyword}%")
                    ->orWhere('ten', 'like', "%{$keyword}%")
                    ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
            });
        }

        // loại
        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        // phòng ban
        if ($request->filled('phong_ban')) {
            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban);
            });
        }

        // tháng (theo ngày quyết định)
        if ($request->filled('thang')) {
            $query->whereMonth('ngay', $request->thang);
        }

        // năm
        if ($request->filled('nam')) {
            $query->whereYear('ngay', $request->nam);
        }

        // ================= DATA LIST =================

        $ds = (clone $query)
            ->latest('ngay')
            ->paginate(10)
            ->withQueryString();

        // ================= DROPDOWN DATA =================

        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        // ================= THỐNG KÊ (THEO FILTER) =================

        $tongQuyetDinh = (clone $query)->count();

        $tongKhenThuong = (clone $query)
            ->where('loai', 'khen_thuong')
            ->count();

        $tongKyLuat = (clone $query)
            ->where('loai', 'ky_luat')
            ->count();

        $tongTienThuong = (clone $query)
            ->where('loai', 'khen_thuong')
            ->sum('so_tien');

        // ================= CHART THEO THÁNG =================

        $chartTheoThang = (clone $query)
            ->selectRaw('MONTH(ngay) as thang, COUNT(*) as total')
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();

        // ================= VIEW =================

        return view('admin.khen-thuong-ky-luat.index', compact(
            'ds',
            'phongBans',
            'tongQuyetDinh',
            'tongKhenThuong',
            'tongKyLuat',
            'tongTienThuong',
            'chartTheoThang'
        ));
    }

    /**
     * Form thêm
     */
    public function create()
    {
        $hoSos = HoSo::with([
            'nguoi_dung.phongBan',
            'nguoi_dung.chucVu'
        ])
            ->orderBy('ho')
            ->orderBy('ten')
            ->get();

        $nguoiKys = NguoiDung::orderBy('ten_dang_nhap')->get();

        return view(
            'admin.khen-thuong-ky-luat.create',
            compact(
                'hoSos',
                'nguoiKys'
            )
        );
    }

    /**
     * Lưu
     */
    public function store(Request $request)
    {

        $data = $request->validate([

            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',

            'loai' => 'required|in:khen_thuong,ky_luat',

            'ten' => 'required|max:255',

            'ngay' => 'required|date',

            'noi_dung' => 'nullable',

            'hinh_thuc' => 'nullable|max:255',

            'so_tien' => 'nullable|numeric|min:0',

            'quyet_dinh_so' => 'nullable|max:255',

            'nguoi_ky_id' => 'nullable|exists:nguoi_dung,id',

        ]);

        KhenThuongKyLuatNhanVien::create($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Đã thêm thành công.');
    }

    /**
     * Chi tiết
     */
    public function show($id)
    {
        $ktkl = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'hoSo.nguoi_dung.chucVu',
            'nguoiKy'
        ])->findOrFail($id);

        return view('admin.khen-thuong-ky-luat.show', compact('ktkl'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new KhenThuongKyLuatExport($request->all()),
            'khen-thuong-ky-luat.xlsx'
        );
    }

    /**
     * Form sửa
     */
    public function edit($id)
    {

        $ktkl = KhenThuongKyLuatNhanVien::findOrFail($id);

        $hoSos = HoSo::with([
            'nguoi_dung.phongBan',
            'nguoi_dung.chucVu'
        ])
            ->orderBy('ho')
            ->orderBy('ten')
            ->get();

        $nguoiKys = NguoiDung::orderBy('ten_dang_nhap')->get();

        return view(
            'admin.khen-thuong-ky-luat.edit',
            compact(
                'ktkl',
                'hoSos',
                'nguoiKys'
            )
        );
    }

    /**
     * Cập nhật
     */
    public function update(Request $request, $id)
    {

        $ktkl = KhenThuongKyLuatNhanVien::findOrFail($id);

        $data = $request->validate([

            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',

            'loai' => 'required|in:khen_thuong,ky_luat',

            'ten' => 'required|max:255',

            'ngay' => 'required|date',

            'noi_dung' => 'nullable',

            'hinh_thuc' => 'nullable|max:255',

            'so_tien' => 'nullable|numeric|min:0',

            'quyet_dinh_so' => 'nullable|max:255',

            'nguoi_ky_id' => 'nullable|exists:nguoi_dung,id',

        ]);

        $ktkl->update($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Đã cập nhật thành công.');
    }

    /**
     * Xóa
     */
    public function destroy($id)
    {

        $ktkl = KhenThuongKyLuatNhanVien::findOrFail($id);

        $ktkl->delete();

        return back()->with(
            'success',
            'Đã xóa thành công.'
        );
    }

    public function thongKe(Request $request)
    {
        $query = KhenThuongKyLuatNhanVien::query();

        // ================= FILTER =================

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

        // ================= DROPDOWN =================
        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        // ================= KPI =================
        $tongQuyetDinh = (clone $query)->count();

        $tongKhenThuong = (clone $query)->where('loai', 'khen_thuong')->count();

        $tongKyLuat = (clone $query)->where('loai', 'ky_luat')->count();

        $tongTienThuong = (clone $query)
            ->where('loai', 'khen_thuong')
            ->sum('so_tien');

        // ================= CHART THEO THÁNG =================
        $chartTheoThang = (clone $query)
            ->selectRaw('MONTH(ngay) as thang, COUNT(*) as tong')
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();

        // ================= CHART THEO PHÒNG BAN =================
        $chartPhongBan = KhenThuongKyLuatNhanVien::query()
            ->join('ho_so_nguoi_dung', 'ho_so_nguoi_dung.id', '=', 'khen_thuong_ky_luat_nhan_vien.ho_so_id')
            ->join('nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
            ->join('phong_ban', 'phong_ban.id', '=', 'nguoi_dung.phong_ban_id')
            ->when($request->filled('nam'), fn($q) => $q->whereYear('khen_thuong_ky_luat_nhan_vien.ngay', $request->nam))
            ->when($request->filled('thang'), fn($q) => $q->whereMonth('khen_thuong_ky_luat_nhan_vien.ngay', $request->thang))
            ->selectRaw('phong_ban.ten_phong_ban as ten, COUNT(*) as tong')
            ->groupBy('phong_ban.ten_phong_ban')
            ->get();

        // ================= VIEW =================
        return view('admin.khen-thuong-ky-luat.thong-ke', compact(
            'phongBans',
            'tongQuyetDinh',
            'tongKhenThuong',
            'tongKyLuat',
            'tongTienThuong',
            'chartTheoThang',
            'chartPhongBan'
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Models\KhenThuongKyLuatNhanVien;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhenThuongKyLuatExport;

class KhenThuongKyLuatController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'nguoiKy'
        ]);

        if ($request->filled('search')) {
            $keyword = $request->search;

            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($keyword) {
                $q->where('ma_nhan_vien', 'like', "%$keyword%")
                    ->orWhere('ho', 'like', "%$keyword%")
                    ->orWhere('ten', 'like', "%$keyword%")
                    ->orWhereRaw("CONCAT(ho,' ',ten) LIKE ?", ["%$keyword%"]);
            });
        }

        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        if ($request->filled('phong_ban')) {
            $query->whereHas('hoSo.nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban);
            });
        }

        if ($request->filled('thang')) {
            $query->whereMonth('ngay', $request->thang);
        }

        if ($request->filled('nam')) {
            $query->whereYear('ngay', $request->nam);
        }

        $ds = (clone $query)
            ->latest('ngay')
            ->paginate(10)
            ->withQueryString();

        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();

        $tongQuyetDinh = (clone $query)->count();
        $tongKhenThuong = (clone $query)->where('loai', 'khen_thuong')->count();
        $tongKyLuat = (clone $query)->where('loai', 'ky_luat')->count();
        $tongTienThuong = (clone $query)->where('loai', 'khen_thuong')->sum('so_tien');

        return view('admin.khen-thuong-ky-luat.index', compact(
            'ds',
            'phongBans',
            'tongQuyetDinh',
            'tongKhenThuong',
            'tongKyLuat',
            'tongTienThuong'
        ));
    }

    // ================= CREATE =================
    public function createKhenThuong()
    {
        $hoSos = HoSo::with('nguoi_dung.phongBan')
            ->orderBy('ho')
            ->orderBy('ten')
            ->get();

        $nguoiKys = NguoiDung::orderBy('ten_dang_nhap')->get();

        return view('admin.khen-thuong-ky-luat.create_khen_thuong', compact(
            'hoSos',
            'nguoiKys'
        ));
    }

    public function createKyLuat()
    {
        $hoSos = HoSo::with('nguoi_dung.phongBan')
            ->orderBy('ho')
            ->orderBy('ten')
            ->get();

        $nguoiKys = NguoiDung::orderBy('ten_dang_nhap')->get();

        return view('admin.khen-thuong-ky-luat.create_ky_luat', compact(
            'hoSos',
            'nguoiKys'
        ));
    }

    // ================= STORE =================
    public function storeKhenThuong(Request $request)
    {
        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|max:255',
            'ngay' => 'required|date',
            'so_tien' => 'nullable|numeric|min:0',
            'hinh_thuc' => 'nullable|max:255',
            'quyet_dinh_so' => 'nullable|max:255',
            'nguoi_ky_id' => 'nullable|exists:nguoi_dung,id',
            'noi_dung' => 'nullable',

            'bang_chung' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'quyet_dinh_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data['loai'] = 'khen_thuong';

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

    public function storeKyLuat(Request $request)
    {
        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|max:255',
            'ngay' => 'required|date',
            'muc_do' => 'required|in:canh_cao,khien_trach,sa_thai',
            'quyet_dinh_so' => 'nullable|max:255',
            'nguoi_ky_id' => 'nullable|exists:nguoi_dung,id',
            'noi_dung' => 'nullable',
        ]);

        $data['loai'] = 'ky_luat';
        $data['so_tien'] = null;

        KhenThuongKyLuatNhanVien::create($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Thêm kỷ luật thành công');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $ktkl = KhenThuongKyLuatNhanVien::findOrFail($id);

        $hoSos = HoSo::with('nguoi_dung.phongBan')->get();
        $nguoiKys = NguoiDung::orderBy('ten_dang_nhap')->get();

        return view('admin.khen-thuong-ky-luat.edit', compact(
            'ktkl',
            'hoSos',
            'nguoiKys'
        ));
    }

    // ================= UPDATE =================
    public function updateKhenThuong(Request $request, $id)
    {
        $kt = KhenThuongKyLuatNhanVien::findOrFail($id);

        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|max:255',
            'ngay' => 'required|date',
            'so_tien' => 'nullable|numeric|min:0',
            'hinh_thuc' => 'nullable|max:255',
            'quyet_dinh_so' => 'nullable|max:255',
            'nguoi_ky_id' => 'nullable|exists:nguoi_dung,id',
            'noi_dung' => 'nullable',
            'bang_chung' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'quyet_dinh_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('bang_chung')) {
            $data['bang_chung'] = $request->file('bang_chung')
                ->store('bang-chung', 'public');
        }

        if ($request->hasFile('quyet_dinh_file')) {
            $data['quyet_dinh_file'] = $request->file('quyet_dinh_file')
                ->store('quyet-dinh', 'public');
        }

        $kt->update($data);

        // ✅ FIX: chuyển về index thay vì show
        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function updateKyLuat(Request $request, $id)
    {
        $kt = KhenThuongKyLuatNhanVien::findOrFail($id);

        $data = $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten' => 'required|max:255',
            'ngay' => 'required|date',
            'muc_do' => 'required|in:canh_cao,khien_trach,sa_thai',
            'hinh_thuc' => 'nullable|max:255',
            'quyet_dinh_so' => 'nullable|max:255',
            'nguoi_ky_id' => 'nullable|exists:nguoi_dung,id',
            'noi_dung' => 'nullable',
        ]);

        $data['loai'] = 'ky_luat';
        $data['so_tien'] = null;

        $kt->update($data);

        return redirect()
            ->route('admin.khen-thuong-ky-luat.index')
            ->with('success', 'Cập nhật kỷ luật thành công');
    }

    // ================= OTHER =================
    public function show($id)
    {
        $ktkl = KhenThuongKyLuatNhanVien::with([
            'hoSo.nguoi_dung.phongBan',
            'nguoiKy'
        ])->findOrFail($id);

        return view('admin.khen-thuong-ky-luat.show', compact('ktkl'));
    }

    public function destroy($id)
    {
        KhenThuongKyLuatNhanVien::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa thành công');
    }

    // ================= EXPORT =================
    public function export(Request $request)
    {
        return Excel::download(
            new KhenThuongKyLuatExport($request->all()),
            'khen-thuong-ky-luat.xlsx'
        );
    }

    // ================= THỐNG KÊ =================
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

        $chartPhongBan = KhenThuongKyLuatNhanVien::with('hoSo.nguoi_dung.phongBan')
            ->get()
            ->groupBy(fn($item) => $item->hoSo?->nguoi_dung?->phongBan?->ten_phong_ban)
            ->map(fn($group, $key) => [
                'ten' => $key ?? 'Không xác định',
                'tong' => $group->count()
            ])
            ->values();

        $phongBans = PhongBan::orderBy('ten_phong_ban')->get();
        $tongQuyetDinh = (clone $query)->count();
        $tongKhenThuong = (clone $query)->where('loai', 'khen_thuong')->count();
        $tongKyLuat = (clone $query)->where('loai', 'ky_luat')->count();
        $tongTienThuong = (clone $query)->where('loai', 'khen_thuong')->sum('so_tien');

        $chartTheoThang = (clone $query)
            ->selectRaw('MONTH(ngay) as thang, COUNT(*) as tong')
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();

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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhongBan;
use App\Models\NguoiDung;
use App\Models\ChucVu;
use Illuminate\Http\Request;

class PhongBanController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Sửa từ 'truong_phong' thành 'truongPhong'
        $query = PhongBan::with(['truongPhong.hoSo'])
            ->withCount(['nguoiDungs', 'chucVus']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ma_phong_ban', 'like', "%{$keyword}%")
                    ->orWhere('ten_phong_ban', 'like', "%{$keyword}%");
            });
        }

        $phongBans = $query
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.phong-ban.index', compact('phongBans'));
    }

    public function create()
    {
        $nguoiDungs = NguoiDung::where('trang_thai', 1)->get();

        return view('admin.phong-ban.create', compact('nguoiDungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_phong_ban' => 'required|string|max:255|unique:phong_ban,ma_phong_ban',
            'ten_phong_ban' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'truong_phong_id' => 'nullable|exists:nguoi_dung,id',
            'ngan_sach' => 'nullable|numeric|min:0',
            'trang_thai' => 'boolean',
        ]);

        PhongBan::create($request->all());

        return redirect()->route('admin.phong-ban.index')
            ->with('success', 'Thêm phòng ban thành công');
    }

    public function show($id)
    {
        // ✅ Sửa từ 'truong_phong' thành 'truongPhong'
        $phongBan = PhongBan::with(['truongPhong', 'nguoiDungs', 'chucVus'])->findOrFail($id);

        return view('admin.phong-ban.show', compact('phongBan'));
    }

    public function edit($id)
    {
        $phongBan = PhongBan::findOrFail($id);
        $nguoiDungs = NguoiDung::where('trang_thai', 1)->get();

        return view('admin.phong-ban.edit', compact('phongBan', 'nguoiDungs'));
    }

    public function update(Request $request, $id)
    {
        $phongBan = PhongBan::findOrFail($id);

        $request->validate([
            'ma_phong_ban' => 'required|string|max:255|unique:phong_ban,ma_phong_ban,' . $id,
            'ten_phong_ban' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'truong_phong_id' => 'nullable|exists:nguoi_dung,id',
            'ngan_sach' => 'nullable|numeric|min:0',
            'trang_thai' => 'boolean',
        ]);

        $phongBan->update($request->all());

        return redirect()->route('admin.phong-ban.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $phongBan = PhongBan::findOrFail($id);

        if ($phongBan->nguoiDungs()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì phòng ban đang có nhân viên.');
        }

        if ($phongBan->chucVus()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì phòng ban đang có chức vụ.');
        }

        $phongBan->delete();

        return back()->with('success', 'Xóa thành công');
    }

    // ✅ THÊM: Sơ đồ phòng ban
    public function orgChart()
    {
        $phongBans = PhongBan::with(['chucVus' => function($query) {
                $query->withCount('nguoi_dungs');
            }])
            ->withCount('nguoiDungs')
            ->where('trang_thai', 1)
            ->get();
        
        return view('admin.phong-ban.org-chart', compact('phongBans'));
    }

    // ✅ THÊM: Thống kê phòng ban
    public function statistics()
    {
        $totalPhongBans = PhongBan::count();
        $activePhongBans = PhongBan::where('trang_thai', 1)->count();
        $inactivePhongBans = PhongBan::where('trang_thai', 0)->count();
        
        $phongBanStats = PhongBan::withCount(['nguoiDungs', 'chucVus'])
            ->orderBy('nguoi_dungs_count', 'desc')
            ->get();
        
        $totalNhanVien = NguoiDung::where('trang_thai', 1)->count();
        $totalChucVu = ChucVu::count();
        
        return view('admin.phong-ban.statistics', compact(
            'totalPhongBans',
            'activePhongBans',
            'inactivePhongBans',
            'phongBanStats',
            'totalNhanVien',
            'totalChucVu'
        ));
    }
}
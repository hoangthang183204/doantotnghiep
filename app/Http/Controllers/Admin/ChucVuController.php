<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucVu;
use App\Models\PhongBan;
use App\Models\LuongNhanVien; // ✅ THÊM DÒNG NÀY
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function index()
    {
        $chucVus = ChucVu::with('phong_ban')
            ->withCount('nguoi_dungs')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.chuc-vu.index', compact('chucVus'));
    }

    public function create()
    {
        $phongBans = PhongBan::where('trang_thai', 1)->get();

        return view('admin.chuc-vu.create', compact('phongBans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'required|string|max:255|unique:chuc_vu,ma',
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'he_so_luong' => 'nullable|numeric|min:0|max:10',
            'trang_thai' => 'boolean',
        ]);

        ChucVu::create([
            'ten' => $request->ten,
            'ma' => $request->ma,
            'phong_ban_id' => $request->phong_ban_id,
            'mo_ta' => $request->mo_ta,
            'luong_co_ban' => $request->luong_co_ban ?? null,
            'he_so_luong' => $request->he_so_luong ?? null,
            'trang_thai' => $request->trang_thai ?? 1,
        ]);

        return redirect()
            ->route('admin.chuc-vu.index')
            ->with('success', 'Thêm chức vụ thành công');
    }

    public function show($id)
    {
        $chucVu = ChucVu::with(['phong_ban', 'nguoi_dungs'])->findOrFail($id);
        
        // ✅ Lấy lương gần nhất của nhân viên trong chức vụ này
        $luongGanNhat = LuongNhanVien::whereIn('nguoi_dung_id', $chucVu->nguoi_dungs->pluck('id'))
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->first();
        
        // ✅ Lấy danh sách lương của các nhân viên trong chức vụ
        $luongNhanViens = LuongNhanVien::whereIn('nguoi_dung_id', $chucVu->nguoi_dungs->pluck('id'))
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->get();
        
        // ✅ Thống kê
        $totalEmployees = $chucVu->nguoi_dungs->count();
        $activeEmployees = $chucVu->nguoi_dungs->where('trang_thai', 1)->count();
        
        return view('admin.chuc-vu.show', compact('chucVu', 'luongGanNhat', 'luongNhanViens', 'totalEmployees', 'activeEmployees'));
    }

    public function edit($id)
    {
        $chucVu = ChucVu::findOrFail($id);
        $phongBans = PhongBan::where('trang_thai', 1)->get();

        return view('admin.chuc-vu.edit', compact('chucVu', 'phongBans'));
    }

    public function update(Request $request, $id)
    {
        $chucVu = ChucVu::findOrFail($id);

        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'required|string|max:255|unique:chuc_vu,ma,' . $id,
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'he_so_luong' => 'nullable|numeric|min:0|max:10',
            'trang_thai' => 'boolean',
        ]);

        $chucVu->update([
            'ten' => $request->ten,
            'ma' => $request->ma,
            'phong_ban_id' => $request->phong_ban_id,
            'mo_ta' => $request->mo_ta,
            'luong_co_ban' => $request->luong_co_ban ?? null,
            'he_so_luong' => $request->he_so_luong ?? null,
            'trang_thai' => $request->trang_thai ?? 1,
        ]);

        return redirect()
            ->route('admin.chuc-vu.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $chucVu = ChucVu::findOrFail($id);
        
        $hasEmployees = $chucVu->nguoi_dungs()->exists();
        
        if ($hasEmployees) {
            $newStatus = $chucVu->trang_thai == 1 ? 0 : 1;
            $chucVu->update(['trang_thai' => $newStatus]);
            $message = $newStatus == 1 
                ? 'Đã hiển thị lại chức vụ thành công' 
                : 'Đã ẩn chức vụ thành công (có nhân viên đang giữ)';
        } else {
            $chucVu->delete();
            $message = 'Đã xóa chức vụ thành công';
        }

        return back()->with('success', $message);
    }

    // ✅ THÊM: Sơ đồ tổ chức
    public function orgChart()
    {
        $phongBans = PhongBan::with(['chucVus' => function($query) {
            $query->withCount('nguoi_dungs')
                  ->orderBy('id');
        }])->where('trang_thai', 1)->get();
        
        return view('admin.chuc-vu.org-chart', compact('phongBans'));
    }

    // ✅ THÊM: Thống kê
    public function statistics()
    {
        $totalChucVus = ChucVu::count();
        $activeChucVus = ChucVu::where('trang_thai', 1)->count();
        $inactiveChucVus = ChucVu::where('trang_thai', 0)->count();
        
        $topChucVus = ChucVu::with('phong_ban')
            ->withCount('nguoi_dungs')
            ->orderBy('nguoi_dungs_count', 'desc')
            ->take(5)
            ->get();
            
        $phongBanStats = PhongBan::withCount('chucVus')
            ->orderBy('chuc_vus_count', 'desc')
            ->get();
        
        return view('admin.chuc-vu.statistics', compact(
            'totalChucVus', 
            'activeChucVus', 
            'inactiveChucVus',
            'topChucVus',
            'phongBanStats'
        ));
    }
}
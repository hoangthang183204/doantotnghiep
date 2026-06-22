<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucVu;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChucVuController extends Controller
{
    public function index()
    {
        // ✅ Thêm đếm số nhân viên cho mỗi chức vụ
        $chucVus = ChucVu::with('phong_ban')
            ->withCount('nguoi_dungs') // Thêm count
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
        // ✅ Bổ sung validate cho lương
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'required|string|max:255|unique:chuc_vu,ma',
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string',
            'luong_co_ban' => 'nullable|numeric|min:0', // ✅ Đã thêm lại
            'he_so_luong' => 'nullable|numeric|min:0|max:10', // ✅ Đã thêm lại
            'trang_thai' => 'boolean',
        ]);

        // ✅ Xử lý dữ liệu cẩn thận
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

        // ✅ Thêm thống kê thêm
        $totalEmployees = $chucVu->nguoi_dungs->count();
        $activeEmployees = $chucVu->nguoi_dungs->where('trang_thai', 1)->count();

        return view('admin.chuc-vu.show', compact('chucVu', 'totalEmployees', 'activeEmployees'));
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

        // ✅ Bổ sung validate cho lương
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => [
                'required',
                'string',
                'max:255',
                Rule::unique('chuc_vu', 'ma')->ignore($id),
            ],
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'he_so_luong' => 'nullable|numeric|min:0|max:10',
            'trang_thai' => 'boolean',
        ]);

        // ✅ Cập nhật đầy đủ
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

        // ✅ Kiểm tra ràng buộc trước khi ẩn
        $hasEmployees = $chucVu->nguoi_dungs()->exists();

        if ($hasEmployees) {
            // Nếu có nhân viên, chỉ chuyển trạng thái
            $newStatus = $chucVu->trang_thai == 1 ? 0 : 1;
            $chucVu->update(['trang_thai' => $newStatus]);
            $message = $newStatus == 1
                ? 'Đã hiển thị lại chức vụ thành công'
                : 'Đã ẩn chức vụ thành công (có nhân viên đang giữ)';
        } else {
            // Nếu không có nhân viên, có thể xóa thực sự
            $chucVu->delete();
            $message = 'Đã xóa chức vụ thành công';
        }

        return back()->with('success', $message);
    }

    public function orgChart()
    {
        // Lấy tất cả phòng ban có chức vụ
        $phongBans = PhongBan::with(['chucVus' => function ($query) {
            $query->withCount('nguoi_dungs')
                ->orderBy('id');
        }])->where('trang_thai', 1)
            ->has('chucVus') // Chỉ lấy phòng ban có chức vụ
            ->get();

        // Lấy tổng số nhân viên theo từng chức vụ để hiển thị
        $chucVuStats = ChucVu::withCount('nguoi_dungs')->get()->keyBy('id');

        return view('admin.chuc-vu.org-chart', compact('phongBans', 'chucVuStats'));
    }

    public function statistics()
    {
        // 1. Tổng quan
        $totalChucVus = ChucVu::count();
        $activeChucVus = ChucVu::where('trang_thai', 1)->count();
        $inactiveChucVus = ChucVu::where('trang_thai', 0)->count();

        // 2. Top chức vụ có nhiều nhân viên nhất
        $topChucVus = ChucVu::with('phong_ban')
            ->withCount('nguoi_dungs')
            ->orderBy('nguoi_dungs_count', 'desc')
            ->take(5)
            ->get();

        // 3. Thống kê theo phòng ban
        $phongBanStats = PhongBan::withCount('chucVus')
            ->with(['chucVus' => function ($query) {
                $query->withCount('nguoi_dungs');
            }])
            ->orderBy('chuc_vus_count', 'desc')
            ->get();

        // 4. Dữ liệu cho biểu đồ (nếu dùng Chart.js)
        $chartData = [
            'labels' => $phongBanStats->pluck('ten_phong_ban')->toArray(),
            'data' => $phongBanStats->pluck('chuc_vus_count')->toArray(),
        ];

        return view('admin.chuc-vu.statistics', compact(
            'totalChucVus',
            'activeChucVus',
            'inactiveChucVus',
            'topChucVus',
            'phongBanStats',
            'chartData'
        ));
    }
}

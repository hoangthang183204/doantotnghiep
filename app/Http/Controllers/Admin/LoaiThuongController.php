<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiThuong;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Danh mục loại thưởng — admin/HR tự định nghĩa các loại thưởng của công ty
 * (chuyên cần, KPI, lương tháng 13, thưởng Tết, thâm niên, sáng kiến...).
 */
class LoaiThuongController extends Controller
{
    public function index(Request $request)
    {
        $query = LoaiThuong::withCount('thuongNhanViens');

        if ($request->filled('tim')) {
            $tim = $request->input('tim');
            $query->where(function ($q) use ($tim) {
                $q->where('ten', 'like', "%{$tim}%")
                    ->orWhere('ma', 'like', "%{$tim}%");
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', (bool) $request->input('trang_thai'));
        }

        $loaiThuongs = $query->orderByDesc('trang_thai')
            ->orderBy('ten')
            ->paginate(15)
            ->withQueryString();

        return view('admin.loai-thuong.index', compact('loaiThuongs'));
    }

    public function create()
    {
        return view('admin.loai-thuong.create');
    }

    public function store(Request $request)
    {
        LoaiThuong::create($this->validateData($request));

        return redirect()
            ->route('admin.loai-thuong.index')
            ->with('success', 'Đã thêm loại thưởng mới.');
    }

    public function edit($id)
    {
        $loaiThuong = LoaiThuong::findOrFail($id);

        return view('admin.loai-thuong.edit', compact('loaiThuong'));
    }

    public function update(Request $request, $id)
    {
        $loaiThuong = LoaiThuong::findOrFail($id);
        $loaiThuong->update($this->validateData($request, (int) $id));

        return redirect()
            ->route('admin.loai-thuong.index')
            ->with('success', 'Đã cập nhật loại thưởng.');
    }

    public function destroy($id)
    {
        $loaiThuong = LoaiThuong::withCount('thuongNhanViens')->findOrFail($id);

        // Đã có khoản thưởng dùng loại này → chỉ ngừng sử dụng để không vỡ dữ liệu lương cũ
        if ($loaiThuong->thuong_nhan_viens_count > 0) {
            $loaiThuong->update(['trang_thai' => false]);

            return redirect()
                ->route('admin.loai-thuong.index')
                ->with('success', 'Loại thưởng đang được sử dụng nên đã chuyển sang "Ngừng sử dụng" thay vì xoá.');
        }

        $loaiThuong->delete();

        return redirect()
            ->route('admin.loai-thuong.index')
            ->with('success', 'Đã xoá loại thưởng.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'ten'                => 'required|string|max:255',
            'ma'                 => ['required', 'string', 'max:50', Rule::unique('loai_thuong', 'ma')->ignore($id)],
            'mo_ta'              => 'nullable|string|max:1000',
            'hinh_thuc_mac_dinh' => 'required|in:dinh_ky,mot_lan',
            'cach_tinh'          => 'required|in:so_tien_co_dinh,phan_tram_luong_cb',
            'gia_tri_mac_dinh'   => 'required|numeric|min:0',
            'chiu_thue'          => 'nullable|boolean',
            'trang_thai'         => 'nullable|boolean',
        ], [], [
            'ten'              => 'tên loại thưởng',
            'ma'               => 'mã',
            'gia_tri_mac_dinh' => 'giá trị mặc định',
        ]);

        $data['ma']         = strtoupper($data['ma']);
        $data['chiu_thue']  = $request->boolean('chiu_thue');
        $data['trang_thai'] = $request->boolean('trang_thai');

        return $data;
    }
}

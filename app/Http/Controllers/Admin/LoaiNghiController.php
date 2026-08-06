<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoaiNghiPhep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoaiNghiController extends Controller
{
    /**
     * Hiển thị danh sách loại nghỉ phép
     */
    public function index(Request $request)
    {
        $query = LoaiNghiPhep::query();

        // Tìm kiếm theo tên hoặc mã
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('ten', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('ma', 'LIKE', "%{$searchTerm}%");
            });
        }

        $dsLoaiNghi = $query->latest()->get();

        // Thống kê
        $tongLoaiNghi = LoaiNghiPhep::count();
        $dangHoatDong = LoaiNghiPhep::where('trang_thai', 1)->count();
        $coLuong      = LoaiNghiPhep::where('co_luong', 1)->count();
        $khongLuong   = LoaiNghiPhep::where('co_luong', 0)->count();

        return view('admin.loai_nghi_phep.index', compact(
            'dsLoaiNghi',
            'tongLoaiNghi',
            'dangHoatDong',
            'coLuong',
            'khongLuong'
        ));
    }

    /**
     * Hiển thị form tạo mới
     */
    public function create()
    {
        return view('admin.loai_nghi_phep.create');
    }

    /**
     * Lưu loại nghỉ phép mới
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten'        => 'required|string|max:255',
                'ma'         => 'required|string|max:50|unique:loai_nghi_phep,ma',
                'co_luong'   => 'required|boolean',
                'trang_thai' => 'required|boolean',
                'quy_tac'    => 'nullable|string',
                'mo_ta'      => 'nullable|string',
            ], [
                'ten.required' => 'Vui lòng nhập tên loại nghỉ phép.',
                'ma.required'  => 'Vui lòng nhập mã loại nghỉ phép.',
                'ma.unique'    => 'Mã loại nghỉ phép này đã tồn tại.',
            ]);

            LoaiNghiPhep::create($validated);

            return redirect()
                ->route('admin.loai-nghi-phep.index')
                ->with('success', 'Thêm mới loại nghỉ phép thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết loại nghỉ phép
     */
    public function show($id)
    {
        $loaiNghi = LoaiNghiPhep::findOrFail($id);
        return view('admin.loai_nghi_phep.show', compact('loaiNghi'));
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id)
    {
        $loaiNghi = LoaiNghiPhep::findOrFail($id);
        return view('admin.loai_nghi_phep.edit', compact('loaiNghi'));
    }

    /**
     * Cập nhật loại nghỉ phép
     */
    public function update(Request $request, $id)
    {
        try {
            $loaiNghi = LoaiNghiPhep::findOrFail($id);

            // ⭐ SỬA: Bỏ required cho trường ma vì nó đang là readonly
            $validated = $request->validate([
                'ten'        => 'required|string|max:255',
                // KHÔNG validate ma vì nó là readonly
                'co_luong'   => 'required|boolean',
                'trang_thai' => 'required|boolean',
                'quy_tac'    => 'nullable|string',
                'mo_ta'      => 'nullable|string',
            ], [
                'ten.required' => 'Vui lòng nhập tên loại nghỉ phép.',
            ]);

            $loaiNghi->update($validated);

            return redirect()
                ->route('admin.loai-nghi-phep.index')
                ->with('success', '✅ Cập nhật loại nghỉ phép thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa loại nghỉ phép
     */
    public function destroy($id)
    {
        try {
            $loaiNghi = LoaiNghiPhep::findOrFail($id);

            // Kiểm tra xem loại nghỉ này có đang được sử dụng không
            $isUsed = \App\Models\DonXinNghi::where('loai_nghi_phep_id', $id)->exists();

            if ($isUsed) {
                return redirect()
                    ->route('admin.loai-nghi-phep.index')
                    ->with('error', 'Không thể xóa vì loại nghỉ phép này đang được sử dụng trong các đơn xin nghỉ!');
            }

            $loaiNghi->delete();

            return redirect()
                ->route('admin.loai-nghi-phep.index')
                ->with('success', 'Xóa loại nghỉ phép thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.loai-nghi-phep.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

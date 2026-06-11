<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaiTro;
use Illuminate\Http\Request;

class VaiTroController extends Controller
{
    // Danh sách vai trò
    public function index(Request $request)
    {
        $query = VaiTro::query();

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                    ->orWhere('ten_hien_thi', 'like', "%{$request->keyword}%")
                    ->orWhere('mo_ta', 'like', "%{$request->keyword}%");
            });
        }

        $vaiTros = $query->withCount('nguoi_dungs')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.vai_tro.index', compact('vaiTros'));
    }

    // Form tạo mới
    public function create()
    {
        return view('admin.vai_tro.create');
    }

    // Lưu vai trò mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vai_tro,name',
            'ten_hien_thi' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        VaiTro::create([
            'name' => $request->name,
            'ten_hien_thi' => $request->ten_hien_thi,
            'mo_ta' => $request->mo_ta,
            'la_vai_tro_he_thong' => 0,
            'trang_thai' => $request->trang_thai ?? 1,
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Thêm vai trò thành công!');
    }

    // Xem chi tiết
    public function show($id)
    {
        $vaiTro = VaiTro::with('nguoi_dungs')->findOrFail($id);

        return view('admin.vai_tro.show', compact('vaiTro'));
    }

    // Form sửa
    public function edit($id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        return view('admin.vai_tro.edit', compact('vaiTro'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        // Không cho sửa vai trò hệ thống
        if ($vaiTro->la_vai_tro_he_thong == 1) {
            return back()->with('error', 'Không thể sửa vai trò hệ thống!');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:vai_tro,name,' . $id,
            'ten_hien_thi' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        $vaiTro->update($request->only(['name', 'ten_hien_thi', 'mo_ta', 'trang_thai']));

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Cập nhật vai trò thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        // Không cho xóa vai trò hệ thống
        if ($vaiTro->la_vai_tro_he_thong == 1) {
            return back()->with('error', 'Không thể xóa vai trò hệ thống!');
        }

        // Kiểm tra xem có người dùng nào đang dùng vai trò này không
        if ($vaiTro->nguoi_dungs()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì đang có người dùng thuộc vai trò này!');
        }

        $vaiTro->delete();

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Xóa vai trò thành công!');
    }
}

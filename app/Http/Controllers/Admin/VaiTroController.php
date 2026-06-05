<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VaiTroController extends Controller
{
    // Hiển thị danh sách vai trò
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $vaiTros = DB::table('vai_tro')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('ten_hien_thi', 'LIKE', "%{$keyword}%")
                    ->orWhere('mo_ta', 'LIKE', "%{$keyword}%");
            })
            ->get(); // Hoặc ->paginate(10); nếu muốn phân trang

        return view('admin.vai_tro.index', compact('vaiTros'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('admin.vai_tro.create');
    }

    // Lưu vai trò mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vai_tro,name',
            'ten_hien_thi' => 'required',
        ]);

        DB::table('vai_tro')->insert([
            'name' => $request->name,
            'ten_hien_thi' => $request->ten_hien_thi,
            'mo_ta' => $request->mo_ta,
            'la_vai_tro_he_thong' => 0,
            'trang_thai' => $request->trang_thai ?? 1,
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('vai-tro.index')->with('success', 'Thêm vai trò thành công!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VaiTroController extends Controller
{
    // Hiển thị danh sách vai trò
    public function index()
    {
        $vaiTros = DB::table('vai_tro')->get();
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
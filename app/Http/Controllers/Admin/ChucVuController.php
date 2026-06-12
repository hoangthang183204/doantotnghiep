<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucVu;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{

    public function index()
    {
        $chucVus = ChucVu::with('phong_ban')
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
        // Đã gỡ bỏ validate của 'luong_co_ban' và 'he_so_luong'
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'required|string|max:255|unique:chuc_vu,ma',
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        ChucVu::create($request->all());

        return redirect()
            ->route('admin.chuc-vu.index')
            ->with('success', 'Thêm chức vụ thành công');
    }

    public function show($id)
    {
        $chucVu = ChucVu::with(['phong_ban', 'nguoi_dungs'])->findOrFail($id);

        return view('admin.chuc-vu.show', compact('chucVu'));
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

        // Đã gỡ bỏ validate của 'luong_co_ban' và 'he_so_luong'
        $request->validate([
            'ten' => 'required|string|max:255',
            'ma' => 'required|string|max:255|unique:chuc_vu,ma,' . $id,
            'phong_ban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'boolean',
        ]);

        $chucVu->update($request->all());

        return redirect()
            ->route('admin.chuc-vu.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
{
    $chucVu = ChucVu::findOrFail($id);
    
    // Nếu trạng thái đang là 1 thì chuyển thành 0, ngược lại đang 0 thì chuyển thành 1
    $newStatus = $chucVu->trang_thai == 1 ? 0 : 1;
    
    $chucVu->update([
        'trang_thai' => $newStatus
    ]);

    // Tùy chỉnh câu thông báo trả về
    $message = $newStatus == 1 ? 'Đã hiển thị lại chức vụ thành công' : 'Đã ẩn chức vụ thành công';

    return back()->with('success', $message);
}
}
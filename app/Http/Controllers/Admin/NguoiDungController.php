<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PhongBan;
use App\Models\VaiTro;
use App\Models\ChucVu;

class NguoiDungController extends Controller
{
    // Danh sách user
    public function index(Request $request)
    {
        $query = NguoiDung::with(['vai_tro', 'phong_ban']);

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('ten_dang_nhap', 'like', "%{$request->keyword}%")
                    ->orWhere('email', 'like', "%{$request->keyword}%");
            });
        }

        if ($request->phong_ban_id) {
            $query->where('phong_ban_id', $request->phong_ban_id);
        }

        if ($request->trang_thai !== null && $request->trang_thai !== '') {
            $query->where('trang_thai', $request->trang_thai);
        }

        $users = $query->latest()->paginate(10);

        // 🔥 THIẾU CHỖ NÀY LÀ GÂY LỖI
        $phongBans = PhongBan::all();

        return view('admin.nguoi-dung.index', compact('users', 'phongBans'));
    }
    // Form tạo

    public function create()
    {
        $vaiTros = VaiTro::all();
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        return view('admin.nguoi-dung.create', compact(
            'vaiTros',
            'phongBans',
            'chucVus'
        ));
    }

    // Lưu user
    public function store(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:nguoi_dung',
            'email' => 'required|email|unique:nguoi_dung',
            'password' => 'required|min:6',
        ]);

        NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            'vai_tro_id' => $request->vai_tro_id,
            'phong_ban_id' => $request->phong_ban_id,   
            'chuc_vu_id' => $request->chuc_vu_id,       

            'trang_thai' => 1,
        ]);

        return redirect()->route('admin.nguoi-dung.index')
            ->with('success', 'Tạo người dùng thành công');
    }

    // Chi tiết
    public function show($id)
    {
        $user = NguoiDung::with(['vai_tro', 'phong_ban', 'chuc_vu'])->findOrFail($id);

        return view('admin.nguoi-dung.show', compact('user'));
    }

    // Form sửa
    public function edit($id)
    {
        $user = NguoiDung::findOrFail($id);

        $vaiTros = VaiTro::all();
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        return view('admin.nguoi-dung.edit', compact(
            'user',
            'vaiTros',
            'phongBans',
            'chucVus'
        ));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $user = NguoiDung::findOrFail($id);

        $request->validate([
            'ten_dang_nhap' => 'required',
            'email' => 'required|email',
        ]);

        $data = [
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email' => $request->email,
            'vai_tro_id' => $request->vai_tro_id,
            'phong_ban_id' => $request->phong_ban_id,
            'chuc_vu_id' => $request->chuc_vu_id,
            'trang_thai' => $request->trang_thai ?? 1,
        ];

        // chỉ update password nếu có nhập
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.nguoi-dung.index')
            ->with('success', 'Cập nhật người dùng thành công');
    }

    // Xóa
    public function destroy($id)
    {
        NguoiDung::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa người dùng');
    }
}

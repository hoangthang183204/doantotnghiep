<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\HoSoNguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PhongBan;
use App\Models\VaiTro;
use App\Models\ChucVu;

class NguoiDungController extends Controller
{
    // Danh sách user
    // Danh sách user
    public function index(Request $request)
    {
        $query = NguoiDung::with(['vai_tro', 'phong_ban', 'hoSo']);

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('ten_dang_nhap', 'like', "%{$request->keyword}%")
                    ->orWhere('email', 'like', "%{$request->keyword}%")
                    // ⭐ THÊM TÌM THEO MÃ NV, HỌ TÊN
                    ->orWhereHas('hoSo', function ($hs) use ($request) {
                        $hs->where('ma_nhan_vien', 'like', "%{$request->keyword}%")
                            ->orWhere('ho', 'like', "%{$request->keyword}%")
                            ->orWhere('ten', 'like', "%{$request->keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$request->keyword}%"]);
                    });
            });
        }

        // ⭐ THÊM LỌC THEO VAI TRÒ
        if ($request->vai_tro_id) {
            $query->where('vai_tro_id', $request->vai_tro_id);
        }

        if ($request->phong_ban_id) {
            $query->where('phong_ban_id', $request->phong_ban_id);
        }

        if ($request->trang_thai !== null && $request->trang_thai !== '') {
            $query->where('trang_thai', $request->trang_thai);
        }

        $users = $query->latest()->paginate(10);

        // ⭐ THÊM 2 DÒNG NÀY ĐỂ LẤY DỮ LIỆU CHO BỘ LỌC
        $vaiTros = VaiTro::all();
        $phongBans = PhongBan::all();

        // ⭐ THÊM $vaiTros VÀO COMPACT
        return view('admin.nguoi-dung.index', compact('users', 'vaiTros', 'phongBans'));
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

    // Lưu user - TỰ ĐỘNG TẠO HỒ SƠ
    public function store(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:nguoi_dung',
            'email' => 'required|email|unique:nguoi_dung',
            'password' => 'required|min:6',
            'vai_tro_id' => 'nullable|exists:vai_tro,id',
            'phong_ban_id' => 'nullable|exists:phong_ban,id',
            'chuc_vu_id' => 'nullable|exists:chuc_vu,id',
        ]);

        // Tạo user
        $user = NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phong_ban_id' => $request->phong_ban_id,
            'chuc_vu_id' => $request->chuc_vu_id,
            'trang_thai' => 1,
            'trang_thai_cong_viec' => 'dang_lam',
        ]);

        // ⭐ QUAN TRỌNG: Gán vai trò cho user
        if ($request->filled('vai_tro_id')) {
            $user->vaiTros()->attach($request->vai_tro_id);
        }

        // Tạo hồ sơ
        $lastId = HoSoNguoiDung::max('id') ?? 0;
        $maNhanVien = 'NV' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        HoSoNguoiDung::create([
            'nguoi_dung_id' => $user->id,
            'ma_nhan_vien' => $maNhanVien,
            'ho' => '',
            'ten' => $request->ten_dang_nhap,
            'so_dien_thoai' => null,
            'ngay_sinh' => null,
            'gioi_tinh' => null,
            'dia_chi_hien_tai' => null,
            'dia_chi_thuong_tru' => null,
            'cmnd_cccd' => null,
            'so_ho_chieu' => null,
            'tinh_trang_hon_nhan' => null,
            'lien_he_khan_cap' => null,
            'sdt_khan_cap' => null,
            'quan_he_khan_cap' => null,
        ]);

        return redirect()->route('admin.nguoi-dung.index')
            ->with('success', 'Tạo người dùng thành công! Hồ sơ đã được tự động tạo.');
    }

    // Chi tiết
    public function show($id)
    {
        $user = NguoiDung::with(['vai_tro', 'phong_ban', 'chuc_vu', 'hoSo'])->findOrFail($id);

        return view('admin.nguoi-dung.show', compact('user'));
    }

    // Form sửa
    public function edit($id)
    {
        $user = NguoiDung::with('hoSo')->findOrFail($id);
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
            'ten_dang_nhap' => 'required|unique:nguoi_dung,ten_dang_nhap,' . $id,
            'email' => 'required|email|unique:nguoi_dung,email,' . $id,
            'vai_tro_id' => 'nullable|exists:vai_tro,id',
        ]);

        $data = [
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email' => $request->email,
            'phong_ban_id' => $request->phong_ban_id,
            'chuc_vu_id' => $request->chuc_vu_id,
            'trang_thai' => $request->trang_thai ?? 1,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        // ⭐ QUAN TRỌNG: Cập nhật vai trò vào bảng nguoi_dung_vai_tro
        if ($request->filled('vai_tro_id')) {
            // Xóa tất cả role cũ và gán role mới
            $user->vaiTros()->sync([$request->vai_tro_id]);
        } else {
            // Nếu không chọn role nào thì xóa hết
            $user->vaiTros()->detach();
        }

        // Cập nhật tên hồ sơ nếu có thay đổi
        if ($user->hoSo && $request->ten_dang_nhap != $user->getOriginal('ten_dang_nhap')) {
            $user->hoSo->update(['ten' => $request->ten_dang_nhap]);
        }

        return redirect()->route('admin.nguoi-dung.index')
            ->with('success', 'Cập nhật người dùng thành công');
    }

    // Xóa - Xóa cả hồ sơ (cascade đã được set trong migration)
    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);

        // Xóa user (hồ sơ sẽ tự xóa do cascadeOnDelete)
        $user->delete();

        return back()->with('success', 'Đã xóa người dùng và hồ sơ liên quan');
    }

    /**
     * Đồng bộ hồ sơ cho user cũ
     */
    public function syncHoSo()
    {
        $users = NguoiDung::doesntHave('hoSo')->get();
        $count = 0;

        foreach ($users as $user) {
            $lastId = HoSoNguoiDung::max('id') ?? 0;
            $maNhanVien = 'NV' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            HoSoNguoiDung::create([
                'nguoi_dung_id' => $user->id,
                'ma_nhan_vien' => $maNhanVien,
                'ho' => '',
                'ten' => $user->ten_dang_nhap,
                'so_dien_thoai' => null,
                'ngay_sinh' => null,
                'gioi_tinh' => null,
                'dia_chi_hien_tai' => null,
                'dia_chi_thuong_tru' => null,
                'cmnd_cccd' => null,
                'so_ho_chieu' => null,
                'tinh_trang_hon_nhan' => null,
                'lien_he_khan_cap' => null,
                'sdt_khan_cap' => null,
                'quan_he_khan_cap' => null,
            ]);
            $count++;
        }

        return redirect()->route('admin.nguoi-dung.index')
            ->with('success', "Đã đồng bộ {$count} hồ sơ cho user chưa có hồ sơ.");
    }
}

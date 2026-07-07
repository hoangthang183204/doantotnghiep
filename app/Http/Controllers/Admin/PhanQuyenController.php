<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaiTro;
use App\Models\Quyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PhanQuyenController extends Controller
{
    public function index()
    {
        $roles = VaiTro::with('quyens')->get();
        return view('admin.phan-quyen.index', compact('roles'));
    }

    public function edit($id)
    {
        $role = VaiTro::with('quyens')->findOrFail($id);
        $permissions = Quyen::orderBy('nhom')->orderBy('id')->get()->groupBy('nhom');

        return view('admin.phan-quyen.edit', compact('role', 'permissions'));
    }

    // app/Http/Controllers/Admin/PhanQuyenController.php

    public function update(Request $request, $id)
    {
        $role = VaiTro::findOrFail($id);

        // ⭐ CHO PHÉP SUPER ADMIN TỰ PHÂN QUYỀN CHO CHÍNH MÌNH
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->vaiTros()->where('name', 'Super Admin')->exists();
        $isSelfUpdate = ($role->name === 'Super Admin' && $isSuperAdmin);

        // ❌ CHỈ CHẶN KHI KHÔNG PHẢI TỰ PHÂN QUYỀN
        if ($role->la_vai_tro_he_thong && !$isSelfUpdate) {
            return redirect()->route('admin.phan-quyen.index')
                ->with('error', 'Không thể phân quyền cho vai trò hệ thống!');
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:quyen,id'
        ]);

        $role->quyens()->sync($request->permissions ?? []);

        return redirect()->route('admin.phan-quyen.index')
            ->with('success', 'Phân quyền cho vai trò "' . $role->ten_hien_thi . '" thành công!');
    }
}

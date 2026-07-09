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
        
        // ⭐ Gợi ý quyền mặc định cho từng vai trò
        $suggestedPermissions = $this->getSuggestedPermissions();
        $suggested = $suggestedPermissions[$role->name] ?? [];
        
        // ⭐ Phân loại nhóm quyền
        $groupTypes = $this->getGroupTypes();
        
        return view('admin.phan-quyen.edit', compact('role', 'permissions', 'suggested', 'groupTypes'));
    }

    public function update(Request $request, $id)
    {
        $role = VaiTro::findOrFail($id);
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->vaiTros()->where('name', 'Super Admin')->exists();

        // ⭐ CHỈ SUPER ADMIN MỚI ĐƯỢC PHÂN QUYỀN HỆ THỐNG
        $hasSystemPermission = false;
        if ($request->has('permissions')) {
            $systemPermissions = Quyen::where('nhom', 'system')->pluck('id')->toArray();
            foreach ($request->permissions as $permId) {
                if (in_array($permId, $systemPermissions)) {
                    $hasSystemPermission = true;
                    break;
                }
            }
        }

        if ($hasSystemPermission && !$isSuperAdmin) {
            return redirect()->route('admin.phan-quyen.index')
                ->with('error', 'Chỉ Super Admin mới có thể phân quyền hệ thống!');
        }

        // ⭐ BẢO VỆ VAI TRÒ SUPER ADMIN
        if ($role->name === 'Super Admin' && !$isSuperAdmin) {
            return redirect()->route('admin.phan-quyen.index')
                ->with('error', 'Không thể thay đổi quyền của Super Admin!');
        }

        // ⭐ BẢO VỆ VAI TRÒ HỆ THỐNG
        if ($role->la_vai_tro_he_thong && !$isSuperAdmin) {
            return redirect()->route('admin.phan-quyen.index')
                ->with('error', 'Không thể phân quyền cho vai trò hệ thống!');
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:quyen,id'
        ]);

        $permissions = $request->permissions ?? [];
        
        // ⭐ TỰ ĐỘNG THÊM QUYỀN CƠ BẢN CHO NHÂN VIÊN
        if ($role->name === 'Employee') {
            $basicPermissions = Quyen::whereIn('name', [
                'dashboard.employee',
                'attendance.checkin',
                'attendance.checkout',
                'attendance.history',
                'personal.profile',
                'personal.update',
                'personal.password',
                'personal.regulation'
            ])->pluck('id')->toArray();
            $permissions = array_merge($permissions, $basicPermissions);
        }

        $role->quyens()->sync(array_unique($permissions));

        return redirect()->route('admin.phan-quyen.index')
            ->with('success', 'Phân quyền cho "' . $role->ten_hien_thi . '" thành công!');
    }

    /**
     * ⭐ Lấy gợi ý quyền mặc định cho từng vai trò
     */
    private function getSuggestedPermissions()
    {
        return [
            'Super Admin' => Quyen::pluck('id')->toArray(),
            'Admin' => Quyen::whereNotIn('nhom', ['system'])->pluck('id')->toArray(),
            'Manager' => Quyen::whereIn('nhom', [
                'dashboard', 'employee', 'department', 'position', 
                'attendance', 'leave', 'overtime', 'adjustment'
            ])->where(function($q) {
                $q->where('name', 'like', '%.view%')
                  ->orWhere('name', 'like', '%.admin.%')
                  ->orWhere('name', 'like', '%.approve%');
            })->pluck('id')->toArray(),
            'Employee' => Quyen::whereIn('nhom', [
                'dashboard', 'attendance', 'leave', 'overtime', 'adjustment', 'personal'
            ])->where(function($q) {
                $q->where('name', 'not like', '%.admin.%')
                  ->orWhere('name', 'like', '%.checkin%')
                  ->orWhere('name', 'like', '%.checkout%')
                  ->orWhere('name', 'like', '%.history%');
            })->pluck('id')->toArray(),
        ];
    }

    /**
     * ⭐ Phân loại nhóm quyền
     */
    private function getGroupTypes()
    {
        return [
            'system' => ['system', 'role', 'permission', 'setting'],
            'admin' => ['admin', 'department', 'salary', 'contract', 'attendance.admin', 'leave.admin', 'overtime.admin', 'adjustment.admin'],
            'employee' => ['attendance', 'leave', 'overtime', 'adjustment', 'personal', 'profile'],
        ];
    }
}
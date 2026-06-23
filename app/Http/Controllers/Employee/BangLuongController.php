<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LuongNhanVien;
use Illuminate\Support\Facades\Auth;

class BangLuongController extends Controller
{
    // Danh sách phiếu lương của nhân viên
    public function index()
    {
        $userId = Auth::id();

        $payrolls = LuongNhanVien::with(['bangLuong'])
            ->where('nguoi_dung_id', $userId)
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->paginate(10); // số bản ghi mỗi trang

        return view('employee.bang-luong.index', compact('payrolls'));
    }

    // Chi tiết phiếu lương
    public function show($id)
    {
        $userId = Auth::id();

        $payroll = LuongNhanVien::with(['bangLuong', 'khauTrus'])
            ->where('nguoi_dung_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        return view('employee.bang-luong.show', compact('payroll'));
    }
}

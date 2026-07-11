<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LuongNhanVien;
use Illuminate\Support\Facades\Auth;

class BangLuongController extends Controller
{
    // Danh sách bảng lương theo NĂM
    public function index()
    {
        $userId = Auth::id();

        $years = LuongNhanVien::where('nguoi_dung_id', $userId)
            ->selectRaw('
                luong_nam,
                COUNT(*) as so_thang,
                SUM(tong_luong) as tong_luong,
                SUM(tong_khau_tru) as tong_khau_tru,
                SUM(luong_thuc_nhan) as tong_thuc_nhan
            ')
            ->groupBy('luong_nam')
            ->orderByDesc('luong_nam')
            ->get();

        return view('employee.bang-luong.index', compact('years'));
    }

    // Danh sách các THÁNG trong một năm
    public function year($year)
    {
        $userId = Auth::id();

        $payrolls = LuongNhanVien::with(['bangLuong', 'hoSo'])
            ->where('nguoi_dung_id', $userId)
            ->where('luong_nam', $year)
            ->orderByDesc('luong_thang')
            ->get();

        abort_if($payrolls->isEmpty(), 404);

        return view(
            'employee.bang-luong.year',
            compact('payrolls', 'year')
        );
    }

    // Chi tiết phiếu lương từng tháng
    public function show($id)
    {
        $userId = Auth::id();

        $payroll = LuongNhanVien::with([
            'bangLuong',
            'khauTrus'
        ])
            ->where('nguoi_dung_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        return view(
            'employee.bang-luong.show',
            compact('payroll')
        );
    }
}
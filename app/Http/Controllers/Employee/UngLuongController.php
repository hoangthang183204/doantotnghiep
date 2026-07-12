<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\KhauTruKhac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UngLuongController extends Controller
{
    public function index()
    {
        $danhSach = KhauTruKhac::where('nguoi_dung_id', Auth::id())
            ->where('loai', 'tam_ung')
            ->latest()
            ->get();

        return view(
            'employee.ung-luong.index',
            compact('danhSach')
        );
    }

    public function create()
    {
        return view('employee.ung-luong.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'so_tien' => 'required|numeric|min:100000',
            'ly_do'   => 'required|max:255',
        ]);

        KhauTruKhac::create([
            'nguoi_dung_id' => Auth::id(),
            'thang'         => now()->month,
            'nam'           => now()->year,
            'loai'          => 'tam_ung',
            'so_tien'       => $request->so_tien,
            'ly_do'         => $request->ly_do,

            // Tạm dùng "huy" để biểu thị CHỜ DUYỆT
            // Khi admin duyệt sẽ đổi sang "hieu_luc"
            'trang_thai'    => 'huy',

            'nguoi_tao_id'  => Auth::id(),
        ]);

        return redirect()
            ->route('employee.ung-luong.index')
            ->with('success', 'Đã gửi yêu cầu ứng lương.');
    }

    public function cancel($id)
    {
        $ungLuong = KhauTruKhac::where('id', $id)
            ->where('nguoi_dung_id', Auth::id())
            ->where('loai', 'tam_ung')
            ->firstOrFail();

        // Chỉ được hủy khi còn chờ duyệt
        if ($ungLuong->trang_thai != 'huy') {
            return back()->with('error', 'Yêu cầu này đã được duyệt nên không thể hủy.');
        }

        $ungLuong->delete();

        return redirect()
            ->route('employee.ung-luong.index')
            ->with('success', 'Đã hủy yêu cầu ứng lương.');
    }
}
<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\KhauTruKhac;
use App\Models\LuongNhanVien;
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
        $luong = LuongNhanVien::where('nguoi_dung_id', Auth::id())
            ->orderByDesc('luong_nam')
            ->orderByDesc('luong_thang')
            ->first();

        $gioiHan = $luong ? $luong->luong_thuc_nhan : 0;

        return view('employee.ung-luong.create', compact('gioiHan'));
    }

    public function store(Request $request)
    {
        $luong = LuongNhanVien::where('nguoi_dung_id', Auth::id())
                ->orderByDesc('luong_nam')
                ->orderByDesc('luong_thang')
                ->first();

            if (!$luong) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'so_tien' => 'Bạn chưa có bảng lương nên chưa thể ứng lương.'
                    ]);
            }

            // Được ứng tối đa bằng 1 tháng lương thực nhận
            $gioiHan = $luong->luong_thuc_nhan;

            $request->validate([
                'so_tien' => [
                    'required',
                    'numeric',
                    'min:100000',
                    function ($attribute, $value, $fail) use ($gioiHan) {
                        if ($value > $gioiHan) {
                            $fail('Bạn chỉ được ứng tối đa ' .
                                number_format($gioiHan,0,',','.') .
                                ' VNĐ (1 tháng lương).');
                        }
                    },
                ],
                'ly_do' => 'required|max:255',
            ]);

        KhauTruKhac::create([
            'nguoi_dung_id' => Auth::id(),
            'thang'         => now()->month,
            'nam'           => now()->year,
            'loai'          => 'tam_ung',
            'so_tien'       => $request->so_tien,
            'ly_do'         => $request->ly_do,
            'trang_thai' => 'huy',
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
        if ($ungLuong->trang_thai != 'cho_duyet') {
            return back()->with('error', 'Chỉ yêu cầu đang chờ duyệt mới được hủy.');
                }

                // Không nên xóa luôn
                $ungLuong->update([
                    'trang_thai' => 'huy'
                ]);

                return redirect()
                    ->route('employee.ung-luong.index')
                    ->with('success', 'Đã hủy yêu cầu ứng lương.');
    }
}
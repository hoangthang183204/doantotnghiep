<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LuongNhanVien;
use App\Models\YeuCauXemXetLuong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YeuCauXemXetLuongController extends Controller
{
    // Form tạo yêu cầu
    public function create($luongId)
    {
        $luong = LuongNhanVien::where('id', $luongId)
            ->where('nguoi_dung_id', Auth::id())
            ->firstOrFail();

        return view('employee.yeu-cau-luong.create', compact('luong'));
    }

    // Lưu yêu cầu
    public function store(Request $request, $luongId)
    {
        $request->validate([
            'ly_do' => 'required|string|min:10|max:1000',
        ]);

        $luong = LuongNhanVien::where('id', $luongId)
            ->where('nguoi_dung_id', Auth::id())
            ->firstOrFail();

        // Không cho gửi nhiều yêu cầu khi còn chờ
        $exists = YeuCauXemXetLuong::where('luong_nhan_vien_id', $luong->id)
            ->where('trang_thai', 'cho_duyet')
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Phiếu lương này đã có yêu cầu đang chờ xử lý.'
            );
        }

        YeuCauXemXetLuong::create([
            'luong_nhan_vien_id' => $luong->id,
            'nguoi_dung_id'      => Auth::id(),
            'ly_do'              => $request->ly_do,
        ]);

        return redirect()
            ->route('employee.bang-luong.show', $luong->id)
            ->with('success', 'Đã gửi yêu cầu xem xét lương.');
    }
}
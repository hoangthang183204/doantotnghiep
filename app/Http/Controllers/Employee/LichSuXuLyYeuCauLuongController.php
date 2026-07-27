<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\YeuCauXemXetLuong;
use Illuminate\Support\Facades\Auth;

class LichSuXuLyYeuCauLuongController extends Controller
{
    public function index()
    {
        $lichSu = YeuCauXemXetLuong::with([
            'nguoiDuyet.ho_so',
            'lichSuXuLy.nguoiThucHien'
        ])
        ->where('nguoi_dung_id', auth()->id())
        ->latest()
        ->paginate(5); // mỗi trang 5 yêu cầu


        return view(
            'employee.lich-su-xu-ly-yeu-cau-luong.index',
            compact('lichSu')
        );
    }
}
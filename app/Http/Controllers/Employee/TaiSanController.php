<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CapPhatTaiSan;
use Illuminate\Support\Facades\Auth;

class TaiSanController extends Controller
{
    public function index()
    {
        $dangGiu = CapPhatTaiSan::with('taiSan')
            ->where('nguoi_dung_id', Auth::id())
            ->where('trang_thai', 'dang_cap')
            ->orderByDesc('ngay_cap')
            ->get();

        $lichSu = CapPhatTaiSan::with('taiSan')
            ->where('nguoi_dung_id', Auth::id())
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        return view('employee.tai-san.index', compact('dangGiu', 'lichSu'));
    }
}

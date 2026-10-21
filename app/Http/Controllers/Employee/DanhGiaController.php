<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\KetQuaDanhGia;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    public function index()
    {
        $ketQuas = KetQuaDanhGia::with(['ky', 'mucTieus'])
            ->where('nguoi_dung_id', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('employee.danh-gia.index', compact('ketQuas'));
    }
}

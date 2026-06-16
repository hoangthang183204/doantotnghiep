<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonXinNghi;
use App\Models\LichSuDuyetDonNghi;
class LichSuDuyetDonNghiController extends Controller
{
    public function show($id)
{
    $lichSu = LichSuDuyetDonNghi::with([
        'donXinNghi',
        'nguoiDuyet.hoSo'
    ])->findOrFail($id);

    return view(
        'admin.lich-su-duyet.show',
        compact('lichSu')
    );
}
    public function index()
{
    $lichSus = LichSuDuyetDonNghi::with([
        'donXinNghi',
        'nguoiDuyet.hoSo'
    ])
    ->latest('thoi_gian_duyet')
    ->paginate(10);

    return view(
        'admin.lich-su-duyet.index',
        compact('lichSus')
    );
}
}
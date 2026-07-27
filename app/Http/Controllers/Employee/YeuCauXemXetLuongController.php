<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LuongNhanVien;
use App\Models\YeuCauXemXetLuong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LichSuXuLyYeuCauLuong;

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
        'loai_sai_sot' => 'required|array|min:1',
        'loai_sai_sot.*' => 'in:cham_cong,tang_ca,phu_cap,khau_tru',
        'ly_do' => 'required|string|max:1000',
    ]);

    $luong = LuongNhanVien::where('id', $luongId)
        ->where('nguoi_dung_id', Auth::id())
        ->firstOrFail();

    // Không cho gửi nhiều yêu cầu đang chờ
    $exists = YeuCauXemXetLuong::where('luong_nhan_vien_id', $luong->id)
        ->where('trang_thai', 'cho_duyet')
        ->exists();

    if ($exists) {
        return back()->with(
            'error',
            'Phiếu lương này đã có yêu cầu đang chờ xử lý.'
        );
    }

    $selected = $request->loai_sai_sot;
    sort($selected);

    $key = implode(',', $selected);

    switch ($key) {

        // 1 lỗi
        case 'cham_cong':
            $loaiSaiSot = 'cham_cong';
            break;

        case 'tang_ca':
            $loaiSaiSot = 'tang_ca';
            break;

        case 'phu_cap':
            $loaiSaiSot = 'phu_cap';
            break;

        case 'khau_tru':
            $loaiSaiSot = 'khau_tru';
            break;

        // 2 lỗi
        case 'cham_cong,tang_ca':
            $loaiSaiSot = 'cham_cong_tang_ca';
            break;

        case 'cham_cong,phu_cap':
            $loaiSaiSot = 'cham_cong_phu_cap';
            break;

        case 'cham_cong,khau_tru':
            $loaiSaiSot = 'cham_cong_khau_tru';
            break;

        case 'phu_cap,tang_ca':
            $loaiSaiSot = 'tang_ca_phu_cap';
            break;

        case 'khau_tru,tang_ca':
            $loaiSaiSot = 'tang_ca_khau_tru';
            break;

        case 'khau_tru,phu_cap':
            $loaiSaiSot = 'phu_cap_khau_tru';
            break;

        // 3 lỗi
        case 'cham_cong,phu_cap,tang_ca':
            $loaiSaiSot = 'cham_cong_tang_ca_phu_cap';
            break;

        case 'cham_cong,khau_tru,tang_ca':
            $loaiSaiSot = 'cham_cong_tang_ca_khau_tru';
            break;

        case 'cham_cong,khau_tru,phu_cap':
            $loaiSaiSot = 'cham_cong_phu_cap_khau_tru';
            break;

        case 'khau_tru,phu_cap,tang_ca':
            $loaiSaiSot = 'tang_ca_phu_cap_khau_tru';
            break;

        // 4 lỗi
        case 'cham_cong,khau_tru,phu_cap,tang_ca':
            $loaiSaiSot = 'tat_ca';
            break;

        default:
            return back()
                ->withInput()
                ->withErrors([
                    'loai_sai_sot' => 'Loại sai sót không hợp lệ.'
                ]);
    }
   $yeuCau = YeuCauXemXetLuong::create([
        'luong_nhan_vien_id' => $luong->id,
        'nguoi_dung_id'      => Auth::id(),
        'loai_sai_sot'       => $loaiSaiSot,
        'ly_do'              => $request->ly_do,
        'trang_thai'         => 'cho_duyet',
        'phan_hoi'           => null,
        'nguoi_duyet_id'     => null,
        'thoi_gian_duyet'    => null,
    ]);
LichSuXuLyYeuCauLuong::create([

    'yeu_cau_xem_xet_luong_id' => $yeuCau->id,

    'nguoi_thuc_hien_id' => Auth::id(),

    'hanh_dong' => 'tao',

    'du_lieu_cu' => null,

    'du_lieu_moi' => $yeuCau->toArray(),

    'ghi_chu' => 'Nhân viên tạo yêu cầu xem xét lương',

    'thoi_gian' => now(),

]);
    return redirect()
        ->route('employee.bang-luong.show', $luong->id)
        ->with('success', 'Đã gửi yêu cầu xem xét lương.');
}
}
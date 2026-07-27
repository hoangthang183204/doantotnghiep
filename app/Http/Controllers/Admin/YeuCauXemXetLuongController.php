<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YeuCauXemXetLuong;
use Illuminate\Http\Request;
use App\Models\HopDongLaoDong;
use App\Models\KhauTruLuong;
use App\Models\PhuCapLuong;
use App\Services\TinhLuongService;
use Illuminate\Support\Facades\DB;
use App\Models\LichSuXuLyYeuCauLuong;

class YeuCauXemXetLuongController extends Controller
{
    protected TinhLuongService $tinhLuong;

    public function __construct(TinhLuongService $tinhLuong)
    {
        $this->tinhLuong = $tinhLuong;
    }
    public function index()
    {
        $yeuCaus = YeuCauXemXetLuong::with([
            'nguoiDung.ho_so',
            'luongNhanVien.bangLuong'
        ])
        ->latest()
        ->paginate(10);

        return view('admin.yeu-cau-luong.index', compact('yeuCaus'));
    }

   public function show($id)
{
    $yeuCau = YeuCauXemXetLuong::with([

        'nguoiDung.ho_so',

        'luongNhanVien.bangLuong',

        'nguoiDuyet.ho_so',

        'lichSuXuLy.nguoiThucHien.ho_so',

    ])->findOrFail($id);


    return view(
        'admin.yeu-cau-luong.show',
        compact('yeuCau')
    );
}

public function duyet($id)
{
    $yeuCau = YeuCauXemXetLuong::findOrFail($id);


    // lưu dữ liệu cũ trước khi thay đổi
    $duLieuCu = $yeuCau->toArray();



    // cập nhật yêu cầu
    $yeuCau->update([

        'trang_thai'      => 'da_duyet',

        'nguoi_duyet_id'  => auth()->id(),

        'thoi_gian_duyet' => now(),

    ]);



    // ghi lịch sử xử lý

    LichSuXuLyYeuCauLuong::create([

        'yeu_cau_xem_xet_luong_id' => $yeuCau->id,

        'nguoi_thuc_hien_id' => auth()->id(),

        'hanh_dong' => 'duyet',

        'du_lieu_cu' => $duLieuCu,

        'du_lieu_moi' => $yeuCau->fresh()->toArray(),

        'ghi_chu' => 'Admin đã duyệt yêu cầu xem xét lương',

        'thoi_gian' => now(),

    ]);



    return redirect()

        ->route('admin.yeu-cau-luong.show', $id)

        ->with('success', 'Đã duyệt yêu cầu.');

}

   public function tuChoi(Request $request, $id)
{
    $yeuCau = YeuCauXemXetLuong::findOrFail($id);


    $duLieuCu = $yeuCau->toArray();


    $yeuCau->update([

        'trang_thai' => 'tu_choi',

        'nguoi_duyet_id' => auth()->id(),

        'thoi_gian_duyet' => now(),

        'phan_hoi' => $request->ghi_chu,

    ]);



    LichSuXuLyYeuCauLuong::create([

        'yeu_cau_xem_xet_luong_id' => $yeuCau->id,

        'nguoi_thuc_hien_id' => auth()->id(),

        'hanh_dong' => 'tu_choi',

        'du_lieu_cu' => $duLieuCu,

        'du_lieu_moi' => $yeuCau->fresh()->toArray(),

        'ghi_chu' => $request->ghi_chu
            ?? 'Admin từ chối yêu cầu',

        'thoi_gian' => now(),

    ]);


    return redirect()

        ->route('admin.yeu-cau-luong.show', $id)

        ->with('success', 'Đã từ chối yêu cầu.');
}
public function edit($id)
{
    $yeuCau = YeuCauXemXetLuong::with([
        'nguoiDung.ho_so',
        'luongNhanVien'
    ])->findOrFail($id);

    $luong = $yeuCau->luongNhanVien;

    return view(
        'admin.yeu-cau-luong.edit',
        compact('yeuCau', 'luong')
    );
}
public function updateLuong(Request $request, $id)
{
    $request->validate([
        'so_ngay_cong' => 'nullable|numeric|min:0|max:22',
        'gio_tang_ca'   => 'nullable|numeric|min:0',
        'tong_phu_cap'  => 'nullable|numeric|min:0',
        'phan_hoi'      => 'nullable|string|max:500',
    ]);

    $yeuCau = YeuCauXemXetLuong::with('luongNhanVien')
        ->findOrFail($id);

    $luong = $yeuCau->luongNhanVien;
    $duLieuCu = $luong->toArray();
    $duLieuMoi = null;

   DB::transaction(function () use (
    $request,
    $yeuCau,
    $luong,
    $duLieuCu,
    &$duLieuMoi
) {

        // Tính lại theo dữ liệu admin sửa
        $ketQua = $this->tinhLuong->tinhLaiLuong(
            $luong,
            $request->only([
                'so_ngay_cong',
                'gio_tang_ca',
                'tong_phu_cap',
            ])
        );

        // Cập nhật phiếu lương
        $luong->update($ketQua);
        $luong->refresh();
        $duLieuMoi = $luong->toArray();

        // Cập nhật yêu cầu
        $yeuCau->update([
            'trang_thai'      => 'da_cap_nhat',
            'phan_hoi'        => $request->phan_hoi,
            'nguoi_duyet_id'  => auth()->id(),
            'thoi_gian_duyet' => now(),
        ]);

        // ==========================
        // Cập nhật lại chi tiết khấu trừ
        // ==========================

        KhauTruLuong::where('luong_nhan_vien_id', $luong->id)
            ->whereIn('loai_khau_tru', [
                'bhxh',
                'bhyt',
                'bhtn',
                'thue_tncn'
            ])
            ->delete();

        if ($luong->bhxh > 0) {
            KhauTruLuong::create([
                'luong_nhan_vien_id' => $luong->id,
                'loai_khau_tru'      => 'bhxh',
                'so_tien'            => $luong->bhxh,
                'ghi_chu'            => 'BHXH',
            ]);
        }

        if ($luong->bhyt > 0) {
            KhauTruLuong::create([
                'luong_nhan_vien_id' => $luong->id,
                'loai_khau_tru'      => 'bhyt',
                'so_tien'            => $luong->bhyt,
                'ghi_chu'            => 'BHYT',
            ]);
        }

        if ($luong->bhtn > 0) {
            KhauTruLuong::create([
                'luong_nhan_vien_id' => $luong->id,
                'loai_khau_tru'      => 'bhtn',
                'so_tien'            => $luong->bhtn,
                'ghi_chu'            => 'BHTN',
            ]);
        }

        if ($ketQua['thue_thu_nhap_ca_nhan'] > 0) {
            KhauTruLuong::create([
                'luong_nhan_vien_id' => $luong->id,
                'loai_khau_tru'      => 'thue_tncn',
                'so_tien'            => $ketQua['thue_thu_nhap_ca_nhan'],
                'ghi_chu'            => 'Thuế TNCN',
            ]);
        }
    });
LichSuXuLyYeuCauLuong::create([

    'yeu_cau_xem_xet_luong_id' => $yeuCau->id,

    'nguoi_thuc_hien_id' => auth()->id(),

    'hanh_dong' => 'cap_nhat',

    'du_lieu_cu' => $duLieuCu,

    'du_lieu_moi' => $duLieuMoi,

    'ghi_chu' => $request->phan_hoi 
        ?? 'Admin cập nhật lại phiếu lương',

    'thoi_gian' => now(),

]);
    return redirect()
        ->route('admin.yeu-cau-luong.show', $yeuCau->id)
        ->with('success', 'Đã cập nhật phiếu lương.');
}
}
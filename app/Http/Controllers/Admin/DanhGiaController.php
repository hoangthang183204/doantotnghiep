<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KetQuaDanhGia;
use App\Models\KyDanhGia;
use App\Models\MucTieuDanhGia;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class DanhGiaController extends Controller
{
    public function index()
    {
        $kys = KyDanhGia::withCount('ketQuas')->orderByDesc('den_ngay')->get();
        return view('admin.danh-gia.index', compact('kys'));
    }

    public function storeKy(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:150',
            'loai' => 'required|in:quy,nam',
            'tu_ngay' => 'required|date',
            'den_ngay' => 'required|date|after_or_equal:tu_ngay',
        ]);
        $data['trang_thai'] = 'dang_mo';
        $ky = KyDanhGia::create($data);
        return redirect()->route('admin.danh-gia.show', $ky->id);
    }

    public function show($id)
    {
        $ky = KyDanhGia::findOrFail($id);
        $nhanViens = NguoiDung::with('hoSo')->where('trang_thai', 1)->orderBy('id')->get();
        $ketQuas = KetQuaDanhGia::with(['nguoiDung.hoSo', 'mucTieus'])->where('ky_danh_gia_id', $ky->id)->get();
        return view('admin.danh-gia.show', compact('ky', 'nhanViens', 'ketQuas'));
    }

    public function storeKetQua(Request $request, $id)
    {
        $mucs = collect($request->input('muc_tieu', []))
            ->filter(fn ($muc) => filled($muc['ten'] ?? null))
            ->values()
            ->all();
        $request->merge(['muc_tieu' => $mucs]);
        $data = $request->validate([
            'nguoi_dung_id' => 'required|integer',
            'nhan_xet' => 'nullable|string',
            'muc_tieu' => 'required|array|min:1',
            'muc_tieu.*.ten' => 'required|string|max:200',
            'muc_tieu.*.trong_so' => 'required|integer|min:0|max:100',
            'muc_tieu.*.muc_tieu' => 'nullable|string|max:255',
            'muc_tieu.*.thuc_dat' => 'nullable|string|max:255',
            'muc_tieu.*.diem' => 'nullable|numeric|min:0|max:100',
        ]);

        $tongTrongSo = array_sum(array_column($data['muc_tieu'], 'trong_so'));
        $diem = 0;
        if ($tongTrongSo > 0) {
            foreach ($data['muc_tieu'] as $muc) {
                $diem += ((float) ($muc['diem'] ?? 0)) * ((int) $muc['trong_so']) / $tongTrongSo;
            }
        }
        $diem = round($diem, 2);

        $ketQua = KetQuaDanhGia::updateOrCreate(
            ['ky_danh_gia_id' => $id, 'nguoi_dung_id' => $data['nguoi_dung_id']],
            [
                'diem' => $diem,
                'xep_loai' => $this->xepLoai($diem),
                'nhan_xet' => $data['nhan_xet'] ?? null,
                'nguoi_danh_gia_id' => auth()->id(),
            ]
        );
        $ketQua->mucTieus()->delete();
        foreach ($data['muc_tieu'] as $muc) {
            MucTieuDanhGia::create([
                'ket_qua_danh_gia_id' => $ketQua->id,
                'ten' => $muc['ten'],
                'trong_so' => $muc['trong_so'],
                'muc_tieu' => $muc['muc_tieu'] ?? null,
                'thuc_dat' => $muc['thuc_dat'] ?? null,
                'diem' => $muc['diem'] ?? null,
            ]);
        }

        return back()->with('success', 'Đã lưu đánh giá. Điểm ' . $diem . ' sẽ được dùng khi tính thưởng cuối năm.');
    }

    private function xepLoai(float $diem): string
    {
        return match (true) {
            $diem >= 90 => 'xuat_sac',
            $diem >= 75 => 'tot',
            $diem >= 60 => 'dat',
            default => 'chua_dat',
        };
    }
}

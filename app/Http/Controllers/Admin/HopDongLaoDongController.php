<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use App\Models\ChucVu;
use Illuminate\Http\Request;

class HopDongLaoDongController extends Controller
{
    /**
     * Danh sách hợp đồng
     */
    public function index()
    {
        $hopDongs = HopDongLaoDong::with([
            'nguoi_dung',
            'chuc_vu',
            'nguoi_ky'
        ])->latest()->paginate(10);

        return view('admin.hop-dong-lao-dong.index', compact('hopDongs'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $nguoiDungs = NguoiDung::orderBy('id')->get();
        $chucVus = ChucVu::orderBy('id')->get();

        return view(
            'admin.hop-dong-lao-dong.create',
            compact('nguoiDungs', 'chucVus')
        );
    }

    /**
     * Lưu hợp đồng
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
        'chuc_vu_id' => 'required|exists:chuc_vu,id',

        'so_hop_dong' => 'required|string|max:255|unique:hop_dong_lao_dong,so_hop_dong',

        'loai_hop_dong' => 'required',

        'ngay_bat_dau' => 'required|date',
        'ngay_ket_thuc' => 'nullable|date',

        'luong_co_ban' => 'required|numeric',
        'phu_cap' => 'nullable|numeric',

        'hinh_thuc_lam_viec' => 'nullable|string|max:50',
        'dia_diem_lam_viec' => 'nullable|string|max:100',

        'ghi_chu' => 'nullable',
        'dieu_khoan' => 'nullable',

        'trang_thai_hop_dong' => 'nullable',
        'trang_thai_ky' => 'nullable',

        'file_hop_dong_da_ky' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
    ]);

    if ($request->hasFile('file_hop_dong_da_ky')) {

        $validated['file_hop_dong_da_ky'] =
            $request->file('file_hop_dong_da_ky')
                ->store('hop-dong', 'public');
    }

    $validated['created_by'] = auth()->id();

    HopDongLaoDong::create($validated);

    return redirect()
        ->route('admin.hop-dong.index')
        ->with('success', 'Tạo hợp đồng thành công');
}

    /**
     * Chi tiết hợp đồng
     */
    public function show($id)
    {
        $hopDong = HopDongLaoDong::with([
            'nguoi_dung',
            'chuc_vu',
            'nguoi_ky'
        ])->findOrFail($id);

        return view(
            'admin.hop-dong-lao-dong.show',
            compact('hopDong')
        );
    }

    /**
     * Form sửa
     */
    public function edit($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        $nguoiDungs = NguoiDung::orderBy('id')->get();
        $chucVus = ChucVu::orderBy('id')->get();

        return view(
            'admin.hop-dong-lao-dong.edit',
            compact(
                'hopDong',
                'nguoiDungs',
                'chucVus'
            )
        );
    }

    /**
     * Cập nhật hợp đồng
     */
    public function update(Request $request, $id)
{
    $hopDong = HopDongLaoDong::findOrFail($id);

    $validated = $request->validate([
        'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
        'chuc_vu_id' => 'required|exists:chuc_vu,id',

        'so_hop_dong' => 'required|string|max:255|unique:hop_dong_lao_dong,so_hop_dong,' . $hopDong->id,

        'loai_hop_dong' => 'required',

        'ngay_bat_dau' => 'required|date',
        'ngay_ket_thuc' => 'nullable|date',

        'luong_co_ban' => 'required|numeric',
        'phu_cap' => 'nullable|numeric',

        'hinh_thuc_lam_viec' => 'nullable|string|max:50',
        'dia_diem_lam_viec' => 'nullable|string|max:100',

        'ghi_chu' => 'nullable',
        'dieu_khoan' => 'nullable',

        'trang_thai_hop_dong' => 'nullable',
        'trang_thai_ky' => 'nullable',

        'file_hop_dong_da_ky' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    ]);

    if ($request->hasFile('file_hop_dong_da_ky')) {

        if (
            $hopDong->file_hop_dong_da_ky &&
            Storage::disk('public')->exists($hopDong->file_hop_dong_da_ky)
        ) {
            Storage::disk('public')->delete(
                $hopDong->file_hop_dong_da_ky
            );
        }

        $validated['file_hop_dong_da_ky'] =
            $request->file('file_hop_dong_da_ky')
                ->store('hop-dong', 'public');
    }

    $hopDong->update($validated);

    return redirect()
        ->route('admin.hop-dong.index')
        ->with('success', 'Cập nhật hợp đồng thành công');
}

    /**
     * Xóa hợp đồng
     */
    public function destroy($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        $hopDong->delete();

        return redirect()
            ->route('admin.hop-dong.index')
            ->with('success', 'Xóa hợp đồng thành công');
    }
}
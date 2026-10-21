<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NgayLe;
use App\Services\LichLamViecService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LichLamViecController extends Controller
{
    public function index()
    {
        $cauHinh = LichLamViecService::cauHinh();
        $ngayLam = LichLamViecService::ngayLamTrongTuan();
        $ngayLes = NgayLe::orderBy('ngay')->get();
        $diaDiems = DB::table('company_locations')->orderBy('id')->get();
        return view('admin.lich-lam-viec.index', compact('cauHinh', 'ngayLam', 'ngayLes', 'diaDiems'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'gio_vao' => 'required',
            'gio_ra' => 'required',
            'gio_nghi_trua_bat_dau' => 'required',
            'gio_nghi_trua_ket_thuc' => 'required',
            'so_gio_chuan' => 'required|numeric|min:1|max:16',
            'ngay_cong_chuan' => 'required|integer|min:1|max:31',
            'ngay_lam' => 'required|array|min:1',
            'ngay_lam.*' => 'integer|between:1,7',
        ]);

        $payload = [
            'gio_vao' => $data['gio_vao'],
            'gio_ra' => $data['gio_ra'],
            'gio_nghi_trua_bat_dau' => $data['gio_nghi_trua_bat_dau'],
            'gio_nghi_trua_ket_thuc' => $data['gio_nghi_trua_ket_thuc'],
            'so_gio_chuan' => $data['so_gio_chuan'],
            'ngay_cong_chuan' => $data['ngay_cong_chuan'],
            'ngay_lam' => json_encode(array_map('intval', $data['ngay_lam'])),
            'updated_at' => now(),
        ];

        $row = DB::table('cau_hinh_lich_lam')->first();
        if ($row) {
            DB::table('cau_hinh_lich_lam')->where('id', $row->id)->update($payload);
        } else {
            $payload['created_at'] = now();
            DB::table('cau_hinh_lich_lam')->insert($payload);
        }

        return back()->with('success', 'Đã cập nhật lịch làm việc.');
    }

    public function storeNgayLe(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:150',
            'ngay' => 'required|date',
            'loai' => 'required|in:quoc_gia,cong_ty',
            'co_huong_luong' => 'nullable|boolean',
            'lap_lai_hang_nam' => 'nullable|boolean',
        ]);
        $data['co_huong_luong'] = $request->boolean('co_huong_luong');
        $data['lap_lai_hang_nam'] = $request->boolean('lap_lai_hang_nam');
        NgayLe::create($data);
        return back()->with('success', 'Đã thêm ngày lễ.');
    }

    public function destroyNgayLe($id)
    {
        NgayLe::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa ngày lễ.');
    }

    public function storeDiaDiem(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'allowed_radius' => 'required|integer|min:20|max:5000',
        ]);
        DB::table('company_locations')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Đã thêm vị trí chấm công. Nhân viên phải đứng trong bán kính này khi quét mặt.');
    }

    public function destroyDiaDiem($id)
    {
        DB::table('company_locations')->where('id', $id)->delete();
        return back()->with('success', 'Đã xóa vị trí chấm công.');
    }
}

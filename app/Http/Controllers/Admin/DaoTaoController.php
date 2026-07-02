<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaoTaoNhanVien;
use App\Models\HoSo;
use Illuminate\Http\Request;

class DaoTaoController extends Controller
{
    /**
     * Danh sách đào tạo
     */
    public function index(Request $request)
    {
        $query = DaoTaoNhanVien::with('hoSo');

        if ($request->filled('keyword')) {

            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                $q->where('ten_khoa_hoc', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($sub) use ($keyword) {

                        $sub->where('ma_nhan_vien', 'like', "%{$keyword}%")
                            ->orWhere('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%");
                    });
            });
        }

        $daoTaos = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.dao-tao.index', compact('daoTaos'));
    }

    /**
     * Form đăng ký
     */
    public function create()
    {
        $hoSos = HoSo::orderBy('ma_nhan_vien')->get();

        return view('admin.dao-tao.create', compact('hoSos'));
    }

    /**
     * Lưu đăng ký
     */
    public function store(Request $request)
    {
        $request->validate([
            'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
            'ten_khoa_hoc' => 'required|max:255',
            'to_chuc' => 'nullable|max:255',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'chi_phi' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable',
        ], [
            'ho_so_id.required' => 'Vui lòng chọn nhân viên.',
            'ten_khoa_hoc.required' => 'Tên khóa học không được bỏ trống.',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
        ]);

        DaoTaoNhanVien::create([
            'ho_so_id' => $request->ho_so_id,
            'ten_khoa_hoc' => $request->ten_khoa_hoc,
            'to_chuc' => $request->to_chuc,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'chi_phi' => $request->chi_phi,
            'ghi_chu' => $request->ghi_chu,

            // mặc định
            'ket_qua' => null,
            'co_chung_chi' => 0,
        ]);

        return redirect()
            ->route('admin.dao-tao.index')
            ->with('success', 'Đăng ký khóa đào tạo thành công.');
    }
    public function show($id)
    {
    $daoTao = DaoTaoNhanVien::with('hoSo')->findOrFail($id);

    return view('admin.dao-tao.show', compact('daoTao'));
    }
    public function edit($id)
    {
    $daoTao = DaoTaoNhanVien::findOrFail($id);

    $hoSos = HoSo::orderBy('ma_nhan_vien')->get();

    return view('admin.dao-tao.edit', compact(
        'daoTao',
        'hoSos'
    ));
    }
    public function update(Request $request, $id)
    {
    $request->validate([
        'ho_so_id' => 'required|exists:ho_so_nguoi_dung,id',
        'ten_khoa_hoc' => 'required|max:255',
        'to_chuc' => 'nullable|max:255',
        'ngay_bat_dau' => 'required|date',
        'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
        'ket_qua' => 'nullable|max:255',
        'chi_phi' => 'nullable|numeric|min:0',
        'ghi_chu' => 'nullable',
    ]);

    $daoTao = DaoTaoNhanVien::findOrFail($id);

    $daoTao->update([
        'ho_so_id' => $request->ho_so_id,
        'ten_khoa_hoc' => $request->ten_khoa_hoc,
        'to_chuc' => $request->to_chuc,
        'ngay_bat_dau' => $request->ngay_bat_dau,
        'ngay_ket_thuc' => $request->ngay_ket_thuc,
        'ket_qua' => $request->ket_qua,
        'co_chung_chi' => $request->has('co_chung_chi'),
        'chi_phi' => $request->chi_phi,
        'ghi_chu' => $request->ghi_chu,
    ]);

    return redirect()
        ->route('admin.dao-tao.index')
        ->with('success', 'Cập nhật khóa đào tạo thành công.');
    }
    public function destroy($id)
    {
    DaoTaoNhanVien::findOrFail($id)->delete();

    return back()->with(
        'success',
        'Đã xóa khóa đào tạo.'
    );
    }
}
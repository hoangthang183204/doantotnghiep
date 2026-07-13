<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhauTruKhac;
use App\Models\NguoiDung;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Quản lý các khoản khấu trừ khác (tạm ứng, phạt, bồi thường...).
 * Các khoản này sẽ được cộng vào tổng khấu trừ khi tính lương tháng tương ứng.
 */
class KhauTruKhacController extends Controller
{
    /** Danh sách khấu trừ khác theo tháng/năm */
    public function index(Request $request)
    {
        $macDinh = Carbon::now();
        $thang   = (int) $request->input('thang', $macDinh->month);
        $nam     = (int) $request->input('nam', $macDinh->year);

        $query = KhauTruKhac::with('nguoiDung.ho_so')
            ->where('thang', $thang)
            ->where('nam', $nam);

        if ($request->filled('loai')) {
            $query->where('loai', $request->input('loai'));
        }

        $khoanKhauTrus = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $tongTien = KhauTruKhac::hieuLuc()->thang($thang, $nam)->sum('so_tien');

        return view('admin.khau-tru-khac.index', compact(
            'khoanKhauTrus', 'thang', 'nam', 'tongTien'
        ));
    }

    /** Form thêm khoản khấu trừ */
    public function create(Request $request)
    {
        $macDinh   = Carbon::now();
        $thang     = (int) $request->input('thang', $macDinh->month);
        $nam       = (int) $request->input('nam', $macDinh->year);
        $nhanViens = NguoiDung::with('ho_so')->where('trang_thai', 1)->get();

        return view('admin.khau-tru-khac.create', compact('nhanViens', 'thang', 'nam'));
    }

    /** Lưu khoản khấu trừ mới */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['nguoi_tao_id'] = auth()->id();

        KhauTruKhac::create($data);

        return redirect()
            ->route('admin.khau-tru-khac.index', ['thang' => $data['thang'], 'nam' => $data['nam']])
            ->with('success', 'Đã thêm khoản khấu trừ. Khoản này sẽ được áp dụng khi tính lương tháng ' . $data['thang'] . '/' . $data['nam'] . '.');
    }

    /** Form sửa */
    public function edit($id)
    {
        $khauTru   = KhauTruKhac::findOrFail($id);
        $nhanViens = NguoiDung::with('ho_so')->where('trang_thai', 1)->get();

        return view('admin.khau-tru-khac.edit', compact('khauTru', 'nhanViens'));
    }

    /** Cập nhật */
    public function update(Request $request, $id)
    {
        $khauTru = KhauTruKhac::findOrFail($id);
        $data    = $this->validateData($request);

        $khauTru->update($data);

        return redirect()
            ->route('admin.khau-tru-khac.index', ['thang' => $data['thang'], 'nam' => $data['nam']])
            ->with('success', 'Đã cập nhật khoản khấu trừ.');
    }

    /** Xoá */
    public function destroy($id)
    {
        $khauTru = KhauTruKhac::findOrFail($id);
        $thang   = $khauTru->thang;
        $nam     = $khauTru->nam;
        $khauTru->delete();

        return redirect()
            ->route('admin.khau-tru-khac.index', ['thang' => $thang, 'nam' => $nam])
            ->with('success', 'Đã xoá khoản khấu trừ.');
    }
    
    public function show($id)
    {
        $khauTru = KhauTruKhac::with('nguoiDung.ho_so')->findOrFail($id);

        return view('admin.khau-tru-khac.show', compact('khauTru'));
    }

    public function approve($id)
    {
        $khauTru = KhauTruKhac::findOrFail($id);

        $khauTru->update([
            'trang_thai' => 'hieu_luc'
        ]);

        return back()->with('success','Đã duyệt ứng lương.');
    }

    public function reject($id)
    {
        $khauTru = KhauTruKhac::findOrFail($id);

        $khauTru->update([
            'trang_thai' => 'tu_choi'
        ]);

        return back()->with('success','Đã từ chối.');
    }

    public function undo($id)
    {
        $khauTru = KhauTruKhac::findOrFail($id);

        $khauTru->update([
            'trang_thai' => 'huy'
        ]);

        return back()->with('success','Đã hoàn tác.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nguoi_dung_id' => 'required|integer|exists:nguoi_dung,id',
            'thang'         => 'required|integer|between:1,12',
            'nam'           => 'required|integer|min:2000|max:2100',
            'loai'          => 'required|in:tam_ung,phat,boi_thuong,khac',
            'so_tien'       => 'required|numeric|min:0',
            'ly_do'         => 'nullable|string|max:255',
            'trang_thai'    => 'required|in:hieu_luc,huy',
        ], [], [
            'nguoi_dung_id' => 'nhân viên',
            'so_tien'       => 'số tiền',
            'ly_do'         => 'lý do',
        ]);
    }
}

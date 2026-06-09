<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
// use App\Models\VaiTro;

class ChamCongController extends Controller
{
    public function index(Request $request)
    {
        $query = ChamCong::with([
            'nguoi_dung.hoSo'
        ]);

        // Tìm theo tên hoặc tên đăng nhập
        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->whereHas('nguoi_dung', function ($q) use ($keyword) {

                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {

                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw(
                                "CONCAT(ho, ' ', ten) LIKE ?",
                                ["%{$keyword}%"]
                            );
                    });
            });
        }

        // Lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo ngày cụ thể
        if ($request->filled('ngay_cham_cong')) {
            $query->whereDate(
                'ngay_cham_cong',
                $request->ngay_cham_cong
            );
        }

        // Từ ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate(
                'ngay_cham_cong',
                '>=',
                $request->tu_ngay
            );
        }

        // Đến ngày
        if ($request->filled('den_ngay')) {
            $query->whereDate(
                'ngay_cham_cong',
                '<=',
                $request->den_ngay
            );
        }

        $chamCongs = $query
            ->orderBy('id', 'asc') // ID tăng dần
            ->paginate(10)
            ->appends($request->query());

        return view(
            'admin.cham-cong.index',
            compact('chamCongs')
        );
    }

    public function export(Request $request)
    {
        $query = ChamCong::with([
            'nguoi_dung.hoSo'
        ]);

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->whereHas('nguoi_dung', function ($q) use ($keyword) {

                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {

                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw(
                                "CONCAT(ho,' ',ten) LIKE ?",
                                ["%{$keyword}%"]
                            );
                    });
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('ngay_cham_cong')) {
            $query->whereDate('ngay_cham_cong', $request->ngay_cham_cong);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_cham_cong', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_cham_cong', '<=', $request->den_ngay);
        }

        $data = $query->get();

        $fileName = 'cham_cong_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($data) {

            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nhân viên',
                'Ngày chấm công',
                'Giờ vào',
                'Giờ ra',
                'Số giờ làm',
                'Trạng thái'
            ]);

            foreach ($data as $item) {

                $hoTen = $item->nguoi_dung->hoSo
                    ? $item->nguoi_dung->hoSo->ho . ' ' . $item->nguoi_dung->hoSo->ten
                    : $item->nguoi_dung->ten_dang_nhap;

                fputcsv($file, [
                    $item->id,
                    $hoTen,
                    $item->ngay_cham_cong,
                    $item->gio_vao,
                    $item->gio_ra,
                    $item->so_gio_lam,
                    $item->trang_thai,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $nguoiDungs = NguoiDung::orderBy('ten_dang_nhap')->get();

        $nguoiPheDuyets = NguoiDung::whereHas('vai_tro', function ($q) {
            $q->whereIn('name', [
                'admin',
                'hr',
                'truong_phong'
            ]);
        })->get();

        return view(
            'admin.cham-cong.create',
            compact(
                'nguoiDungs',
                'nguoiPheDuyets'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nguoi_dung_id' => 'required',
            'ngay_cham_cong' => 'required|date',
            'gio_vao' => 'nullable',
            'gio_ra' => 'nullable',
            'trang_thai' => 'required',
        ]);

        ChamCong::create($request->all());

        return redirect()
            ->route('admin.cham-cong.index')
            ->with('success', 'Thêm chấm công thành công');
    }

    public function show($id)
    {
        $chamCong = ChamCong::with([
            'nguoi_dung',
            'nguoi_phe_duyet'
        ])->findOrFail($id);

        return view('admin.cham-cong.show', compact('chamCong'));
    }

    public function edit($id)
    {
        $chamCong = ChamCong::findOrFail($id);

        $nguoiDungs = NguoiDung::orderBy('ten_dang_nhap')->get();

        $nguoiPheDuyets = NguoiDung::whereHas('vai_tro', function ($q) {
            $q->whereIn('name', [
                'admin',
                'hr',
                'truong_phong'
            ]);
        })->get();

        return view(
            'admin.cham-cong.edit',
            compact(
                'chamCong',
                'nguoiDungs',
                'nguoiPheDuyets'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $chamCong = ChamCong::findOrFail($id);

        $request->validate([
            'nguoi_dung_id' => 'required',
            'ngay_cham_cong' => 'required|date',
            'trang_thai' => 'required',
        ]);

        $chamCong->update($request->all());

        return redirect()
            ->route('admin.cham-cong.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        ChamCong::findOrFail($id)->delete();

        return back()
            ->with('success', 'Xóa thành công');
    }
}

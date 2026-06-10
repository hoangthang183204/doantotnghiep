<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class ChamCongController extends Controller
{
    // Giờ chuẩn vào / ra — chỉnh ở đây nếu công ty thay đổi
    private const GIO_CHUAN_VAO       = '08:30';
    private const GIO_CHUAN_RA        = '17:30';
    private const GIO_TIEU_CHUAN_NGAY = 8.0; // số giờ = 1 công

    // =========================================================================
    // INDEX
    // =========================================================================

    public function index(Request $request)
    {
        $query = ChamCong::with(['nguoi_dung.hoSo']);

        $this->applyFilters($query, $request);

        $chamCongs = $query
            ->orderBy('ngay_cham_cong', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.cham-cong.index', compact('chamCongs'));
    }

    // =========================================================================
    // EXPORT CSV
    // =========================================================================

    public function export(Request $request)
    {
        $query = ChamCong::with(['nguoi_dung.hoSo']);

        $this->applyFilters($query, $request);

        $fileName = 'cham_cong_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($query) {

            $file = fopen('php://output', 'w');

            // BOM UTF-8 để Excel hiển thị đúng tiếng Việt
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nhân viên',
                'Ngày chấm công',
                'Giờ vào',
                'Giờ ra',
                'Số giờ làm',
                'Số công',
                'Giờ tăng ca',
                'Phút đi muộn',
                'Phút về sớm',
                'Trạng thái',
                'Phương thức',
                'Trạng thái duyệt',
            ]);

            // chunk() tránh out-of-memory khi dữ liệu lớn
            $query->chunk(500, function ($records) use ($file) {
                foreach ($records as $item) {
                    $hoTen = optional($item->nguoi_dung->hoSo)
                        ? $item->nguoi_dung->hoSo->ho . ' ' . $item->nguoi_dung->hoSo->ten
                        : $item->nguoi_dung->ten_dang_nhap;

                    fputcsv($file, [
                        $item->id,
                        $hoTen,
                        $item->ngay_cham_cong?->format('d/m/Y'),
                        $item->gio_vao,
                        $item->gio_ra,
                        $item->so_gio_lam,
                        $item->so_cong,
                        $item->gio_tang_ca,
                        $item->phut_di_muon,
                        $item->phut_ve_som,
                        $item->trang_thai,
                        $item->phuong_thuc_cham_cong,
                        $item->trang_thai_duyet ? 'Đã duyệt' : 'Chờ duyệt',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // =========================================================================
    // CREATE
    // =========================================================================

    public function create()
    {
        $nguoiDungs = NguoiDung::orderBy('ten_dang_nhap')->get();

        $nguoiPheDuyets = NguoiDung::whereHas('vai_tro', function ($q) {
            $q->whereIn('name', ['admin', 'hr', 'truong_phong']);
        })->get();

        return view('admin.cham-cong.create', compact('nguoiDungs', 'nguoiPheDuyets'));
    }

    // =========================================================================
    // STORE
    // =========================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nguoi_dung_id'         => 'required|exists:nguoi_dung,id',
            'ngay_cham_cong'        => 'required|date',
            'gio_vao'               => 'nullable|date_format:H:i',
            'gio_ra'                => 'nullable|date_format:H:i|after:gio_vao',
            'ghi_chu'               => 'nullable|string|max:500',
            'phuong_thuc_cham_cong' => 'nullable|in:ip,wifi,mac,manual',
        ]);

        // Kiểm tra trùng chấm công cùng ngày
        $exists = ChamCong::where('nguoi_dung_id', $validated['nguoi_dung_id'])
            ->whereDate('ngay_cham_cong', $validated['ngay_cham_cong'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'ngay_cham_cong' => 'Nhân viên này đã có bản ghi chấm công trong ngày ' . $validated['ngay_cham_cong'] . '.',
            ]);
        }

        // Tự động tính toán
        $gioVao     = $validated['gio_vao'] ?? null;
        $gioRa      = $validated['gio_ra'] ?? null;
        $soGioLam   = ChamCong::tinhSoGioLam($gioVao, $gioRa);
        $phutDiMuon = ChamCong::tinhPhutDiMuon($gioVao, self::GIO_CHUAN_VAO);
        $phutVeSom  = ChamCong::tinhPhutVeSom($gioRa, self::GIO_CHUAN_RA);
        $gioTangCa  = max(0, round($soGioLam - self::GIO_TIEU_CHUAN_NGAY, 2));
        $soCong     = $soGioLam > 0
            ? round(min($soGioLam, self::GIO_TIEU_CHUAN_NGAY) / self::GIO_TIEU_CHUAN_NGAY, 2)
            : 0.0;
        $trangThai  = ChamCong::xacDinhTrangThai($gioVao, $gioRa, $phutDiMuon, $phutVeSom);

        ChamCong::create([
            'nguoi_dung_id'         => $validated['nguoi_dung_id'],
            'ngay_cham_cong'        => $validated['ngay_cham_cong'],
            'gio_vao'               => $gioVao,
            'gio_ra'                => $gioRa,
            'so_gio_lam'            => $soGioLam,
            'so_cong'               => $soCong,
            'gio_tang_ca'           => $gioTangCa,
            'phut_di_muon'          => $phutDiMuon,
            'phut_ve_som'           => $phutVeSom,
            'trang_thai'            => $trangThai,
            'ghi_chu'               => $validated['ghi_chu'] ?? null,
            'phuong_thuc_cham_cong' => $validated['phuong_thuc_cham_cong'] ?? 'manual',
            'trang_thai_duyet'      => 0,
        ]);

        return redirect()
            ->route('admin.cham-cong.index')
            ->with('success', 'Thêm chấm công thành công.');
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show($id)
    {
        $chamCong = ChamCong::with(['nguoi_dung.hoSo', 'nguoi_phe_duyet'])
            ->findOrFail($id);

        return view('admin.cham-cong.show', compact('chamCong'));
    }

    // =========================================================================
    // EDIT
    // =========================================================================

    public function edit($id)
    {
        $chamCong = ChamCong::findOrFail($id);

        $nguoiDungs = NguoiDung::orderBy('ten_dang_nhap')->get();

        $nguoiPheDuyets = NguoiDung::whereHas('vai_tro', function ($q) {
            $q->whereIn('name', ['admin', 'hr', 'truong_phong']);
        })->get();

        return view('admin.cham-cong.edit', compact('chamCong', 'nguoiDungs', 'nguoiPheDuyets'));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(Request $request, $id)
    {
        $chamCong = ChamCong::findOrFail($id);

        $validated = $request->validate([
            'nguoi_dung_id'         => 'required|exists:nguoi_dung,id',
            'ngay_cham_cong'        => 'required|date',
            'gio_vao'               => 'nullable|date_format:H:i',
            'gio_ra'                => 'nullable|date_format:H:i|after:gio_vao',
            'ghi_chu'               => 'nullable|string|max:500',
            'phuong_thuc_cham_cong' => 'nullable|in:ip,wifi,mac,manual',
            'nguoi_phe_duyet_id'    => 'nullable|exists:nguoi_dung,id',
            'trang_thai_duyet'      => 'nullable|boolean',
            'ghi_chu_duyet'         => 'nullable|string|max:500',
        ]);

        // Kiểm tra trùng ngày (bỏ qua bản ghi hiện tại)
        $exists = ChamCong::where('nguoi_dung_id', $validated['nguoi_dung_id'])
            ->whereDate('ngay_cham_cong', $validated['ngay_cham_cong'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'ngay_cham_cong' => 'Nhân viên này đã có bản ghi chấm công trong ngày ' . $validated['ngay_cham_cong'] . '.',
            ]);
        }

        // Tái tính toán khi gio_vao / gio_ra thay đổi
        $gioVao     = $validated['gio_vao'] ?? null;
        $gioRa      = $validated['gio_ra'] ?? null;
        $soGioLam   = ChamCong::tinhSoGioLam($gioVao, $gioRa);
        $phutDiMuon = ChamCong::tinhPhutDiMuon($gioVao, self::GIO_CHUAN_VAO);
        $phutVeSom  = ChamCong::tinhPhutVeSom($gioRa, self::GIO_CHUAN_RA);
        $gioTangCa  = max(0, round($soGioLam - self::GIO_TIEU_CHUAN_NGAY, 2));
        $soCong     = $soGioLam > 0
            ? round(min($soGioLam, self::GIO_TIEU_CHUAN_NGAY) / self::GIO_TIEU_CHUAN_NGAY, 2)
            : 0.0;
        $trangThai  = ChamCong::xacDinhTrangThai($gioVao, $gioRa, $phutDiMuon, $phutVeSom);

        // Ghi thời gian phê duyệt nếu vừa được duyệt
        $thoiGianPheDuyet = $chamCong->thoi_gian_phe_duyet;
        if (!empty($validated['trang_thai_duyet']) && !$chamCong->trang_thai_duyet) {
            $thoiGianPheDuyet = now();
        }

        $chamCong->update([
            'nguoi_dung_id'         => $validated['nguoi_dung_id'],
            'ngay_cham_cong'        => $validated['ngay_cham_cong'],
            'gio_vao'               => $gioVao,
            'gio_ra'                => $gioRa,
            'so_gio_lam'            => $soGioLam,
            'so_cong'               => $soCong,
            'gio_tang_ca'           => $gioTangCa,
            'phut_di_muon'          => $phutDiMuon,
            'phut_ve_som'           => $phutVeSom,
            'trang_thai'            => $trangThai,
            'ghi_chu'               => $validated['ghi_chu'] ?? null,
            'phuong_thuc_cham_cong' => $validated['phuong_thuc_cham_cong'] ?? $chamCong->phuong_thuc_cham_cong,
            'nguoi_phe_duyet_id'    => $validated['nguoi_phe_duyet_id'] ?? null,
            'trang_thai_duyet'      => $validated['trang_thai_duyet'] ?? 0,
            'ghi_chu_duyet'         => $validated['ghi_chu_duyet'] ?? null,
            'thoi_gian_phe_duyet'   => $thoiGianPheDuyet,
        ]);

        return redirect()
            ->route('admin.cham-cong.index')
            ->with('success', 'Cập nhật chấm công thành công.');
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    public function destroy($id)
    {
        ChamCong::findOrFail($id)->delete();

        return back()->with('success', 'Xóa chấm công thành công.');
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Áp dụng bộ lọc chung cho index() và export() — tránh lặp code.
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->whereHas('nguoi_dung', function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
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
    }
}

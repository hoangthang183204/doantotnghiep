<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TangCaController extends Controller
{
    // =========================================================================
    // INDEX — Danh sách đăng ký tăng ca
    // =========================================================================

    public function index(Request $request)
    {
        $query = DangKyTangCa::with(['nguoi_dung.hoSo', 'nguoi_duyet.hoSo', 'thuc_hien']);

        // Tìm theo tên nhân viên
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

        // Lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc loại tăng ca
        if ($request->filled('loai_tang_ca')) {
            $query->where('loai_tang_ca', $request->loai_tang_ca);
        }

        // Lọc từ ngày / đến ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
        }

        // Thống kê nhanh (không bị ảnh hưởng bởi filter)
        $tongHoSo    = DangKyTangCa::count();
        $choDuyet    = DangKyTangCa::where('trang_thai', 'cho_duyet')->count();
        $daDuyet     = DangKyTangCa::where('trang_thai', 'da_duyet')->count();
        $tuChoi      = DangKyTangCa::where('trang_thai', 'tu_choi')->count();

        $dangKyList = $query
            ->orderBy('ngay_tang_ca', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.tang-ca.index', compact(
            'dangKyList',
            'tongHoSo',
            'choDuyet',
            'daDuyet',
            'tuChoi'
        ));
    }

    // =========================================================================
    // SHOW — Chi tiết đơn tăng ca
    // =========================================================================

    public function show($id)
    {
        $dangKy = DangKyTangCa::with([
            'nguoi_dung.hoSo',
            'nguoi_duyet.hoSo',
            'thuc_hien',
        ])->findOrFail($id);

        return view('admin.tang-ca.show', compact('dangKy'));
    }

    // =========================================================================
    // DUYET — Phê duyệt đơn tăng ca
    // =========================================================================

    public function duyet(Request $request, $id)
    {
        $dangKy = DangKyTangCa::findOrFail($id);

        if ($dangKy->trang_thai !== 'cho_duyet') {
            return back()->withErrors(['Đơn này không ở trạng thái chờ duyệt.']);
        }

        $dangKy->update([
            'trang_thai'     => 'da_duyet',
            'nguoi_duyet_id' => Auth::id(),
            'thoi_gian_duyet'=> now(),
            'ly_do_tu_choi'  => null,
        ]);

        return back()->with('success', 'Đã phê duyệt đơn tăng ca thành công.');
    }

    // =========================================================================
    // TU CHOI — Từ chối đơn tăng ca
    // =========================================================================

    public function tuChoi(Request $request, $id)
    {
        $dangKy = DangKyTangCa::findOrFail($id);

        if ($dangKy->trang_thai !== 'cho_duyet') {
            return back()->withErrors(['Đơn này không ở trạng thái chờ duyệt.']);
        }

        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ], [
            'ly_do_tu_choi.required' => 'Vui lòng nhập lý do từ chối.',
        ]);

        $dangKy->update([
            'trang_thai'     => 'tu_choi',
            'nguoi_duyet_id' => Auth::id(),
            'thoi_gian_duyet'=> now(),
            'ly_do_tu_choi'  => $request->ly_do_tu_choi,
        ]);

        return back()->with('success', 'Đã từ chối đơn tăng ca.');
    }

    // =========================================================================
    // DUYET HANG LOAT — Phê duyệt nhiều đơn cùng lúc
    // =========================================================================

    public function duyetHangLoat(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:dang_ky_tang_ca,id',
        ]);

        $soLuong = DangKyTangCa::whereIn('id', $request->ids)
            ->where('trang_thai', 'cho_duyet')
            ->update([
                'trang_thai'      => 'da_duyet',
                'nguoi_duyet_id'  => Auth::id(),
                'thoi_gian_duyet' => now(),
            ]);

        return back()->with('success', "Đã phê duyệt {$soLuong} đơn tăng ca.");
    }
}

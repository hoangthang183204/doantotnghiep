<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThucHienTangCa;
use Illuminate\Http\Request;

class ThucHienTangCaController extends Controller
{
    // =========================================================================
    // DANH SÁCH THỰC HIỆN TĂNG CA
    // =========================================================================

    public function index(Request $request)
    {
        $query = ThucHienTangCa::with([
            'dang_ky.nguoi_dung.hoSo',
            'dang_ky.nguoi_duyet.hoSo',
        ]);

        // Tìm kiếm nhân viên
        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->whereHas('dang_ky.nguoi_dung', function ($q) use ($keyword) {

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

        // Lọc ngày tăng ca
        if ($request->filled('tu_ngay')) {
            $query->whereHas('dang_ky', function ($q) use ($request) {
                $q->whereDate('ngay_tang_ca', '>=', $request->tu_ngay);
            });
        }

        if ($request->filled('den_ngay')) {
            $query->whereHas('dang_ky', function ($q) use ($request) {
                $q->whereDate('ngay_tang_ca', '<=', $request->den_ngay);
            });
        }

        $danhSach = $query
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());

        // Thống kê
        $tongSo = ThucHienTangCa::count();

        $chuaLam = ThucHienTangCa::where(
            'trang_thai',
            'chua_lam'
        )->count();

        $dangLam = ThucHienTangCa::where(
            'trang_thai',
            'dang_lam'
        )->count();

        $hoanThanh = ThucHienTangCa::where(
            'trang_thai',
            'hoan_thanh'
        )->count();

        $khongHoanThanh = ThucHienTangCa::where(
            'trang_thai',
            'khong_hoan_thanh'
        )->count();

        return view(
            'admin.thuc-hien-tang-ca.index',
            compact(
                'danhSach',
                'tongSo',
                'chuaLam',
                'dangLam',
                'hoanThanh',
                'khongHoanThanh'
            )
        );
    }

    // =========================================================================
    // CHI TIẾT
    // =========================================================================

    public function show($id)
    {
        $thucHien = ThucHienTangCa::with([
            'dang_ky.nguoi_dung.hoSo',
            'dang_ky.nguoi_duyet.hoSo',
        ])->findOrFail($id);

        return view(
            'admin.thuc-hien-tang-ca.show',
            compact('thucHien')
        );
    }

    // =========================================================================
    // FORM CẬP NHẬT
    // =========================================================================

    public function edit($id)
    {
        $thucHien = ThucHienTangCa::with([
            'dang_ky.nguoi_dung.hoSo',
            'dang_ky.nguoi_duyet.hoSo',
        ])->findOrFail($id);

        return view(
            'admin.thuc-hien-tang-ca.edit',
            compact('thucHien')
        );
    }

    // =========================================================================
    // CẬP NHẬT KẾT QUẢ THỰC HIỆN
    // =========================================================================

    public function update(Request $request, $id)
    {
        $thucHien = ThucHienTangCa::with('dang_ky')->findOrFail($id);

        // =========================
        // VALIDATION (KHÔNG ÉP FORMAT NỮA)
        // =========================
        $request->validate([
            'gio_bat_dau_thuc_te'  => 'nullable',
            'gio_ket_thuc_thuc_te' => 'nullable',
            'cong_viec_da_thuc_hien' => 'nullable|string',
            'trang_thai' => 'required|in:chua_lam,dang_lam,hoan_thanh,khong_hoan_thanh',
            'ghi_chu' => 'nullable|string|max:1000',
        ]);

        // =========================
        // NORMALIZE TIME (CHỐNG LỖI FORMAT)
        // =========================
        $batDau = $request->gio_bat_dau_thuc_te
            ? \Carbon\Carbon::parse($request->gio_bat_dau_thuc_te)->format('H:i')
            : null;

        $ketThuc = $request->gio_ket_thuc_thuc_te
            ? \Carbon\Carbon::parse($request->gio_ket_thuc_thuc_te)->format('H:i')
            : null;

        // =========================
        // TÍNH SỐ GIỜ
        // =========================
        $soGio = 0;

        if ($batDau && $ketThuc) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $batDau);
            $end   = \Carbon\Carbon::createFromFormat('H:i', $ketThuc);

            if ($end->gt($start)) {
                $soGio = round($start->floatDiffInHours($end), 2);
            }
        }

        // =========================
        // TÍNH CÔNG
        // =========================
        $loaiTangCa = $thucHien->dang_ky->loai_tang_ca;

        $soCong = match ($loaiTangCa) {
            'ngay_nghi' => round(($soGio * 1.5) / 8, 2),
            'le_tet'    => round(($soGio * 2) / 8, 2),
            default     => round($soGio / 8, 2),
        };

        // =========================
        // UPDATE DB
        // =========================
        $thucHien->update([
            'gio_bat_dau_thuc_te'  => $batDau,
            'gio_ket_thuc_thuc_te' => $ketThuc,
            'so_gio_tang_ca_thuc_te' => $soGio,
            'so_cong_tang_ca' => $soCong,
            'cong_viec_da_thuc_hien' => $request->cong_viec_da_thuc_hien,
            'trang_thai' => $request->trang_thai,
            'ghi_chu' => $request->ghi_chu,
        ]);

        return redirect()
            ->route('admin.thuc-hien-tang-ca.index')
            ->with('success', 'Cập nhật kết quả tăng ca thành công.');
    }
}

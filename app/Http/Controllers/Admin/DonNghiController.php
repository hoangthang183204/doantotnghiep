<?php
// app/Http/Controllers/Admin/DonNghiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonXinNghi;
use App\Models\NguoiDung;
use App\Models\LoaiNghiPhep;
use Illuminate\Support\Facades\DB;

class DonNghiController extends Controller
{
    /**
     * Danh sách đơn xin nghỉ (có filter)
     */
    public function index(Request $request)
    {
        $query = DonXinNghi::with([
            'nguoiDung.hoSo', 
            'banGiaoCho.hoSo', 
            'loaiNghiPhep', 
            'nguoiDuyet.hoSo'
        ]);

        // 🔍 Filter theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('ma_don_nghi', 'like', "%{$keyword}%")
                    ->orWhereHas('nguoiDung', function ($sub) use ($keyword) {
                        $sub->where('ten_dang_nhap', 'like', "%{$keyword}%")
                            ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                                $hs->where('ho', 'like', "%{$keyword}%")
                                    ->orWhere('ten', 'like', "%{$keyword}%")
                                    ->orWhere('ma_nhan_vien', 'like', "%{$keyword}%")
                                    ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                            });
                    });
            });
        }

        // 🔍 Filter theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // 🔍 Filter theo loại nghỉ phép
        if ($request->filled('loai_nghi_phep_id')) {
            $query->where('loai_nghi_phep_id', $request->loai_nghi_phep_id);
        }

        // 🔍 Filter theo khoảng ngày tạo
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        // 🔍 Filter theo khoảng ngày nghỉ
        if ($request->filled('tu_ngay_nghi')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->tu_ngay_nghi);
        }
        if ($request->filled('den_ngay_nghi')) {
            $query->whereDate('ngay_ket_thuc', '<=', $request->den_ngay_nghi);
        }

        $danhSachDon = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        // Thống kê
        $countChoDuyet = DonXinNghi::where('trang_thai', 'cho_duyet')->count();
        $countDaDuyet = DonXinNghi::where('trang_thai', 'da_duyet')->count();
        $countTuChoi = DonXinNghi::where('trang_thai', 'tu_choi')->count();
        $countHuyBo = DonXinNghi::where('trang_thai', 'huy_bo')->count(); // ← THÊM THỐNG KÊ HỦY BỎ
        $countHomNay = DonXinNghi::whereDate('created_at', now()->toDateString())->count();

        $loaiNghiPheps = LoaiNghiPhep::where('trang_thai', 1)->get();

        return view('admin.don_nghi.index', compact(
            'danhSachDon',
            'countChoDuyet',
            'countDaDuyet',
            'countTuChoi',
            'countHuyBo',
            'countHomNay',
            'loaiNghiPheps'
        ));
    }

    /**
     * Chi tiết đơn xin nghỉ
     */
    public function show($id)
    {
        $donNghi = DonXinNghi::with([
            'nguoiDung.hoSo',
            'nguoiDung.phongBan',
            'nguoiDung.chucVu',
            'banGiaoCho.hoSo',
            'loaiNghiPhep',
            'nguoiDuyet.hoSo'
        ])->findOrFail($id);

        return view('admin.don_nghi.show', compact('donNghi'));
    }

    /**
     * Cập nhật trạng thái đơn xin nghỉ
     */
    public function capNhatTrangThai(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:da_duyet,tu_choi,cho_duyet',
            'ly_do_tu_choi' => 'nullable|string|max:500',
        ]);

        try {
            $donNghi = DonXinNghi::findOrFail($id);

            // ⚠️ KIỂM TRA: KHÔNG CHO PHÉP THAY ĐỔI ĐƠN ĐÃ HỦY
            if ($donNghi->trang_thai == 'huy_bo') {
                return redirect()->back()->with('error', '❌ Đơn này đã bị hủy, không thể thay đổi trạng thái!');
            }

            // Cập nhật trạng thái
            $donNghi->trang_thai = $request->trang_thai;

            if ($request->trang_thai == 'da_duyet' || $request->trang_thai == 'tu_choi') {
                $donNghi->nguoi_duyet_id = auth()->id();
                $donNghi->thoi_gian_duyet = now();
            }

            if ($request->trang_thai == 'tu_choi') {
                $donNghi->ghi_chu = $request->ly_do_tu_choi ?? 'Không có lý do';
            }

            if ($request->trang_thai == 'cho_duyet') {
                $donNghi->nguoi_duyet_id = null;
                $donNghi->thoi_gian_duyet = null;
                $donNghi->ghi_chu = null;
            }

            $donNghi->save();

            $thongBao = match ($request->trang_thai) {
                'cho_duyet' => 'Đã hoàn tác đơn về trạng thái Chờ duyệt!',
                'da_duyet' => 'Đã duyệt đơn nghỉ phép thành công!',
                'tu_choi' => 'Đã từ chối đơn nghỉ phép!',
                default => 'Cập nhật thành công!',
            };

            return redirect()->back()->with('success', $thongBao);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Duyệt hàng loạt
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:don_xin_nghi,id',
            'action' => 'required|in:da_duyet,tu_choi'
        ]);

        try {
            // ⚠️ CHỈ CẬP NHẬT CÁC ĐƠN CÓ TRẠNG THÁI 'cho_duyet' VÀ KHÔNG PHẢI 'huy_bo'
            $count = DonXinNghi::whereIn('id', $request->ids)
                ->where('trang_thai', 'cho_duyet')
                ->where('trang_thai', '!=', 'huy_bo')
                ->update([
                    'trang_thai' => $request->action,
                    'nguoi_duyet_id' => auth()->id(),
                    'thoi_gian_duyet' => now(),
                    'ghi_chu' => $request->ly_do_tu_choi ?? ($request->action == 'da_duyet' ? 'Duyệt hàng loạt' : 'Từ chối hàng loạt'),
                ]);

            $message = $request->action == 'da_duyet' ? 'duyệt' : 'từ chối';
            return response()->json([
                'success' => true,
                'message' => "Đã {$message} {$count} đơn thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
<?php
// app/Http/Controllers/Admin/DonNghiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonXinNghi;
use App\Models\NguoiDung;
use App\Models\LoaiNghiPhep;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class DonNghiController extends Controller
{

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
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
     * Duyệt hàng loạt (Đã cập nhật logic loại trừ trừ phép năm)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:don_xin_nghi,id',
            'action' => 'required|in:da_duyet,tu_choi'
        ]);

        try {
            $donNghiList = DonXinNghi::with('loaiNghiPhep')
                ->whereIn('id', $request->ids)
                ->where('trang_thai', 'cho_duyet')
                ->where('trang_thai', '!=', 'huy_bo')
                ->get();

            $count = 0;
            foreach ($donNghiList as $donNghi) {
                $donNghi->update([
                    'trang_thai' => $request->action,
                    'nguoi_duyet_id' => auth()->id(),
                    'thoi_gian_duyet' => now(),
                    'ghi_chu' => $request->ly_do_tu_choi ?? ($request->action == 'da_duyet' ? 'Duyệt hàng loạt' : 'Từ chối hàng loạt'),
                ]);

                // ⭐ LOGIC KHẤU TRỪ SỐ DƯ PHÉP KHI DUYỆT HÀNG LOẠT (LOẠI TRỪ THAI SẢN / KHÔNG LƯƠNG)
                if ($request->action == 'da_duyet') {
                    $loaiNghi = $donNghi->loaiNghiPhep;
                    $tenLoaiCheck = mb_strtolower($loaiNghi->ten, 'UTF-8');

                    if (!str_contains($tenLoaiCheck, 'thai sản') && !str_contains($tenLoaiCheck, 'không lương')) {
                        $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;
                        $soDuPhep = \App\Models\SoDuPhep::firstOrCreate(
                            ['nguoi_dung_id' => $donNghi->nguoi_dung_id, 'nam' => $namDonNghi],
                            ['phep_nam_moi' => 12.0, 'phep_cu_chuyen_sang' => 0.0, 'phep_da_dung' => 0.0]
                        );
                        $soDuPhep->increment('phep_da_dung', $donNghi->so_ngay_nghi);
                    }
                }

                // ⭐ GỬI THÔNG BÁO CHO TỪNG NHÂN VIÊN
                $action = $request->action === 'da_duyet' ? 'approved' : 'rejected';
                $this->notificationService->notifyLeaveRequest($donNghi, $action);

                $count++;
            }

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

    /**
     * Duyệt đơn lẻ (Đã cập nhật logic loại trừ trừ phép năm)
     */
    public function capNhatTrangThai(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:da_duyet,tu_choi,cho_duyet',
            'ly_do_tu_choi' => 'nullable|string|max:500',
        ]);

        // Bắt đầu dùng Transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            $donNghi = DonXinNghi::with('loaiNghiPhep')->findOrFail($id);

            if ($donNghi->trang_thai == 'huy_bo') {
                return redirect()->back()->with('error', '❌ Đơn này đã bị hủy, không thể thay đổi trạng thái!');
            }

            // Lưu lại trạng thái cũ trước khi update để check logic hoàn tác
            $trangThaiCu = $donNghi->trang_thai;
            $trangThaiMoi = $request->trang_thai;

            $donNghi->trang_thai = $trangThaiMoi;

            if ($trangThaiMoi == 'da_duyet' || $trangThaiMoi == 'tu_choi') {
                $donNghi->nguoi_duyet_id = auth()->id();
                $donNghi->thoi_gian_duyet = now();
            }

            if ($trangThaiMoi == 'tu_choi') {
                $donNghi->ghi_chu = $request->ly_do_tu_choi ?? 'Không có lý do';
            }

            if ($trangThaiMoi == 'cho_duyet') {
                $donNghi->nguoi_duyet_id = null;
                $donNghi->thoi_gian_duyet = null;
                $donNghi->ghi_chu = null;
            }

            $donNghi->save();

            // ⭐⭐⭐ LOGIC KHẤU TRỪ / HOÀN TÁC SỐ DƯ PHÉP ĐỘNG (ĐÃ LOẠI TRỪ THAI SẢN / KHÔNG LƯƠNG) ⭐⭐⭐
            $loaiNghi = $donNghi->loaiNghiPhep;
            $tenLoaiCheck = mb_strtolower($loaiNghi->ten, 'UTF-8');

            if (!str_contains($tenLoaiCheck, 'thai sản') && !str_contains($tenLoaiCheck, 'không lương')) {
                
                $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;

                // Tìm hoặc tự khởi tạo bản ghi số dư phép năm đó của nhân viên
                $soDuPhep = \App\Models\SoDuPhep::firstOrCreate(
                    ['nguoi_dung_id' => $donNghi->nguoi_dung_id, 'nam' => $namDonNghi],
                    ['phep_nam_moi' => 12.0, 'phep_cu_chuyen_sang' => 0.0, 'phep_da_dung' => 0.0]
                );

                // Trường hợp 1: Chuyển từ trạng thái khác SANG "Đã duyệt" -> Trừ số dư phép (Tăng phep_da_dung)
                if ($trangThaiMoi === 'da_duyet' && $trangThaiCu !== 'da_duyet') {
                    $soDuPhep->increment('phep_da_dung', $donNghi->so_ngay_nghi);
                }

                // Trường hợp 2: Hoàn tác từ "Đã duyệt" QUAY VỀ trạng thái khác -> Hoàn lại số dư phép (Giảm phep_da_dung)
                elseif ($trangThaiCu === 'da_duyet' && $trangThaiMoi !== 'da_duyet') {
                    // Đảm bảo không trừ âm giá trị phep_da_dung
                    $soDuPhep->phep_da_dung = max(0, $soDuPhep->phep_da_dung - $donNghi->so_ngay_nghi);
                    $soDuPhep->save();
                }
            }

            // ⭐⭐⭐ GỬI THÔNG BÁO KHI DUYỆT/TỪ CHỐI ⭐⭐⭐
            $action = $trangThaiMoi === 'da_duyet' ? 'approved' : 'rejected';
            if (in_array($trangThaiMoi, ['da_duyet', 'tu_choi'])) {
                $this->notificationService->notifyLeaveRequest($donNghi, $action);
            }

            DB::commit(); // Mọi thứ chạy mượt mà, lưu vào DB

            $thongBao = match ($trangThaiMoi) {
                'cho_duyet' => 'Đã hoàn tác đơn về trạng thái Chờ duyệt thành công!',
                'da_duyet' => 'Đã xử lý cập nhật trạng thái đơn nghỉ phép thành công!',
                'tu_choi' => 'Đã từ chối đơn nghỉ phép!',
                default => 'Cập nhật thành công!',
            };

            return redirect()->back()->with('success', $thongBao);
        } catch (\Exception $e) {
            DB::rollback(); // Có biến cố gì xảy ra, lập tức khôi phục trạng thái cũ
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $donNghi = DonXinNghi::findOrFail($id);
        $donNghi->trang_thai = 'tu_choi';
        $donNghi->ghi_chu = $request->ly_do_tu_choi;
        $donNghi->save();

        // ⭐⭐⭐ GỬI THÔNG BÁO TỪ CHỐI ⭐⭐⭐
        $this->notificationService->notifyLeaveRequest($donNghi, 'rejected');

        return redirect()->back()->with('error', 'Đã từ chối đơn nghỉ phép');
    }

    public function cancel($id)
    {
        $donNghi = DonXinNghi::findOrFail($id);
        $donNghi->trang_thai = 'huy_bo';
        $donNghi->save();

        // ⭐⭐⭐ GỬI THÔNG BÁO HỦY ĐƠN ⭐⭐⭐
        $this->notificationService->notifyLeaveRequest($donNghi, 'cancelled');

        return redirect()->back()->with('success', 'Đã hủy đơn nghỉ phép');
    }
}
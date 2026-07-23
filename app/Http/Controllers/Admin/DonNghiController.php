<?php
// app/Http/Controllers/Admin/DonNghiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonXinNghi;
use App\Models\LoaiNghiPhep;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('loai_nghi_phep_id')) {
            $query->where('loai_nghi_phep_id', $request->loai_nghi_phep_id);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        if ($request->filled('tu_ngay_nghi')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->tu_ngay_nghi);
        }
        if ($request->filled('den_ngay_nghi')) {
            $query->whereDate('ngay_ket_thuc', '<=', $request->den_ngay_nghi);
        }

        $danhSachDon = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        $countChoDuyet = DonXinNghi::where('trang_thai', 'cho_duyet')->count();
        $countDaDuyet = DonXinNghi::where('trang_thai', 'da_duyet')->count();
        $countTuChoi = DonXinNghi::where('trang_thai', 'tu_choi')->count();
        $countHuyBo = DonXinNghi::where('trang_thai', 'huy_bo')->count();
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
            $donNghiList = DonXinNghi::with('loaiNghiPhep')
                ->whereIn('id', $request->ids)
                ->where('trang_thai', 'cho_duyet')
                ->where('trang_thai', '!=', 'huy_bo')
                ->get();

            $count = 0;
            $currentUserId = Auth::id();

            foreach ($donNghiList as $donNghi) {

                // ⛔ Bỏ qua đơn của chính mình khi duyệt hàng loạt
                if ((int)$donNghi->nguoi_dung_id === (int)$currentUserId) {
                    continue;
                }

                $donNghi->update([
                    'trang_thai' => $request->action,
                    'nguoi_duyet_id' => $currentUserId,
                    'thoi_gian_duyet' => now(),
                    'ghi_chu' => $request->ly_do_tu_choi ?? ($request->action == 'da_duyet' ? 'Duyệt hàng loạt' : 'Từ chối hàng loạt'),
                ]);

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
     * Duyệt đơn lẻ (Đã thêm chặn tự duyệt đơn)
     */
    public function capNhatTrangThai(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:da_duyet,tu_choi,cho_duyet',
            'ly_do_tu_choi' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $donNghi = DonXinNghi::with('loaiNghiPhep')->findOrFail($id);

            // ⛔ CHẶN KHÔNG CHO TỰ DUYỆT ĐƠN CỦA CHÍNH MÌNH
            if ((int)$donNghi->nguoi_dung_id === (int)Auth::id()) {
                DB::rollBack();
                return redirect()->back()->with('error', '❌ Bạn không thể tự duyệt đơn xin nghỉ phép của chính mình!');
            }

            if ($donNghi->trang_thai == 'huy_bo') {
                DB::rollBack();
                return redirect()->back()->with('error', '❌ Đơn này đã bị hủy, không thể thay đổi trạng thái!');
            }

            $trangThaiCu = $donNghi->trang_thai;
            $trangThaiMoi = $request->trang_thai;

            $donNghi->trang_thai = $trangThaiMoi;

            if ($trangThaiMoi == 'da_duyet' || $trangThaiMoi == 'tu_choi') {
                $donNghi->nguoi_duyet_id = Auth::id();
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

            // LOGIC KHẤU TRỪ / HOÀN TÁC SỐ DƯ PHÉP
            $loaiNghi = $donNghi->loaiNghiPhep;
            $tenLoaiCheck = mb_strtolower($loaiNghi->ten, 'UTF-8');

            if (!str_contains($tenLoaiCheck, 'thai sản') && !str_contains($tenLoaiCheck, 'không lương')) {
                $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;

                $soDuPhep = \App\Models\SoDuPhep::firstOrCreate(
                    ['nguoi_dung_id' => $donNghi->nguoi_dung_id, 'nam' => $namDonNghi],
                    ['phep_nam_moi' => 12.0, 'phep_cu_chuyen_sang' => 0.0, 'phep_da_dung' => 0.0]
                );

                if ($trangThaiMoi === 'da_duyet' && $trangThaiCu !== 'da_duyet') {
                    $soDuPhep->increment('phep_da_dung', $donNghi->so_ngay_nghi);
                } elseif ($trangThaiCu === 'da_duyet' && $trangThaiMoi !== 'da_duyet') {
                    $soDuPhep->phep_da_dung = max(0, $soDuPhep->phep_da_dung - $donNghi->so_ngay_nghi);
                    $soDuPhep->save();
                }
            }

            // GỬI THÔNG BÁO
            $action = $trangThaiMoi === 'da_duyet' ? 'approved' : 'rejected';
            if (in_array($trangThaiMoi, ['da_duyet', 'tu_choi'])) {
                $this->notificationService->notifyLeaveRequest($donNghi, $action);
            }

            DB::commit();

            $thongBao = match ($trangThaiMoi) {
                'cho_duyet' => 'Đã hoàn tác đơn về trạng thái Chờ duyệt thành công!',
                'da_duyet' => 'Đã xử lý cập nhật trạng thái đơn nghỉ phép thành công!',
                'tu_choi' => 'Đã từ chối đơn nghỉ phép!',
                default => 'Cập nhật thành công!',
            };

            return redirect()->back()->with('success', $thongBao);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $donNghi = DonXinNghi::with('loaiNghiPhep')->findOrFail($id);

            // ⛔ CHẶN KHÔNG CHO TỰ TỪ CHỐI ĐƠN CỦA CHÍNH MÌNH
            if ((int)$donNghi->nguoi_dung_id === (int)Auth::id()) {
                DB::rollBack();
                return redirect()->back()->with('error', '❌ Bạn không thể tự thao tác trên đơn của chính mình!');
            }

            if ($donNghi->trang_thai == 'huy_bo') {
                DB::rollBack();
                return redirect()->back()->with('error', '❌ Đơn này đã bị hủy!');
            }

            $trangThaiCu = $donNghi->trang_thai;

            $donNghi->trang_thai = 'tu_choi';
            $donNghi->ghi_chu = $request->ly_do_tu_choi;
            $donNghi->nguoi_duyet_id = Auth::id();
            $donNghi->thoi_gian_duyet = now();
            $donNghi->save();

            $loaiNghi = $donNghi->loaiNghiPhep;
            $tenLoaiCheck = mb_strtolower($loaiNghi->ten, 'UTF-8');

            if (!str_contains($tenLoaiCheck, 'thai sản') && !str_contains($tenLoaiCheck, 'không lương')) {
                if ($trangThaiCu === 'da_duyet') {
                    $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;
                    $soDuPhep = \App\Models\SoDuPhep::where('nguoi_dung_id', $donNghi->nguoi_dung_id)
                        ->where('nam', $namDonNghi)
                        ->first();
                    if ($soDuPhep) {
                        $soDuPhep->phep_da_dung = max(0, $soDuPhep->phep_da_dung - $donNghi->so_ngay_nghi);
                        $soDuPhep->save();
                    }
                }
            }

            $this->notificationService->notifyLeaveRequest($donNghi, 'rejected');

            DB::commit();
            return redirect()->back()->with('error', 'Đã từ chối đơn nghỉ phép');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $donNghi = DonXinNghi::with('loaiNghiPhep')->findOrFail($id);
            $trangThaiCu = $donNghi->trang_thai;

            $donNghi->trang_thai = 'huy_bo';
            $donNghi->save();

            $loaiNghi = $donNghi->loaiNghiPhep;
            $tenLoaiCheck = mb_strtolower($loaiNghi->ten, 'UTF-8');

            if (!str_contains($tenLoaiCheck, 'thai sản') && !str_contains($tenLoaiCheck, 'không lương')) {
                if ($trangThaiCu === 'da_duyet') {
                    $namDonNghi = \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->year;
                    $soDuPhep = \App\Models\SoDuPhep::where('nguoi_dung_id', $donNghi->nguoi_dung_id)
                        ->where('nam', $namDonNghi)
                        ->first();
                    if ($soDuPhep) {
                        $soDuPhep->phep_da_dung = max(0, $soDuPhep->phep_da_dung - $donNghi->so_ngay_nghi);
                        $soDuPhep->save();
                    }
                }
            }

            $this->notificationService->notifyLeaveRequest($donNghi, 'cancelled');

            DB::commit();
            return redirect()->back()->with('success', 'Đã hủy đơn nghỉ phép');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BangLuong;
use App\Models\ChamCong;
use App\Models\PhongBan;
use App\Models\YeuCauDieuChinhCong;
use App\Models\NguoiDung;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PheDuyetYeuCauChinhCong;

class YeuCauDieuChinhCongAdminController extends Controller
{
    /**
     * Hiển thị danh sách tất cả yêu cầu điều chỉnh công
     */
    public function index(Request $request)
    {
        $query = YeuCauDieuChinhCong::with(['nguoiDung', 'nguoiDuyet', 'nguoiDung.hoSo', 'nguoiDung.phongBan']);

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo phòng ban
        if ($request->filled('phong_ban_id')) {
            $query->whereHas('nguoiDung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban_id);
            });
        }

        // Lọc theo ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay', '<=', $request->den_ngay);
        }

        // Tìm kiếm theo tên nhân viên
        if ($request->filled('tim_kiem')) {
            $keyword = $request->tim_kiem;
            $query->whereHas('nguoiDung.hoSo', function ($q) use ($keyword) {
                $q->where('ho', 'LIKE', "%{$keyword}%")
                    ->orWhere('ten', 'LIKE', "%{$keyword}%")
                    ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
            });
        }

        $yeuCauList = $query->orderBy('created_at', 'desc')->paginate(15);

        // Thống kê tổng quan
        $thongKe = [
            'tong_so' => YeuCauDieuChinhCong::count(),
            'cho_duyet' => YeuCauDieuChinhCong::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => YeuCauDieuChinhCong::where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => YeuCauDieuChinhCong::where('trang_thai', 'tu_choi')->count(),
        ];

        // Danh sách phòng ban để filter
        $phongBanList = PhongBan::orderBy('ten_phong_ban')->get();

        return view('admin.yeu-cau-dieu-chinh-cong.index', [
            'yeuCauList' => $yeuCauList,
            'thongKe' => $thongKe,
            'phongBanList' => $phongBanList,
        ]);
    }

    /**
     * Hiển thị chi tiết yêu cầu
     */
    public function show($id)
    {
        $yeuCau = YeuCauDieuChinhCong::with(['nguoiDung', 'nguoiDuyet', 'nguoiDung.hoSo', 'nguoiDung.phongBan'])
            ->findOrFail($id);

        return view('admin.yeu-cau-dieu-chinh-cong.show', compact('yeuCau'));
    }

    /**
     * Duyệt / từ chối yêu cầu (đơn lẻ)
     */
    public function duyet(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hanh_dong' => 'required|in:duyet,tu_choi',
            'ghi_chu_duyet' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $yeuCau = YeuCauDieuChinhCong::where('id', $id)
            ->where('trang_thai', 'cho_duyet')
            ->first();

        if (!$yeuCau) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy bản ghi hoặc yêu cầu đã được duyệt.']);
        }

        $chamCong = ChamCong::where('nguoi_dung_id', $yeuCau->nguoi_dung_id)
            ->whereDate('ngay_cham_cong', $yeuCau->ngay)
            ->first();

        DB::beginTransaction();

        try {
            $trangThaiMoi = $request->hanh_dong === 'duyet' ? 'da_duyet' : 'tu_choi';

            if ($trangThaiMoi === 'da_duyet') {
                // Kiểm tra đã chốt lương chưa
                $thang = Carbon::parse($yeuCau->ngay)->month;
                $nam = Carbon::parse($yeuCau->ngay)->year;

                $daChotLuong = BangLuong::where('nguoi_xu_ly_id', $yeuCau->nguoi_dung_id)
                    ->where('thang', $thang)
                    ->where('nam', $nam)
                    ->exists();

                if ($daChotLuong) {
                    return redirect()->route('admin.yeu-cau-dieu-chinh-cong.index')
                        ->withErrors(['error' => "Nhân viên đã được chốt lương tháng {$thang}/{$nam}, không thể phê duyệt."]);
                }

                if ($chamCong) {
                    $chamCong->update([
                        'gio_vao' => $yeuCau->gio_vao,
                        'gio_ra' => $yeuCau->gio_ra,
                        'ghi_chu' => $yeuCau->ly_do,
                        'trang_thai_duyet' => 1
                    ]);
                } else {
                    $chamCong = ChamCong::create([
                        'nguoi_dung_id' => $yeuCau->nguoi_dung_id,
                        'ngay_cham_cong' => $yeuCau->ngay,
                        'gio_vao' => $yeuCau->gio_vao,
                        'gio_ra' => $yeuCau->gio_ra,
                        'ghi_chu' => $yeuCau->ly_do,
                        'trang_thai_duyet' => 1
                    ]);
                }

                // Cập nhật trạng thái thủ công
                if ($chamCong->gio_vao && $chamCong->kiemTraDiMuon()) {
                    $chamCong->trang_thai = 'di_muon';
                } elseif ($chamCong->gio_ra && $chamCong->kiemTraVeSom()) {
                    $chamCong->trang_thai = 've_som';
                } elseif ($chamCong->gio_vao && $chamCong->gio_ra) {
                    $chamCong->trang_thai = 'dung_gio';
                } else {
                    $chamCong->trang_thai = 'khong_cham_cong';
                }
                $chamCong->save();
            }

            $yeuCau->update([
                'trang_thai' => $trangThaiMoi,
                'duyet_boi' => Auth::id(),
                'duyet_vao' => Carbon::now(),
                'ghi_chu_duyet' => $request->ghi_chu_duyet
            ]);

            $yeuCau->nguoiDung->notify(new PheDuyetYeuCauChinhCong($yeuCau, $trangThaiMoi));
            DB::commit();

            $thongBao = $request->hanh_dong === 'duyet'
                ? 'Đã duyệt yêu cầu thành công!'
                : 'Đã từ chối yêu cầu thành công!';

            return redirect()->route('admin.yeu-cau-dieu-chinh-cong.index')->with('success', $thongBao);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Duyệt / từ chối hàng loạt
     */
    public function duyetHangLoat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'yeu_cau_ids' => 'required|json',
            'hanh_dong' => 'required|in:duyet,tu_choi',
            'ghi_chu_duyet' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $ids = json_decode($request->yeu_cau_ids, true);
        $trangThaiMoi = $request->hanh_dong === 'duyet' ? 'da_duyet' : 'tu_choi';

        $dsYeuCau = YeuCauDieuChinhCong::whereIn('id', $ids)
            ->where('trang_thai', 'cho_duyet')
            ->get();

        if ($dsYeuCau->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có yêu cầu nào ở trạng thái chờ duyệt'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $soLuongCapNhat = 0;

            foreach ($dsYeuCau as $yeuCau) {
                if ($trangThaiMoi === 'da_duyet') {
                    // Kiểm tra đã chốt lương
                    $thang = Carbon::parse($yeuCau->ngay)->month;
                    $nam = Carbon::parse($yeuCau->ngay)->year;

                    $daChotLuong = BangLuong::where('nguoi_xu_ly_id', $yeuCau->nguoi_dung_id)
                        ->where('thang', $thang)
                        ->where('nam', $nam)
                        ->exists();

                    if ($daChotLuong) {
                        continue; // Bỏ qua yêu cầu này
                    }

                    $chamCong = ChamCong::where('nguoi_dung_id', $yeuCau->nguoi_dung_id)
                        ->whereDate('ngay_cham_cong', $yeuCau->ngay)
                        ->first();

                    if ($chamCong) {
                        $chamCong->update([
                            'gio_vao' => $yeuCau->gio_vao,
                            'gio_ra' => $yeuCau->gio_ra,
                            'ghi_chu' => $yeuCau->ly_do,
                            'trang_thai_duyet' => 1
                        ]);
                    } else {
                        $chamCong = ChamCong::create([
                            'nguoi_dung_id' => $yeuCau->nguoi_dung_id,
                            'ngay_cham_cong' => $yeuCau->ngay,
                            'gio_vao' => $yeuCau->gio_vao,
                            'gio_ra' => $yeuCau->gio_ra,
                            'ghi_chu' => $yeuCau->ly_do,
                            'trang_thai_duyet' => 1
                        ]);
                    }

                    $chamCong->capNhatTrangThai();
                    $chamCong->save();
                }

                $yeuCau->update([
                    'trang_thai' => $trangThaiMoi,
                    'duyet_boi' => Auth::id(),
                    'duyet_vao' => Carbon::now(),
                    'ghi_chu_duyet' => $request->ghi_chu_duyet
                ]);

                // Gửi thông báo
                $yeuCau->nguoiDung->notify(new DuyetDonController($yeuCau, $trangThaiMoi));

                $soLuongCapNhat++;
            }

            DB::commit();

            $thongBao = $request->hanh_dong === 'duyet'
                ? "Đã duyệt {$soLuongCapNhat} yêu cầu thành công!"
                : "Đã từ chối {$soLuongCapNhat} yêu cầu thành công!";

            return response()->json([
                'success' => true,
                'message' => $thongBao,
                'affected_count' => $soLuongCapNhat
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa yêu cầu
     */
    public function destroy($id)
    {
        $yeuCau = YeuCauDieuChinhCong::findOrFail($id);

        if ($yeuCau->trang_thai !== 'cho_duyet') {
            return redirect()->back()->withErrors(['error' => 'Chỉ có thể xóa yêu cầu đang chờ duyệt.']);
        }

        DB::beginTransaction();

        try {
            if ($yeuCau->tep_dinh_kem && Storage::disk('public')->exists($yeuCau->tep_dinh_kem)) {
                Storage::disk('public')->delete($yeuCau->tep_dinh_kem);
            }

            $yeuCau->delete();

            DB::commit();

            return redirect()->route('admin.yeu-cau-dieu-chinh-cong.index')
                ->with('success', 'Xóa yêu cầu thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Download file đính kèm
     */
    public function downloadFile($id)
    {
        $yeuCau = YeuCauDieuChinhCong::findOrFail($id);

        if (!$yeuCau->tep_dinh_kem || !Storage::disk('public')->exists($yeuCau->tep_dinh_kem)) {
            abort(404, 'File không tồn tại');
        }

        $filePath = Storage::disk('public')->path($yeuCau->tep_dinh_kem);
        $fileName = basename($yeuCau->tep_dinh_kem);

        return response()->download($filePath, $fileName);
    }

    /**
     * Báo cáo thống kê
     */
    public function baoCao(Request $request)
    {
        $tuNgay = $request->input('tu_ngay', Carbon::now()->subMonth()->format('Y-m-d'));
        $denNgay = $request->input('den_ngay', Carbon::now()->format('Y-m-d'));

        // Thống kê theo trạng thái
        $thongKeTheoTrangThai = YeuCauDieuChinhCong::whereBetween('ngay', [$tuNgay, $denNgay])
            ->selectRaw('trang_thai, COUNT(*) as so_luong')
            ->groupBy('trang_thai')
            ->pluck('so_luong', 'trang_thai')
            ->toArray();

        // Thống kê theo phòng ban
        $thongKeTheoPhongBan = YeuCauDieuChinhCong::with('nguoiDung.phongBan')
            ->whereBetween('ngay', [$tuNgay, $denNgay])
            ->get()
            ->groupBy(function ($item) {
                return optional($item->nguoiDung->phongBan)->ten_phong_ban ?? 'Chưa có phòng ban';
            })
            ->map(function ($items) {
                return [
                    'tong_so' => $items->count(),
                    'cho_duyet' => $items->where('trang_thai', 'cho_duyet')->count(),
                    'da_duyet' => $items->where('trang_thai', 'da_duyet')->count(),
                    'tu_choi' => $items->where('trang_thai', 'tu_choi')->count(),
                ];
            });

        // Thống kê theo tháng
        $thongKeTheoThang = YeuCauDieuChinhCong::whereBetween('ngay', [$tuNgay, $denNgay])
            ->selectRaw('DATE_FORMAT(ngay, "%Y-%m") as thang, COUNT(*) as so_luong, trang_thai')
            ->groupBy('thang', 'trang_thai')
            ->orderBy('thang')
            ->get()
            ->groupBy('thang')
            ->map(function ($items) {
                return [
                    'tong_so' => $items->sum('so_luong'),
                    'cho_duyet' => $items->where('trang_thai', 'cho_duyet')->sum('so_luong'),
                    'da_duyet' => $items->where('trang_thai', 'da_duyet')->sum('so_luong'),
                    'tu_choi' => $items->where('trang_thai', 'tu_choi')->sum('so_luong'),
                ];
            });

        // Top nhân viên
        $topNhanVien = YeuCauDieuChinhCong::with('nguoiDung.hoSo', 'nguoiDung.phongBan')
            ->whereBetween('ngay', [$tuNgay, $denNgay])
            ->selectRaw('nguoi_dung_id, COUNT(*) as so_luong')
            ->groupBy('nguoi_dung_id')
            ->orderByDesc('so_luong')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'ho_ten' => optional($item->nguoiDung->hoSo)->ho . ' ' . optional($item->nguoiDung->hoSo)->ten,
                    'ma_nhan_vien' => optional($item->nguoiDung->hoSo)->ma_nhan_vien,
                    'phong_ban' => optional($item->nguoiDung->phongBan)->ten_phong_ban,
                    'so_luong' => $item->so_luong
                ];
            });

        return view('admin.yeu-cau-dieu-chinh-cong.baocao', [
            'thongKeTheoTrangThai' => $thongKeTheoTrangThai,
            'thongKeTheoPhongBan' => $thongKeTheoPhongBan,
            'thongKeTheoThang' => $thongKeTheoThang,
            'topNhanVien' => $topNhanVien,
            'tuNgay' => $tuNgay,
            'denNgay' => $denNgay
        ]);
    }
}

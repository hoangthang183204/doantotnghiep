<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichSuLuong;
use App\Models\LichSuTaiKy;
use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use App\Models\PhuCapNhanVien; 
use App\Models\PhuCap;          
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TangLuongController extends Controller
{
    /**
     * Form tăng lương cho nhân viên
     */
    public function create($id)
    {
        $hopDong = HopDongLaoDong::with(['nguoiDung.hoSo', 'nguoiDung.phuCapNhanViens.phuCap'])
            ->findOrFail($id);

        if ($hopDong->trang_thai_hop_dong !== 'hieu_luc') {
            return back()->with('error', 'Hợp đồng không ở trạng thái hiệu lực!');
        }

        $nhanVien = $hopDong->nguoiDung;

        $lichSuTangLuong = LichSuLuong::where('nguoi_dung_id', $nhanVien->id)
            ->where('trang_thai', 'da_duyet')
            ->orderBy('ngay_ap_dung', 'desc')
            ->limit(10)
            ->get();

        return view('admin.tang-luong.create', compact('hopDong', 'nhanVien', 'lichSuTangLuong'));
    }

    /**
     * Xử lý tăng lương
     */
    public function store(Request $request)
    {
        $request->validate([
            'hop_dong_id' => 'required|exists:hop_dong_lao_dong,id',
            'luong_moi' => 'required|numeric|min:0',
            'ngay_ap_dung' => 'required|date|after_or_equal:today',
            'loai' => 'required|in:tang_luong,giam_luong,dieu_chinh',
            'ly_do' => 'nullable|string|max:500',
            'tai_ky' => 'nullable|boolean',
        ]);

        $hopDong = HopDongLaoDong::with(['nguoiDung', 'nguoiDung.hoSo'])
            ->findOrFail($request->hop_dong_id);

        if ($hopDong->trang_thai_hop_dong !== 'hieu_luc') {
            return back()->with('error', 'Hợp đồng không ở trạng thái hiệu lực!');
        }

        if ($request->luong_moi == $hopDong->luong_co_ban) {
            return back()->with('error', 'Lương mới phải khác lương hiện tại!');
        }

        if ($request->loai == 'giam_luong' && empty($request->ly_do)) {
            return back()->with('error', 'Vui lòng nhập lý do khi giảm lương!');
        }

        // ⭐ LƯU LƯƠNG CŨ TRƯỚC
        $luongCu = $hopDong->luong_co_ban;
        $luongMoi = $request->luong_moi;

        DB::beginTransaction();

        try {
            // 1. Lưu lịch sử thay đổi lương
            $lichSu = LichSuLuong::create([
                'nguoi_dung_id' => $hopDong->nguoi_dung_id,
                'hop_dong_id' => $hopDong->id,
                'luong_cu' => $luongCu,
                'luong_moi' => $luongMoi,
                'phu_cap_cu' => $hopDong->phu_cap ?? 0,
                'phu_cap_moi' => $hopDong->phu_cap ?? 0,
                'ngay_ap_dung' => $request->ngay_ap_dung,
                'loai' => $request->loai,
                'ly_do' => $request->ly_do,
                'nguoi_tao_id' => Auth::id(),
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => Auth::id(),
                'thoi_gian_duyet' => now(),
            ]);

            // 2. Nếu chọn tái ký -> Tạo hợp đồng mới
            if ($request->tai_ky) {
                // ⭐ TẠO HỢP ĐỒNG MỚI VỚI LƯƠNG MỚI
                $hopDongMoi = $this->taiKyHopDong($hopDong, $luongCu, $luongMoi);

                // ⭐ XÓA PHỤ CẤP CỦA HỢP ĐỒNG CŨ
                PhuCapNhanVien::where('nguoi_dung_id', $hopDong->nguoi_dung_id)
                    ->where('ghi_chu', 'LIKE', '%từ hợp đồng ' . $hopDong->so_hop_dong . '%')
                    ->delete();

                // ⭐ THÊM PHỤ CẤP CHO HỢP ĐỒNG MỚI (nếu có)
                if ($hopDong->phu_cap) {
                    $phuCapIds = is_string($hopDong->phu_cap) 
                        ? json_decode($hopDong->phu_cap, true) 
                        : $hopDong->phu_cap;
                        
                    if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                        foreach ($phuCapIds as $phuCapId) {
                            $phuCap = PhuCap::find($phuCapId);
                            if ($phuCap) {
                                PhuCapNhanVien::create([
                                    'nguoi_dung_id' => $hopDong->nguoi_dung_id,
                                    'phu_cap_id' => $phuCapId,
                                    'so_tien' => $phuCap->so_tien_mac_dinh,
                                    'ngay_hieu_luc' => $hopDongMoi->ngay_bat_dau,
                                    'ngay_ket_thuc' => $hopDongMoi->ngay_ket_thuc,
                                    'trang_thai' => 'hieu_luc',
                                    'ghi_chu' => 'Phụ cấp từ hợp đồng ' . $hopDongMoi->so_hop_dong,
                                ]);
                            }
                        }
                    }
                }

                // ⭐ CẬP NHẬT HỢP ĐỒNG CŨ: KHÔNG CẬP NHẬT LƯƠNG
                $hopDong->update([
                    'trang_thai_hop_dong' => 'het_han',
                    'trang_thai_tai_ky' => 'da_tai_ky',
                    'ngay_ket_thuc' => now(),
                    'ghi_chu' => ($hopDong->ghi_chu ? $hopDong->ghi_chu . ' | ' : '') 
                        . 'Đã tái ký sang hợp đồng ' . $hopDongMoi->so_hop_dong 
                        . ' (ngày ' . now()->format('d/m/Y') . ')'
                ]);

                // Cập nhật lịch sử với hợp đồng mới
                $lichSu->update([
                    'hop_dong_id' => $hopDongMoi->id
                ]);

                // Lưu lịch sử tái ký
                if (class_exists(\App\Models\LichSuTaiKy::class)) {
                    \App\Models\LichSuTaiKy::create([
                        'hop_dong_cu_id' => $hopDong->id,
                        'hop_dong_moi_id' => $hopDongMoi->id,
                        'nguoi_thuc_hien_id' => Auth::id(),
                        'ly_do_tai_ky' => 'Tái ký do ' . ($request->loai == 'tang_luong' ? 'tăng' : 'giảm') . ' lương từ ' . number_format($luongCu) . ' lên ' . number_format($luongMoi),
                    ]);
                }

                DB::commit();

                return redirect()
                    ->route('admin.hop-dong.show', $hopDongMoi->id)
                    ->with('success', '✅ Đã tăng lương và tạo hợp đồng mới thành công! Vui lòng gửi cho nhân viên ký.');
            }

            // 3. Nếu KHÔNG tái ký -> Chỉ cập nhật lương trên hợp đồng hiện tại
            $hopDong->update([
                'luong_co_ban' => $luongMoi,
                'ghi_chu' => ($hopDong->ghi_chu ? $hopDong->ghi_chu . ' | ' : '')
                    . ($request->loai == 'tang_luong' ? 'Tăng' : 'Giảm') . ' lương từ '
                    . number_format($luongCu) . ' lên ' . number_format($luongMoi)
                    . ' (ngày ' . Carbon::parse($request->ngay_ap_dung)->format('d/m/Y') . ')'
                    . ($request->ly_do ? ' - ' . $request->ly_do : ''),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.hop-dong.show', $hopDong->id)
                ->with('success', '✅ Đã ' . ($request->loai == 'tang_luong' ? 'tăng' : 'giảm') . ' lương thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạo hợp đồng mới từ hợp đồng cũ
     */
    private function taiKyHopDong(HopDongLaoDong $hopDongCu, $luongCu, $luongMoi)
    {
        $year = date('Y');
        do {
            $soHopDong = 'HD' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . '-' . $year;
        } while (HopDongLaoDong::where('so_hop_dong', $soHopDong)->exists());

        $ngayBatDauMoi = $hopDongCu->ngay_ket_thuc
            ? Carbon::parse($hopDongCu->ngay_ket_thuc)->addDay()
            : Carbon::now()->addDay();

        $ngayKetThucMoi = null;
        if ($hopDongCu->loai_hop_dong != 'khong_xac_dinh_thoi_han') {
            $ngayKetThucMoi = $hopDongCu->ngay_ket_thuc
                ? Carbon::parse($hopDongCu->ngay_ket_thuc)->addYear()
                : Carbon::now()->addYear();
        }

        // ⭐ TẠO HỢP ĐỒNG MỚI VỚI LƯƠNG MỚI
        $hopDongMoi = HopDongLaoDong::create([
            'created_by' => Auth::id(),
            'nguoi_dung_id' => $hopDongCu->nguoi_dung_id,
            'chuc_vu_id' => $hopDongCu->chuc_vu_id,
            'so_hop_dong' => $soHopDong,
            'loai_hop_dong' => $hopDongCu->loai_hop_dong,
            'ngay_bat_dau' => $ngayBatDauMoi,
            'ngay_ket_thuc' => $ngayKetThucMoi,
            'luong_co_ban' => $luongMoi,
            'phu_cap_id' => $hopDongCu->phu_cap_id,
            'phu_cap' => $hopDongCu->phu_cap,
            'dia_diem_lam_viec' => $hopDongCu->dia_diem_lam_viec,
            'dieu_khoan' => $hopDongCu->dieu_khoan,
            'ghi_chu' => 'Tái ký từ hợp đồng ' . $hopDongCu->so_hop_dong . ' (tăng lương từ ' . number_format($luongCu) . ' lên ' . number_format($luongMoi) . ')',
            'trang_thai_hop_dong' => 'tao_moi',
            'trang_thai_ky' => 'cho_ky',
            'trang_thai_tai_ky' => null,
        ]);

        return $hopDongMoi;
    }

    /**
     * Duyệt tăng lương
     */
    public function duyet($id)
    {
        $lichSu = LichSuLuong::findOrFail($id);

        if ($lichSu->trang_thai !== 'cho_duyet') {
            return back()->with('error', 'Lịch sử lương này không ở trạng thái chờ duyệt!');
        }

        DB::beginTransaction();

        try {
            $lichSu->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => Auth::id(),
                'thoi_gian_duyet' => now(),
            ]);

            $hopDong = HopDongLaoDong::find($lichSu->hop_dong_id);
            if ($hopDong) {
                $hopDong->update([
                    'luong_co_ban' => $lichSu->luong_moi,
                ]);
            }

            DB::commit();

            return back()->with('success', '✅ Đã duyệt tăng lương thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Từ chối tăng lương
     */
    public function tuChoi(Request $request, $id)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ]);

        $lichSu = LichSuLuong::findOrFail($id);

        if ($lichSu->trang_thai !== 'cho_duyet') {
            return back()->with('error', 'Lịch sử lương này không ở trạng thái chờ duyệt!');
        }

        $lichSu->update([
            'trang_thai' => 'tu_choi',
            'ghi_chu' => ($lichSu->ghi_chu ? $lichSu->ghi_chu . ' | ' : '') . 'Từ chối: ' . $request->ly_do_tu_choi,
        ]);

        return back()->with('success', '✅ Đã từ chối tăng lương!');
    }
}
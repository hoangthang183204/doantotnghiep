<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ChungChiNhanVien;
use App\Models\DaoTaoNhanVien;
use App\Models\HopDongLaoDong;
use App\Models\HoSo;
use App\Models\HoSoNguoiDung;
use App\Models\KyNangNhanVien;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Models\NguoiPhuThuoc;
use App\Models\PhuCap;
use App\Models\PhuCapNhanVien;
use App\Models\TaiLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class HoSoController extends Controller
{
    /**
     * Hiển thị trang xem hồ sơ cá nhân (show)
     * Không cần tham số vì lấy từ Auth::user()
     */
    public function show()
    {
        /** @var NguoiDung $user */
        $user = Auth::user();

        // Load các quan hệ cơ bản
        $user->load([
            'hoSo',
            'phong_ban',
            'chuc_vu',
            'vai_tro',
        ]);

        // LẤY ĐÚNG HO SO (HoSoNguoiDung)
        $hoSoNguoiDung = $user->hoSo;

        // LẤY HO SO (HoSo) - ĐÂY LÀ BẢNG CHÍNH CHỨA THÔNG TIN
        $hoSo = $hoSoNguoiDung?->hoSo; // <--- SỬA: dùng ->hoSo thay vì $hoSo

        // Lấy hợp đồng hiệu lực
        $hopDongHieuLuc = $hoSo?->hop_dong
            ?->where('trang_thai_hop_dong', 'hieu_luc')
            ?->first();

        // Load thêm các quan hệ cho HoSo
        if ($hoSo) {
            $hoSo->load([
                'ky_nang',
                'chung_chi',
                'dao_tao',
                'nguoiPhuThuoc',
                'cv',
                'hop_dong',
                'khen_thuong_ky_luat',
            ]);
        }

        // Lấy bảng lương gần nhất
        $luongGanNhat = LuongNhanVien::where('nguoi_dung_id', $user->id)
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->first();

        // Tính toán lương
        $luongCoBanHienTai = $hopDongHieuLuc?->luong_co_ban ?? 0;

        // Tính phụ cấp
        $tongPhuCap = 0;
        if ($hopDongHieuLuc) {
            if (!empty($hopDongHieuLuc->phu_cap)) {
                $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                    ? json_decode($hopDongHieuLuc->phu_cap, true)
                    : $hopDongHieuLuc->phu_cap;

                if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                    $tongPhuCap = PhuCap::whereIn('id', $phuCapIds)->sum('so_tien_mac_dinh');
                }
            }

            if ($tongPhuCap == 0) {
                $phuCapNhanVien = PhuCapNhanVien::where('nguoi_dung_id', $user->id)
                    ->where('trang_thai', 'hieu_luc')
                    ->where('ngay_hieu_luc', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('ngay_ket_thuc')->orWhere('ngay_ket_thuc', '>=', now());
                    })
                    ->sum('so_tien');
                $tongPhuCap = $phuCapNhanVien > 0 ? $phuCapNhanVien : 0;
            }
        }

        // Tăng ca
        $tienTangCa = $luongGanNhat?->tien_tang_ca ?? 0;
        $coTangCa = $tienTangCa > 0;

        // Tổng thu nhập
        $tongThuNhap = $luongCoBanHienTai + $tongPhuCap + $tienTangCa;

        // Bảo hiểm (10.5%)
        $luongDongBhxh = $hopDongHieuLuc?->luong_co_ban ?? 0;
        $bhxh = round($luongDongBhxh * 0.08, 0);
        $bhyt = round($luongDongBhxh * 0.015, 0);
        $bhtn = round($luongDongBhxh * 0.01, 0);
        $tongBaoHiem = $bhxh + $bhyt + $bhtn;

        // Giảm trừ gia cảnh
        $soNguoiPhuThuoc = $hoSo?->nguoiPhuThuoc?->count() ?? 0;
        $giamTruBanThan = 15500000;
        $giamTruGiaCanh = $giamTruBanThan + 6200000 * $soNguoiPhuThuoc;

        // Thuế TNCN
        $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruGiaCanh);

        $thueTncn = 0;
        $remaining = $thuNhapTinhThue;
        $bac = [
            ['tu' => 0, 'den' => 10000000, 'thue_suat' => 0.05],
            ['tu' => 10000000, 'den' => 30000000, 'thue_suat' => 0.1],
            ['tu' => 30000000, 'den' => 60000000, 'thue_suat' => 0.2],
            ['tu' => 60000000, 'den' => 100000000, 'thue_suat' => 0.3],
            ['tu' => 100000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
        ];
        foreach ($bac as $b) {
            if ($remaining <= 0) break;
            $khoang = min($remaining, $b['den'] - $b['tu']);
            $thueTncn += $khoang * $b['thue_suat'];
            $remaining -= $khoang;
        }
        $thueTncn = round($thueTncn, 0);

        $thucNhan = $tongThuNhap - $tongBaoHiem - $thueTncn;

        // Lấy chi tiết phụ cấp
        $phuCapChiTiets = collect();
        if ($hopDongHieuLuc && !empty($hopDongHieuLuc->phu_cap)) {
            $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                ? json_decode($hopDongHieuLuc->phu_cap, true)
                : $hopDongHieuLuc->phu_cap;
            if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                $phuCapChiTiets = PhuCap::whereIn('id', $phuCapIds)->get();
            }
        }

        return view('employee.ho-so.show', compact(
            'user',
            'hoSo',              // <--- $hoSo là HoSo model (bảng chính)
            'hoSoNguoiDung',     // <--- thêm biến này nếu cần
            'hopDongHieuLuc',
            'luongGanNhat',
            'luongCoBanHienTai',
            'tongPhuCap',
            'tienTangCa',
            'coTangCa',
            'tongThuNhap',
            'luongDongBhxh',
            'bhxh',
            'bhyt',
            'bhtn',
            'tongBaoHiem',
            'soNguoiPhuThuoc',
            'thuNhapChiuThue',
            'thueTncn',
            'thucNhan',
            'phuCapChiTiets'
        ));
    }

    /**
     * Hiển thị trang hồ sơ cá nhân (index - form chỉnh sửa)
     */
    public function index()
    {
        /** @var NguoiDung $user */
        $user = Auth::user();

        // Load các quan hệ
        $user->load([
            'hoSo',
            'phong_ban',
            'chuc_vu',
            'vai_tro',
        ]);

        // Load thêm các quan hệ con từ HoSo
        if ($user->hoSo) {
            $hoSo = $user->hoSo->hoSo;
            if ($hoSo) {
                $hoSo->load([
                    'ky_nang',
                    'chung_chi',
                    'dao_tao',
                    'nguoiPhuThuoc',
                    'cv',
                    'hop_dong',
                    'khen_thuong_ky_luat',
                ]);
            }
        }

        return view('employee.ho-so.index', compact('user'));
    }

    /**
     * Cập nhật thông tin hồ sơ
     */
    public function update(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'ho' => 'required|string|max:100',
            'ten' => 'required|string|max:100',

            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',

            'dia_chi_hien_tai' => 'nullable|string|max:500',
            'dia_chi_thuong_tru' => 'nullable|string|max:500',

            'cmnd_cccd' => 'nullable|string|max:20',
            'so_ho_chieu' => 'nullable|string|max:50',

            'tinh_trang_hon_nhan' => 'nullable|string|max:50',

            'lien_he_khan_cap' => 'nullable|string|max:255',
            'sdt_khan_cap' => 'nullable|string|max:20',
            'quan_he_khan_cap' => 'nullable|string|max:100',

            'chu_tai_khoan' => 'nullable|string|max:255',
            'so_tai_khoan' => 'nullable|string|max:100',
            'ten_ngan_hang' => 'nullable|string|max:255',
            'chi_nhanh_ngan_hang' => 'nullable|string|max:255',

            'so_bhxh' => 'nullable|string|max:100',
            'ma_so_thue' => 'nullable|string|max:100',
            'noi_dang_ky_kcb' => 'nullable|string|max:255',

            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_truoc' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_sau' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        /** @var NguoiDung $user */
        $user = Auth::user();

        // ============================================
        // 1. LẤY HOẶC TẠO HỒ SƠ NHÂN SỰ (HoSoNguoiDung)
        // ============================================
        $hoSoNguoiDung = $user->hoSo;

        if (!$hoSoNguoiDung) {
            $hoSoNguoiDung = HoSoNguoiDung::create([
                'nguoi_dung_id' => $user->id,
            ]);
        }

        // ============================================
        // 2. CẬP NHẬT THÔNG TIN CÁ NHÂN
        // ============================================
        $data = [
            'ho' => $request->ho,
            'ten' => $request->ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'dia_chi_hien_tai' => $request->dia_chi_hien_tai,
            'dia_chi_thuong_tru' => $request->dia_chi_thuong_tru,
            'cmnd_cccd' => $request->cmnd_cccd,
            'so_ho_chieu' => $request->so_ho_chieu,
            'tinh_trang_hon_nhan' => $request->tinh_trang_hon_nhan,
            'lien_he_khan_cap' => $request->lien_he_khan_cap,
            'sdt_khan_cap' => $request->sdt_khan_cap,
            'quan_he_khan_cap' => $request->quan_he_khan_cap,
            'chu_tai_khoan' => $request->chu_tai_khoan,
            'so_tai_khoan' => $request->so_tai_khoan,
            'ten_ngan_hang' => $request->ten_ngan_hang,
            'chi_nhanh_ngan_hang' => $request->chi_nhanh_ngan_hang,
            'so_bhxh' => $request->so_bhxh,
            'ma_so_thue' => $request->ma_so_thue,
            'noi_dang_ky_kcb' => $request->noi_dang_ky_kcb,
        ];

        // Xử lý upload ảnh đại diện
        if ($request->hasFile('anh_dai_dien')) {
            if ($hoSoNguoiDung->anh_dai_dien && Storage::disk('public')->exists($hoSoNguoiDung->anh_dai_dien)) {
                Storage::disk('public')->delete($hoSoNguoiDung->anh_dai_dien);
            }
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        // Xử lý upload ảnh CCCD mặt trước
        if ($request->hasFile('anh_cccd_truoc')) {
            if ($hoSoNguoiDung->anh_cccd_truoc && Storage::disk('public')->exists($hoSoNguoiDung->anh_cccd_truoc)) {
                Storage::disk('public')->delete($hoSoNguoiDung->anh_cccd_truoc);
            }
            $data['anh_cccd_truoc'] = $request->file('anh_cccd_truoc')->store('cccd', 'public');
        }

        // Xử lý upload ảnh CCCD mặt sau
        if ($request->hasFile('anh_cccd_sau')) {
            if ($hoSoNguoiDung->anh_cccd_sau && Storage::disk('public')->exists($hoSoNguoiDung->anh_cccd_sau)) {
                Storage::disk('public')->delete($hoSoNguoiDung->anh_cccd_sau);
            }
            $data['anh_cccd_sau'] = $request->file('anh_cccd_sau')->store('cccd', 'public');
        }

        $hoSoNguoiDung->update($data);

        // ============================================
        // 3. LẤY HOẶC TẠO HỒ SƠ (HoSo)
        // ============================================
        $hoSo = $hoSoNguoiDung->hoSo;

        if (!$hoSo) {
            $hoSo = HoSo::create([
                'nguoi_dung_id' => $user->id,
            ]);

            if (Schema::hasColumn('ho_so_nguoi_dung', 'ho_so_id')) {
                $hoSoNguoiDung->ho_so_id = $hoSo->id;
                $hoSoNguoiDung->save();
            }
        }

        // ============================================
        // 4. XỬ LÝ CV
        // ============================================
        if ($request->hasFile('cv_file')) {
            $cvCu = $hoSo->cv;
            if ($cvCu && $cvCu->duong_dan_file && Storage::disk('public')->exists($cvCu->duong_dan_file)) {
                Storage::disk('public')->delete($cvCu->duong_dan_file);
                $cvCu->delete();
            }

            $file = $request->file('cv_file');
            $path = $file->store('cv', 'public');

            $hoSo->cv()->create([
                'nguoi_dung_id' => Auth::id(),
                'loai_tai_lieu' => 'cv',
                'tieu_de' => 'CV',
                'ten_file_goc' => $file->getClientOriginalName(),
                'duong_dan_file' => $path,
                'kich_thuoc_file' => $file->getSize(),
                'loai_mime' => $file->getMimeType(),
                'nguoi_tai_len_id' => Auth::id(),
                'thoi_gian_tai_len' => now(),
            ]);
        }

        // ============================================
        // 5. HÀM XỬ LÝ XÓA
        // ============================================
        $parseDeleteIds = function ($input) {
            if (empty($input)) {
                return [];
            }
            if (is_string($input)) {
                $ids = array_filter(array_map('trim', explode(',', $input)));
                return array_values($ids);
            }
            if (is_array($input)) {
                return $input;
            }
            return [];
        };

        DB::beginTransaction();

        try {
            // ============================================
            // 6. XÓA KỸ NĂNG
            // ============================================
            $deleteSkills = $parseDeleteIds($request->input('delete_skills'));
            if (!empty($deleteSkills)) {
                $deletedCount = KyNangNhanVien::whereIn('id', $deleteSkills)
                    ->where('ho_so_id', $hoSo->id)
                    ->delete();

                Log::info('Deleted skills', [
                    'ids' => $deleteSkills,
                    'user_id' => $user->id,
                    'deleted_count' => $deletedCount
                ]);
            }

            // ============================================
            // 7. CẬP NHẬT KỸ NĂNG CŨ
            // ============================================
            if ($request->filled('skills')) {
                foreach ($request->skills as $id => $skill) {
                    KyNangNhanVien::where('id', $id)
                        ->where('ho_so_id', $hoSo->id)
                        ->update([
                            'ten_ky_nang' => $skill['ten_ky_nang'] ?? null,
                            'cap_do' => $skill['cap_do'] ?? null,
                        ]);
                }
            }

            // ============================================
            // 8. THÊM MỚI KỸ NĂNG
            // ============================================
            if ($request->filled('new_skills')) {
                foreach ($request->new_skills as $skill) {
                    KyNangNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_ky_nang' => $skill['ten_ky_nang'] ?? null,
                        'cap_do' => $skill['cap_do'] ?? null,
                    ]);
                }
            }

            // ============================================
            // 9. XÓA CHỨNG CHỈ
            // ============================================
            $deleteCertificates = $parseDeleteIds($request->input('delete_certificates'));
            if (!empty($deleteCertificates)) {
                $deletedCount = ChungChiNhanVien::whereIn('id', $deleteCertificates)
                    ->where('ho_so_id', $hoSo->id)
                    ->delete();

                Log::info('Deleted certificates', [
                    'ids' => $deleteCertificates,
                    'user_id' => $user->id,
                    'deleted_count' => $deletedCount
                ]);
            }

            // ============================================
            // 10. CẬP NHẬT CHỨNG CHỈ CŨ
            // ============================================
            if ($request->filled('certificates')) {
                foreach ($request->certificates as $id => $cc) {
                    ChungChiNhanVien::where('id', $id)
                        ->where('ho_so_id', $hoSo->id)
                        ->update([
                            'ten_chung_chi' => $cc['ten_chung_chi'] ?? null,
                            'to_chuc_cap' => $cc['to_chuc_cap'] ?? null,
                            'nam_cap' => $cc['nam_cap'] ?? null,
                            'ngay_het_han' => $cc['ngay_het_han'] ?? null,
                        ]);
                }
            }

            // ============================================
            // 11. THÊM MỚI CHỨNG CHỈ
            // ============================================
            if ($request->filled('new_certificates')) {
                foreach ($request->new_certificates as $cc) {
                    ChungChiNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_chung_chi' => $cc['ten_chung_chi'] ?? null,
                        'to_chuc_cap' => $cc['to_chuc_cap'] ?? null,
                        'nam_cap' => $cc['nam_cap'] ?? null,
                        'ngay_het_han' => $cc['ngay_het_han'] ?? null,
                    ]);
                }
            }

            // ============================================
            // 12. XÓA ĐÀO TẠO
            // ============================================
            $deleteTrainings = $parseDeleteIds($request->input('delete_trainings'));
            if (!empty($deleteTrainings)) {
                $deletedCount = DaoTaoNhanVien::whereIn('id', $deleteTrainings)
                    ->where('ho_so_id', $hoSo->id)
                    ->delete();

                Log::info('Deleted trainings', [
                    'ids' => $deleteTrainings,
                    'user_id' => $user->id,
                    'deleted_count' => $deletedCount
                ]);
            }

            // ============================================
            // 13. CẬP NHẬT ĐÀO TẠO CŨ
            // ============================================
            if ($request->filled('trainings')) {
                foreach ($request->trainings as $id => $dt) {
                    DaoTaoNhanVien::where('id', $id)
                        ->where('ho_so_id', $hoSo->id)
                        ->update([
                            'ten_khoa_hoc' => $dt['ten_khoa_hoc'] ?? null,
                            'to_chuc' => $dt['to_chuc'] ?? null,
                            'ket_qua' => $dt['ket_qua'] ?? null,
                            'co_chung_chi' => isset($dt['co_chung_chi']) ? (bool)$dt['co_chung_chi'] : false,
                            'chi_phi' => !empty($dt['chi_phi']) ? $dt['chi_phi'] : null,
                            'ghi_chu' => $dt['ghi_chu'] ?? null,
                            'ngay_bat_dau' => !empty($dt['ngay_bat_dau']) ? $dt['ngay_bat_dau'] : null,
                            'ngay_ket_thuc' => !empty($dt['ngay_ket_thuc']) ? $dt['ngay_ket_thuc'] : null,
                        ]);
                }
            }

            // ============================================
            // 14. THÊM MỚI ĐÀO TẠO
            // ============================================
            if ($request->filled('new_trainings')) {
                foreach ($request->new_trainings as $dt) {
                    // Kiểm tra dữ liệu hợp lệ
                    if (empty($dt['ten_khoa_hoc'])) {
                        continue;
                    }

                    DaoTaoNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_khoa_hoc' => $dt['ten_khoa_hoc'] ?? null,
                        'to_chuc' => $dt['to_chuc'] ?? null,
                        'ket_qua' => $dt['ket_qua'] ?? null,
                        'co_chung_chi' => isset($dt['co_chung_chi']) ? (bool)$dt['co_chung_chi'] : false,
                        'chi_phi' => !empty($dt['chi_phi']) ? $dt['chi_phi'] : null,
                        'ghi_chu' => $dt['ghi_chu'] ?? null,
                        'ngay_bat_dau' => !empty($dt['ngay_bat_dau']) ? $dt['ngay_bat_dau'] : null,
                        'ngay_ket_thuc' => !empty($dt['ngay_ket_thuc']) ? $dt['ngay_ket_thuc'] : null,
                    ]);
                }
            }

            // ============================================
            // 15. XÓA NGƯỜI PHỤ THUỘC
            // ============================================
            $deleteDependents = $parseDeleteIds($request->input('delete_dependents'));
            if (!empty($deleteDependents)) {
                $deletedCount = NguoiPhuThuoc::whereIn('id', $deleteDependents)
                    ->where('ho_so_id', $hoSo->id)
                    ->delete();

                Log::info('Deleted dependents', [
                    'ids' => $deleteDependents,
                    'user_id' => $user->id,
                    'deleted_count' => $deletedCount
                ]);
            }

            // ============================================
            // 16. CẬP NHẬT NGƯỜI PHỤ THUỘC CŨ
            // ============================================
            if ($request->filled('dependents')) {
                foreach ($request->dependents as $id => $npt) {
                    $updateData = [
                        'ho_ten' => $npt['ho_ten'] ?? null,
                        'ngay_sinh' => !empty($npt['ngay_sinh']) ? $npt['ngay_sinh'] : null,
                        'quan_he' => $npt['quan_he'] ?? 'con',
                        'ma_so_thue' => $npt['ma_so_thue'] ?? null,
                        'ngay_bat_dau' => !empty($npt['ngay_bat_dau']) ? $npt['ngay_bat_dau'] : null,
                        'ngay_ket_thuc' => !empty($npt['ngay_ket_thuc']) ? $npt['ngay_ket_thuc'] : null,
                        'ghi_chu' => $npt['ghi_chu'] ?? null,
                    ];

                    Log::info('Updating dependent', [
                        'id' => $id,
                        'data' => $updateData
                    ]);

                    NguoiPhuThuoc::where('id', $id)
                        ->where('ho_so_id', $hoSo->id)
                        ->update($updateData);
                }
            }

            // ============================================
            // 17. THÊM MỚI NGƯỜI PHỤ THUỘC
            // ============================================
            if ($request->filled('new_dependents')) {
                Log::info('Adding new dependents', [
                    'count' => count($request->new_dependents),
                    'data' => $request->new_dependents
                ]);

                foreach ($request->new_dependents as $key => $npt) {
                    // Kiểm tra dữ liệu hợp lệ
                    if (empty($npt['ho_ten'])) {
                        Log::warning('Skipping dependent with empty ho_ten', ['key' => $key]);
                        continue;
                    }

                    NguoiPhuThuoc::create([
                        'ho_so_id' => $hoSo->id,
                        'ho_ten' => $npt['ho_ten'] ?? null,
                        'ngay_sinh' => !empty($npt['ngay_sinh']) ? $npt['ngay_sinh'] : null,
                        'quan_he' => $npt['quan_he'] ?? 'con',
                        'ma_so_thue' => $npt['ma_so_thue'] ?? null,
                        'ngay_bat_dau' => !empty($npt['ngay_bat_dau']) ? $npt['ngay_bat_dau'] : null,
                        'ngay_ket_thuc' => !empty($npt['ngay_ket_thuc']) ? $npt['ngay_ket_thuc'] : null,
                        'ghi_chu' => $npt['ghi_chu'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('employee.ho-so.index')
                ->with('success', 'Cập nhật hồ sơ thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating employee profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật hồ sơ: ' . $e->getMessage());
        }
    }

    /**
     * Xem CV của nhân viên
     */
    public function viewCv($id)
    {
        /** @var NguoiDung $user */
        $user = Auth::user();

        // Tìm tài liệu CV
        $cv = TaiLieu::where('id', $id)
            ->where('nguoi_dung_id', $user->id)
            ->first();

        if (!$cv) {
            abort(404, 'Không tìm thấy CV');
        }

        $filePath = storage_path('app/public/' . $cv->duong_dan_file);

        if (!file_exists($filePath)) {
            abort(404, 'File CV không tồn tại');
        }

        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $cv->ten_file_goc . '"',
        ]);
    }

    /**
     * Xem hợp đồng của nhân viên
     */
    public function viewContract($id)
    {
        /** @var NguoiDung $user */
        $user = Auth::user();

        // Tìm hợp đồng
        $contract = HopDongLaoDong::where('id', $id)
            ->where('nguoi_dung_id', $user->id)
            ->first();

        if (!$contract) {
            abort(404, 'Không tìm thấy hợp đồng');
        }

        $filePath = $contract->file_hop_dong_da_ky ?? $contract->duong_dan_file;

        if (!$filePath) {
            abort(404, 'File hợp đồng không tồn tại');
        }

        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            abort(404, 'File hợp đồng không tồn tại');
        }

        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\NguoiDung $user */
        $user = Auth::user();

        if (!$user || !$user->password) {
            return back()->withErrors([
                'current_password' => 'User hoặc password không tồn tại'
            ]);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không chính xác'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công');
    }
}

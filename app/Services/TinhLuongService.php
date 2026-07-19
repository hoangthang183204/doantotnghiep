<?php

namespace App\Services;

use App\Models\BangLuong;
use App\Models\ChamCong;
use App\Models\HopDongLaoDong;
use App\Models\HoSo;
use App\Models\KhauTruKhac;
use App\Models\KhauTruLuong;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Models\NguoiPhuThuoc;
use App\Models\PhuCapLuong;
use App\Models\PhuCapNhanVien;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Engine tính lương theo tháng.
 *
 * Luồng xử lý (đúng theo sơ đồ nghiệp vụ):
 *   Người dùng -> Chấm công -> Tổng hợp ngày công -> Lấy lương cơ bản
 *   -> Tính phụ cấp -> Tính tăng ca -> Tính tổng lương
 *   -> Tính lương thực nhận -> Lưu bảng lương
 */
class TinhLuongService
{
    /** Số ngày công chuẩn 1 tháng (quy ước VN phổ biến) */
    public const NGAY_CONG_CHUAN = 22;

    /** Hệ số lương tăng ca ngày thường */
    public const HE_SO_TANG_CA = 1.5;

    /**
     * Giảm trừ gia cảnh cho bản thân người nộp thuế (đ/tháng).
     * NQ 110/2025/UBTVQH15 + Luật Thuế TNCN 2025: 11tr → 15,5tr, áp dụng từ kỳ tính thuế 2026.
     */
    public const GIAM_TRU_BAN_THAN = 15_500_000;

    /**
     * Giảm trừ gia cảnh cho mỗi người phụ thuộc (đ/tháng).
     * NQ 110/2025/UBTVQH15: 4,4tr → 6,2tr, áp dụng từ kỳ tính thuế 2026.
     */
    public const GIAM_TRU_NGUOI_PHU_THUOC = 6_200_000;

    /** Các trạng thái chấm công được tính là "có đi làm" */
    private const TRANG_THAI_CO_MAT = ['dung_gio', 'di_muon', 've_som', 'den_som'];

    // =====================================================================
    // PUBLIC API
    // =====================================================================

    /**
     * Tính lương cho 1 nhân viên trong tháng (KHÔNG lưu DB).
     * Trả về toàn bộ chi tiết để hiển thị công thức.
     */
    public function tinhChoNhanVien(int $nguoiDungId, int $thang, int $nam): array
    {
        $nhanVien   = NguoiDung::with('chuc_vu')->findOrFail($nguoiDungId);
        $dauThang   = Carbon::create($nam, $thang, 1)->startOfMonth();
        $cuoiThang  = (clone $dauThang)->endOfMonth();

        // --- BƯỚC 1: Lấy lương cơ bản (từ hợp đồng hiệu lực) ---
        $hopDong    = $this->layHopDong($nguoiDungId, $dauThang, $cuoiThang);
        $luongCoBan = (float) ($hopDong->luong_co_ban ?? $nhanVien->chuc_vu->luong_co_ban ?? 0);

        // --- BƯỚC 2: Tổng hợp ngày công từ chấm công ---
        $cong               = $this->tongHopNgayCong($nguoiDungId, $thang, $nam);
        $soNgayCong         = $cong['so_ngay_cong'];
        $ngayNghiPhep       = $cong['ngay_nghi_phep'];
        $ngayNghiKhongPhep  = $cong['ngay_nghi_khong_phep'];
        $gioTangCa          = $cong['gio_tang_ca'];

        // --- BƯỚC 3: Quy lương cơ bản theo ngày công thực tế ---
        $ngayCongChuan = self::NGAY_CONG_CHUAN;
        $luongNgay     = $ngayCongChuan > 0 ? $luongCoBan / $ngayCongChuan : 0;
        $luongGio      = $luongNgay / 8;
        // Nghỉ phép vẫn được hưởng lương
        $ngayHuongLuong = $soNgayCong + $ngayNghiPhep;
        $luongTheoCong  = round($luongNgay * $ngayHuongLuong, 2);

        // --- BƯỚC 4: Tính phụ cấp ---
        $phuCap          = $this->tinhPhuCap($nguoiDungId, $luongCoBan, $hopDong, $dauThang, $cuoiThang);
        $tongPhuCap      = $phuCap['tong'];
        $phuCapChiuThue  = $phuCap['tong_chiu_thue'];
        $chiTietPhuCap   = $phuCap['chi_tiet'];

        // --- BƯỚC 5: Tính tăng ca (quy ra tiền) ---
        $tienTangCa = round($gioTangCa * $luongGio * self::HE_SO_TANG_CA, 2);
        $congTangCa = round($gioTangCa / 8, 2);

        // --- BƯỚC 6: Tổng lương (gross) ---
        $tongLuong = round($luongTheoCong + $tongPhuCap + $tienTangCa, 2);

        // --- BƯỚC 7: Khấu trừ (bảo hiểm bắt buộc + thuế TNCN) ---
        $bhxh = round($luongCoBan * 0.08, 2);
        $bhyt = round($luongCoBan * 0.015, 2);
        $bhtn = round($luongCoBan * 0.01, 2);
        $tongBaoHiem = round($bhxh + $bhyt + $bhtn, 2);

        // Giảm trừ gia cảnh = bản thân + (số người phụ thuộc × mức/người)
        // (NQ 110/2025/UBTVQH15, áp dụng từ kỳ tính thuế 2026)
        $soNguoiPhuThuoc = $this->demNguoiPhuThuoc($nguoiDungId, $dauThang, $cuoiThang);
        $giamTruNPT      = round($soNguoiPhuThuoc * self::GIAM_TRU_NGUOI_PHU_THUOC, 2);
        $giamTruGiaCanh  = round(self::GIAM_TRU_BAN_THAN + $giamTruNPT, 2);

        // Thu nhập chịu thuế = lương theo công + phụ cấp CHỊU THUẾ + tiền tăng ca
        $thuNhapChiuThue = round($luongTheoCong + $phuCapChiuThue + $tienTangCa, 2);
        // Thu nhập tính thuế = thu nhập chịu thuế − bảo hiểm bắt buộc − giảm trừ gia cảnh
        $thuNhapTinhThue = max(0, round($thuNhapChiuThue - $tongBaoHiem - $giamTruGiaCanh, 2));
        $thueTNCN        = $this->tinhThueTNCN($thuNhapTinhThue);

        $ghiChuThue = 'Thuế TNCN luỹ tiến — giảm trừ gia cảnh ' . number_format($giamTruGiaCanh) . 'đ'
            . ($soNguoiPhuThuoc > 0 ? " (bản thân + {$soNguoiPhuThuoc} người phụ thuộc)" : '');
        $chiTietKhauTru = [
            ['loai' => 'bhxh',      'so_tien' => $bhxh,     'ghi_chu' => 'BHXH 8% lương cơ bản'],
            ['loai' => 'bhyt',      'so_tien' => $bhyt,     'ghi_chu' => 'BHYT 1.5% lương cơ bản'],
            ['loai' => 'bhtn',      'so_tien' => $bhtn,     'ghi_chu' => 'BHTN 1% lương cơ bản'],
            ['loai' => 'thue_tncn', 'so_tien' => $thueTNCN, 'ghi_chu' => $ghiChuThue],
        ];

        // --- BƯỚC 7b: Khấu trừ khác (tạm ứng, phạt, bồi thường...) do admin nhập ---
        $khauTruKhac      = $this->layKhauTruKhac($nguoiDungId, $thang, $nam);
        $tongKhauTruKhac  = $khauTruKhac['tong'];
        foreach ($khauTruKhac['chi_tiet'] as $kt) {
            $chiTietKhauTru[] = [
                'loai'    => 'khau_tru_khac',
                'so_tien' => $kt['so_tien'],
                'ghi_chu' => $kt['ghi_chu'],
            ];
        }

        $tongKhauTru = round($tongBaoHiem + $thueTNCN + $tongKhauTruKhac, 2);

        // --- BƯỚC 8: Lương thực nhận (net) ---
        $luongThucNhan = round($tongLuong - $tongKhauTru, 2);

        return [
            'nguoi_dung_id'        => $nguoiDungId,
            'nguoi_dung'           => $nhanVien,
            'hop_dong'             => $hopDong,
            'luong_thang'          => $thang,
            'luong_nam'            => $nam,

            'luong_co_ban'         => $luongCoBan,
            'so_ngay_cong'         => $soNgayCong,
            'so_ngay_cong_chuan'   => $ngayCongChuan,
            'ngay_nghi_phep'       => $ngayNghiPhep,
            'ngay_nghi_khong_phep' => $ngayNghiKhongPhep,
            'luong_ngay'           => round($luongNgay, 2),
            'luong_gio'            => round($luongGio, 2),
            'luong_theo_cong'      => $luongTheoCong,

            'tong_phu_cap'         => $tongPhuCap,
            'phu_cap_chiu_thue'    => $phuCapChiuThue,
            'chi_tiet_phu_cap'     => $chiTietPhuCap,

            'gio_tang_ca'          => $gioTangCa,
            'cong_tang_ca'         => $congTangCa,
            'he_so_tang_ca'        => self::HE_SO_TANG_CA,
            'tien_tang_ca'         => $tienTangCa,

            'tong_luong'           => $tongLuong,

            'bhxh'                 => $bhxh,
            'bhyt'                 => $bhyt,
            'bhtn'                 => $bhtn,
            'tong_bao_hiem'        => $tongBaoHiem,
            'so_nguoi_phu_thuoc'   => $soNguoiPhuThuoc,
            'giam_tru_ban_than'    => self::GIAM_TRU_BAN_THAN,
            'giam_tru_nguoi_phu_thuoc' => $giamTruNPT,
            'giam_tru_gia_canh'    => $giamTruGiaCanh,
            'thu_nhap_chiu_thue'   => $thuNhapChiuThue,
            'thu_nhap_tinh_thue'   => $thuNhapTinhThue,
            'thue_thu_nhap_ca_nhan' => $thueTNCN,
            'tong_khau_tru_khac'   => $tongKhauTruKhac,
            'chi_tiet_khau_tru_khac' => $khauTruKhac['chi_tiet'],
            'tong_khau_tru'        => $tongKhauTru,
            'chi_tiet_khau_tru'    => $chiTietKhauTru,

            'luong_thuc_nhan'      => $luongThucNhan,
        ];
    }

    /**
     * Tính & LƯU lương cho 1 nhân viên vào 1 bảng lương.
     * Idempotent: nếu đã có dòng cho nhân viên này trong bảng -> tính lại (ghi đè).
     */
    public function luuLuongNhanVien(int $bangLuongId, int $nguoiDungId, int $thang, int $nam): LuongNhanVien
    {
        $kq = $this->tinhChoNhanVien($nguoiDungId, $thang, $nam);

        return DB::transaction(function () use ($kq, $bangLuongId, $nguoiDungId, $thang, $nam) {
            // Xoá dòng cũ (nếu tính lại) kèm chi tiết khấu trừ (không có FK cascade)
            $cu = LuongNhanVien::where('bang_luong_id', $bangLuongId)
                ->where('nguoi_dung_id', $nguoiDungId)
                ->first();
            if ($cu) {
                KhauTruLuong::where('luong_nhan_vien_id', $cu->id)->delete();
                $cu->delete(); // phu_cap_luong cascade theo FK
            }

            $lnv = LuongNhanVien::create([
                'bang_luong_id'         => $bangLuongId,
                'luong_thang'           => $thang,
                'luong_nam'             => $nam,
                'nguoi_dung_id'         => $nguoiDungId,
                'luong_co_ban'          => $kq['luong_co_ban'],
                'luong_theo_cong'       => $kq['luong_theo_cong'],
                'tong_phu_cap'          => $kq['tong_phu_cap'],
                'tien_tang_ca'          => $kq['tien_tang_ca'],
                'tong_khau_tru'         => $kq['tong_khau_tru'],
                'tong_luong'            => $kq['tong_luong'],
                'luong_thuc_nhan'       => $kq['luong_thuc_nhan'],
                'so_ngay_cong'          => $kq['so_ngay_cong'],
                'so_ngay_cong_chuan'    => $kq['so_ngay_cong_chuan'],
                'gio_tang_ca'           => $kq['gio_tang_ca'],
                'cong_tang_ca'          => $kq['cong_tang_ca'],
                'ngay_nghi_phep'        => $kq['ngay_nghi_phep'],
                'ngay_nghi_khong_phep'  => $kq['ngay_nghi_khong_phep'],
                'ngay_le'               => 0,
                'thue_thu_nhap_ca_nhan' => $kq['thue_thu_nhap_ca_nhan'],
            ]);

            // Lưu chi tiết phụ cấp
            foreach ($kq['chi_tiet_phu_cap'] as $pc) {
                PhuCapLuong::create([
                    'luong_nhan_vien_id' => $lnv->id,
                    'phu_cap_id'         => $pc['phu_cap_id'],
                    'so_tien'            => $pc['so_tien'],
                    'ghi_chu'            => $pc['ten'] ?? null,
                ]);
            }

            // Lưu chi tiết khấu trừ (chỉ lưu khoản > 0)
            foreach ($kq['chi_tiet_khau_tru'] as $kt) {
                if ($kt['so_tien'] > 0) {
                    KhauTruLuong::create([
                        'luong_nhan_vien_id' => $lnv->id,
                        'loai_khau_tru'      => $kt['loai'],
                        'so_tien'            => $kt['so_tien'],
                        'ghi_chu'            => $kt['ghi_chu'] ?? null,
                    ]);
                }
            }

            return $lnv;
        });
    }

    /**
     * Tạo (hoặc dùng lại) 1 bảng lương tháng và tính lương cho danh sách nhân viên.
     */
    public function taoBangLuong(
        int $thang,
        int $nam,
        array $nhanVienIds,
        ?int $nguoiXuLyId = null,
        string $trangThai = 'dang_xu_ly'
    ): BangLuong {
        $bangLuong = BangLuong::firstOrCreate(
            ['thang' => $thang, 'nam' => $nam, 'loai_bang_luong' => 'hang_thang'],
            [
                'ma_bang_luong' => $this->taoMaBangLuong($thang, $nam),
                'trang_thai'    => $trangThai,
                'nguoi_xu_ly_id' => $nguoiXuLyId,
                'thoi_gian_xu_ly' => now(),
            ]
        );

        foreach ($nhanVienIds as $id) {
            $this->luuLuongNhanVien($bangLuong->id, (int) $id, $thang, $nam);
        }

        return $bangLuong->fresh('luongNhanViens');
    }

    /**
     * Chốt lương 1 tháng cho TẤT CẢ nhân viên đang làm việc.
     * Dùng cho lệnh tự động chạy ngày 1 hàng tháng.
     */
    public function chotThang(int $thang, int $nam, ?int $nguoiXuLyId = null): BangLuong
    {
        $nhanVienIds = NguoiDung::where('trang_thai', 1)->pluck('id')->all();

        $bangLuong = $this->taoBangLuong($thang, $nam, $nhanVienIds, $nguoiXuLyId, 'da_chot');

        $bangLuong->update([
            'trang_thai'          => 'da_chot',
            'nguoi_phe_duyet_id'  => $nguoiXuLyId,
            'thoi_gian_phe_duyet' => now(),
        ]);

        return $bangLuong;
    }

    // =====================================================================
    // INTERNAL
    // =====================================================================

    /** Lấy hợp đồng có hiệu lực trong tháng */
    private function layHopDong(int $nguoiDungId, Carbon $dauThang, Carbon $cuoiThang): ?HopDongLaoDong
    {
        return HopDongLaoDong::with('phuCap')
            ->where('nguoi_dung_id', $nguoiDungId)
            ->where('ngay_bat_dau', '<=', $cuoiThang->toDateString())
            ->where(function ($q) use ($dauThang) {
                $q->whereNull('ngay_ket_thuc')
                    ->orWhere('ngay_ket_thuc', '>=', $dauThang->toDateString());
            })
            ->orderByDesc('ngay_bat_dau')
            ->first();
    }

    /** Tổng hợp ngày công từ bảng chấm công đã duyệt */
    private function tongHopNgayCong(int $nguoiDungId, int $thang, int $nam): array
    {
        $chamCongs = ChamCong::where('nguoi_dung_id', $nguoiDungId)
            ->whereYear('ngay_cham_cong', $nam)
            ->whereMonth('ngay_cham_cong', $thang)
            // ✅ Chỉ lấy những ngày có giờ vào và giờ ra
            ->whereNotNull('gio_vao')
            ->whereNotNull('gio_ra')
            ->get();

        return [
            'so_ngay_cong'         => $chamCongs->count(),  // ✅ Đếm tất cả
            'ngay_nghi_phep'       => 0,
            'ngay_nghi_khong_phep' => 0,
            'gio_tang_ca'          => round((float) $chamCongs->sum('gio_tang_ca'), 2),
        ];
    }

    /**
     * Đếm số người phụ thuộc còn hiệu lực trong tháng để tính giảm trừ gia cảnh.
     * Người phụ thuộc gắn với hồ sơ (ho_so_nguoi_dung) qua ho_so_id.
     * Chỉ tính người có ngày bắt đầu ≤ cuối tháng và (chưa kết thúc HOẶC kết thúc ≥ đầu tháng).
     */
    private function demNguoiPhuThuoc(int $nguoiDungId, Carbon $dauThang, Carbon $cuoiThang): int
    {
        $hoSoId = HoSo::where('nguoi_dung_id', $nguoiDungId)->value('id');
        if (!$hoSoId) {
            return 0;
        }

        return (int) NguoiPhuThuoc::where('ho_so_id', $hoSoId)
            // Đã bắt đầu tính giảm trừ trước/trong tháng (NULL = tính từ đầu)
            ->where(function ($q) use ($cuoiThang) {
                $q->whereNull('ngay_bat_dau')
                    ->orWhereDate('ngay_bat_dau', '<=', $cuoiThang->toDateString());
            })
            // Chưa kết thúc hoặc kết thúc sau đầu tháng
            ->where(function ($q) use ($dauThang) {
                $q->whereNull('ngay_ket_thuc')
                    ->orWhereDate('ngay_ket_thuc', '>=', $dauThang->toDateString());
            })
            ->count();
    }

    /**
     * Tính phụ cấp của nhân viên trong tháng.
     * Gộp phụ cấp gán riêng (phu_cap_nhan_vien) + phụ cấp gắn ở hợp đồng.
     */
    private function tinhPhuCap(
        int $nguoiDungId,
        float $luongCoBan,
        ?HopDongLaoDong $hopDong,
        Carbon $dauThang,
        Carbon $cuoiThang
    ): array {
        $chiTiet = [];
        $daCo    = [];

        $pcnvs = PhuCapNhanVien::with('phuCap')
            ->where('nguoi_dung_id', $nguoiDungId)
            ->where('trang_thai', 'hieu_luc')
            ->where('ngay_hieu_luc', '<=', $cuoiThang->toDateString())
            ->where(function ($q) use ($dauThang) {
                $q->whereNull('ngay_ket_thuc')
                    ->orWhere('ngay_ket_thuc', '>=', $dauThang->toDateString());
            })
            ->get();

        foreach ($pcnvs as $pc) {
            $def    = $pc->phuCap;
            $soTien = $this->quyDoiPhuCap($def, (float) $pc->so_tien, $luongCoBan);
            $chiTiet[] = [
                'phu_cap_id' => $pc->phu_cap_id,
                'ten'        => $def->ten ?? 'Phụ cấp',
                'so_tien'    => $soTien,
                'chiu_thue'  => $def ? (bool) $def->chiu_thue : true,
            ];
            $daCo[$pc->phu_cap_id] = true;
        }

        // Phụ cấp gắn trực tiếp trên hợp đồng (nếu chưa có ở trên)
        if ($hopDong && $hopDong->phu_cap_id && $hopDong->phuCap && empty($daCo[$hopDong->phu_cap_id])) {
            $def = $hopDong->phuCap;
            $chiTiet[] = [
                'phu_cap_id' => $hopDong->phu_cap_id,
                'ten'        => $def->ten,
                'so_tien'    => $this->quyDoiPhuCap($def, (float) $def->so_tien_mac_dinh, $luongCoBan),
                'chiu_thue'  => (bool) $def->chiu_thue,
            ];
        }

        $tong         = round(array_sum(array_column($chiTiet, 'so_tien')), 2);
        $tongChiuThue = round(array_sum(array_map(
            fn($i) => $i['chiu_thue'] ? $i['so_tien'] : 0,
            $chiTiet
        )), 2);

        return ['tong' => $tong, 'tong_chiu_thue' => $tongChiuThue, 'chi_tiet' => $chiTiet];
    }

    /**
     * Lấy & tổng hợp các khoản khấu trừ khác (tạm ứng, phạt...) trong tháng.
     * Chỉ tính các khoản còn hiệu lực.
     */
    private function layKhauTruKhac(int $nguoiDungId, int $thang, int $nam): array
    {
        $khoan = KhauTruKhac::where('nguoi_dung_id', $nguoiDungId)
            ->where('thang', $thang)
            ->where('nam', $nam)
            ->where('trang_thai', 'hieu_luc')
            ->get();

        $chiTiet = [];
        foreach ($khoan as $k) {
            $nhan = KhauTruKhac::$loaiLabels[$k->loai] ?? 'Khấu trừ khác';
            $chiTiet[] = [
                'so_tien' => (float) $k->so_tien,
                'ghi_chu' => trim($nhan . ($k->ly_do ? ': ' . $k->ly_do : '')),
            ];
        }

        return [
            'tong'     => round(array_sum(array_column($chiTiet, 'so_tien')), 2),
            'chi_tiet' => $chiTiet,
        ];
    }

    /** Quy đổi giá trị phụ cấp ra tiền (hỗ trợ % lương cơ bản) */
    private function quyDoiPhuCap($def, float $giaTri, float $luongCoBan): float
    {
        if ($def && $def->cach_tinh === 'phan_tram_luong_cb') {
            return round($luongCoBan * $giaTri / 100, 2);
        }
        return round($giaTri, 2);
    }

    /**
     * Thuế TNCN luỹ tiến từng phần (phương pháp rút gọn) — biểu 5 BẬC.
     * Luật Thuế TNCN 2025: rút gọn 7 bậc → 5 bậc, áp dụng từ kỳ tính thuế 2026.
     * Bậc: đến 10tr (5%), 10–30tr (10%), 30–60tr (20%), 60–100tr (30%), trên 100tr (35%).
     */
private function tinhThueTNCN(float $thuNhapTinhThue): float
{
    if ($thuNhapTinhThue <= 0) {
        return 0;
    }

    $thue = 0;

    // Bậc 1
    if ($thuNhapTinhThue <= 10_000_000) {
        return round($thuNhapTinhThue * 0.05, 2);
    }

    $thue += 10_000_000 * 0.05;

    // Bậc 2
    if ($thuNhapTinhThue <= 30_000_000) {
        $thue += ($thuNhapTinhThue - 10_000_000) * 0.10;
        return round($thue, 2);
    }

    $thue += 20_000_000 * 0.10;

    // Bậc 3
    if ($thuNhapTinhThue <= 60_000_000) {
        $thue += ($thuNhapTinhThue - 30_000_000) * 0.20;
        return round($thue, 2);
    }

    $thue += 30_000_000 * 0.20;

    // Bậc 4
    if ($thuNhapTinhThue <= 100_000_000) {
        $thue += ($thuNhapTinhThue - 60_000_000) * 0.30;
        return round($thue, 2);
    }

    $thue += 40_000_000 * 0.30;

    // Bậc 5
    $thue += ($thuNhapTinhThue - 100_000_000) * 0.35;

    return round($thue, 2);
}

    /** Sinh mã bảng lương duy nhất: BL-YYYY-MM-xxxxx */
    private function taoMaBangLuong(int $thang, int $nam): string
    {
        return sprintf('BL-%04d-%02d-%s', $nam, $thang, strtoupper(substr(uniqid(), -5)));
    }
}

<?php

namespace App\Services;

use App\Models\ChamCong;
use App\Models\CaLamViec;
use Carbon\Carbon;

class ChamCongService
{
    protected $caSang;
    protected $caChieu;
    protected $caHanhChinh;

    public function __construct()
    {
        $this->caSang = CaLamViec::where('ma', 'SANG')->first();
        $this->caChieu = CaLamViec::where('ma', 'CHIEU')->first();
        $this->caHanhChinh = CaLamViec::where('ma', 'HANH_CHINH')->first();
    }

    /**
     * Xác định ca hiện tại dựa vào giờ
     */
    public function xacDinhCaHienTai()
    {
        $now = Carbon::now();
        $gio = (int) $now->format('H');
        $phut = (int) $now->format('i');

        // Ca sáng: 07:00 - 12:30
        if (($gio >= 7 && $gio < 12) || ($gio == 12 && $phut <= 30)) {
            return $this->caSang;
        }

        // Ca chiều: 13:00 - 17:30
        if ($gio >= 13 && $gio < 17 || ($gio == 17 && $phut <= 30)) {
            return $this->caChieu;
        }

        // Hành chính: 08:30 - 17:30 (không phân ca)
        if ($gio >= 8 && $gio < 17) {
            return $this->caHanhChinh;
        }

        return null;
    }

    /**
     * Xác định ca cần check-in dựa vào giờ hiện tại
     */
    public function xacDinhCaCheckIn()
    {
        $now = Carbon::now();
        $gio = (int) $now->format('H');

        // 07:00 - 12:00 → check-in ca sáng
        if ($gio >= 7 && $gio < 12) {
            return $this->caSang;
        }

        // 13:00 - 15:00 → check-in ca chiều
        if ($gio >= 13 && $gio < 15) {
            return $this->caChieu;
        }

        // Check-in muộn ca sáng (12:00 - 13:00) → vẫn là ca sáng
        if ($gio >= 12 && $gio < 13) {
            return $this->caSang;
        }

        // Check-in muộn ca chiều (15:00 - 16:00) → vẫn là ca chiều
        if ($gio >= 15 && $gio < 16) {
            return $this->caChieu;
        }

        return null;
    }

    /**
     * Lấy trạng thái chấm công hiện tại của nhân viên
     */
    public function trangThaiHomNay($nguoiDungId)
    {
        $today = Carbon::today();

        // Lấy tất cả bản ghi trong ngày
        $records = ChamCong::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', $today)
            ->get();

        // ⭐ Kiểm tra từng ca
        $caSangCheckIn = $records->where('ca_lam_viec_id', $this->caSang->id)
            ->where('loai_cham_cong', 'check_in')
            ->first();

        $caSangCheckOut = $records->where('ca_lam_viec_id', $this->caSang->id)
            ->where('loai_cham_cong', 'check_out')
            ->first();

        $caChieuCheckIn = $records->where('ca_lam_viec_id', $this->caChieu->id)
            ->where('loai_cham_cong', 'check_in')
            ->first();

        $caChieuCheckOut = $records->where('ca_lam_viec_id', $this->caChieu->id)
            ->where('loai_cham_cong', 'check_out')
            ->first();

        // Xác định ca hiện tại
        $caHienTai = $this->xacDinhCaCheckIn();

        return [
            // Thông tin ca sáng
            'sang' => [
                'da_check_in' => (bool) $caSangCheckIn,
                'da_check_out' => (bool) $caSangCheckOut,
                'da_hoan_thanh' => (bool) $caSangCheckIn && (bool) $caSangCheckOut,
                'check_in_time' => $caSangCheckIn ? $caSangCheckIn->gio_vao : null,
                'check_out_time' => $caSangCheckOut ? $caSangCheckOut->gio_ra : null,
                'trang_thai' => $caSangCheckIn ? $caSangCheckIn->trang_thai : null,
                'ly_do_ve_som' => $caSangCheckOut ? $caSangCheckOut->ly_do_ve_som : null,
            ],
            // Thông tin ca chiều
            'chieu' => [
                'da_check_in' => (bool) $caChieuCheckIn,
                'da_check_out' => (bool) $caChieuCheckOut,
                'da_hoan_thanh' => (bool) $caChieuCheckIn && (bool) $caChieuCheckOut,
                'check_in_time' => $caChieuCheckIn ? $caChieuCheckIn->gio_vao : null,
                'check_out_time' => $caChieuCheckOut ? $caChieuCheckOut->gio_ra : null,
                'trang_thai' => $caChieuCheckIn ? $caChieuCheckIn->trang_thai : null,
                'ly_do_ve_som' => $caChieuCheckOut ? $caChieuCheckOut->ly_do_ve_som : null,
            ],
            // Tổng hợp
            'tong_cong' => $records->where('loai_cham_cong', 'check_in')->sum('so_cong'),
            'tong_gio_lam' => $records->where('loai_cham_cong', 'check_in')->sum('so_gio_lam'),
            'da_check_in' => $records->where('loai_cham_cong', 'check_in')->isNotEmpty(),
            'da_check_out' => $records->where('loai_cham_cong', 'check_out')->isNotEmpty(),
            'ca_hien_tai' => $caHienTai ? $caHienTai->ten : null,
            'ca_hien_tai_id' => $caHienTai ? $caHienTai->id : null,
        ];
    }

    /**
     * Xử lý check-in cho ca sáng/chiều
     */
    public function checkIn($nguoiDungId, $data)
    {
        // Xác định ca cần check-in
        $ca = $this->xacDinhCaCheckIn();

        if (!$ca) {
            return [
                'status' => 'error',
                'message' => 'Không phải giờ chấm công! Vui lòng chấm công trong giờ hành chính.'
            ];
        }

        // Kiểm tra đã check-in ca này chưa
        $existing = ChamCong::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today())
            ->where('ca_lam_viec_id', $ca->id)
            ->where('loai_cham_cong', 'check_in')
            ->first();

        if ($existing) {
            return [
                'status' => 'error',
                'message' => "Bạn đã check-in ca {$ca->ten} rồi!"
            ];
        }

        // Cho phép check-in ca chiều dù chưa check-in ca sáng
        // (Nhân viên có thể chỉ đi làm ca chiều)

        $now = Carbon::now();

        // Tạo bản ghi check-in
        $chamCong = new ChamCong();
        $chamCong->nguoi_dung_id = $nguoiDungId;
        $chamCong->ngay_cham_cong = Carbon::today();
        $chamCong->ca_lam_viec_id = $ca->id;
        $chamCong->loai_cham_cong = 'check_in';
        $chamCong->gio_vao = $now->format('H:i:s');
        $chamCong->dia_chi_ip = $data['ip'] ?? null;
        $chamCong->ten_wifi = $data['wifi'] ?? null;
        $chamCong->ten_thiet_bi = $data['device'] ?? null;
        $chamCong->phuong_thuc_cham_cong = $data['method'] ?? 'manual';

        // Tính trạng thái check-in
        $this->tinhTrangThaiCheckIn($chamCong, $ca);

        $chamCong->save();

        return [
            'status' => 'success',
            'message' => "✅ Check-in ca {$ca->ten} thành công!",
            'ca' => $ca->ten,
            'ca_id' => $ca->id,
            'gio_vao' => $now->format('H:i:s')
        ];
    }

    /**
     * Xử lý check-out cho ca sáng/chiều
     */
    public function checkOut($nguoiDungId, $data)
    {
        // Xác định ca hiện tại để check-out
        $ca = $this->xacDinhCaCheckIn();

        if (!$ca) {
            return [
                'status' => 'error',
                'message' => 'Không phải giờ check-out!'
            ];
        }

        // Tìm bản ghi check-in của ca này
        $checkIn = ChamCong::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today())
            ->where('ca_lam_viec_id', $ca->id)
            ->where('loai_cham_cong', 'check_in')
            ->first();

        if (!$checkIn) {
            return [
                'status' => 'error',
                'message' => "Bạn chưa check-in ca {$ca->ten}! Vui lòng check-in trước."
            ];
        }

        // Kiểm tra đã check-out chưa
        $checkOut = ChamCong::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today())
            ->where('ca_lam_viec_id', $ca->id)
            ->where('loai_cham_cong', 'check_out')
            ->first();

        if ($checkOut) {
            return [
                'status' => 'error',
                'message' => "Bạn đã check-out ca {$ca->ten} rồi!"
            ];
        }

        $now = Carbon::now();
        $gioKetThucCa = Carbon::parse($ca->gio_ket_thuc);

        // Kiểm tra về sớm
        $isVeSom = $now->lt($gioKetThucCa);
        $phutVeSom = $isVeSom ? $now->diffInMinutes($gioKetThucCa) : 0;

        // ⭐ Nếu về sớm, bắt buộc nhập lý do
        if ($isVeSom && empty($data['ly_do_ve_som'])) {
            return [
                'status' => 'error',
                'message' => 'Bạn đang về sớm, vui lòng nhập lý do!',
                'yeu_cau_ly_do' => true,
                'ca' => $ca->ten,
                'phut_ve_som' => $phutVeSom
            ];
        }

        // Tạo bản ghi check-out
        $chamCong = new ChamCong();
        $chamCong->nguoi_dung_id = $nguoiDungId;
        $chamCong->ngay_cham_cong = Carbon::today();
        $chamCong->ca_lam_viec_id = $ca->id;
        $chamCong->loai_cham_cong = 'check_out';
        $chamCong->gio_ra = $now->format('H:i:s');
        $chamCong->ly_do_ve_som = $data['ly_do_ve_som'] ?? null;
        $chamCong->da_xac_nhan_ve_som = $isVeSom && !empty($data['ly_do_ve_som']);
        $chamCong->dia_chi_ip = $data['ip'] ?? null;
        $chamCong->ten_wifi = $data['wifi'] ?? null;
        $chamCong->ten_thiet_bi = $data['device'] ?? null;
        $chamCong->phuong_thuc_cham_cong = $data['method'] ?? 'manual';

        // Tính toán số giờ làm, số công
        $this->tinhToanCheckOut($chamCong, $checkIn, $ca);

        $chamCong->save();

        // Cập nhật giờ ra cho bản ghi check-in
        $checkIn->gio_ra = $chamCong->gio_ra;
        $checkIn->so_gio_lam = $chamCong->so_gio_lam;
        $checkIn->so_cong = $chamCong->so_cong;
        $checkIn->phut_ve_som = $chamCong->phut_ve_som;
        $checkIn->trang_thai = $chamCong->trang_thai;
        $checkIn->ghi_chu = $chamCong->ly_do_ve_som;
        $checkIn->save();

        return [
            'status' => 'success',
            'message' => "✅ Check-out ca {$ca->ten} thành công!" . 
                         ($isVeSom ? " Bạn về sớm {$phutVeSom} phút." : ""),
            'ca' => $ca->ten,
            'ca_id' => $ca->id,
            'gio_ra' => $now->format('H:i:s'),
            'is_ve_som' => $isVeSom,
            'phut_ve_som' => $phutVeSom
        ];
    }

    /**
     * Tính trạng thái check-in
     */
    private function tinhTrangThaiCheckIn($chamCong, $ca)
    {
        $gioVao = Carbon::parse($chamCong->gio_vao);
        $gioBatDau = Carbon::parse($ca->gio_bat_dau);

        $phutDiMuon = 0;
        if ($gioVao->gt($gioBatDau)) {
            $phutDiMuon = $gioVao->diffInMinutes($gioBatDau);
        }

        $chamCong->phut_di_muon = $phutDiMuon;

        if ($phutDiMuon > ($ca->so_phut_cho_phep_di_tre ?? 15)) {
            $chamCong->trang_thai = 'di_muon';
        } elseif ($gioVao->lt($gioBatDau)) {
            $chamCong->trang_thai = 'den_som';
        } else {
            $chamCong->trang_thai = 'dung_gio';
        }
    }

    /**
     * Tính toán khi check-out
     */
    private function tinhToanCheckOut($chamCong, $checkIn, $ca)
    {
        $gioVao = Carbon::parse($checkIn->gio_vao);
        $gioRa = Carbon::parse($chamCong->gio_ra);
        $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);

        // Số phút làm việc
        $soPhutLam = $gioVao->diffInMinutes($gioRa);
        $chamCong->so_gio_lam = number_format($soPhutLam / 60, 2);

        // Số công (dựa trên số giờ tiêu chuẩn của ca)
        $soGioTieuChuan = (float) ($ca->so_gio_lam_viec ?? 4);
        $chamCong->so_cong = round(($soPhutLam / 60 / $soGioTieuChuan) * 2) / 2;

        // Giới hạn tối đa 0.5 công cho mỗi ca
        if ($chamCong->so_cong > 0.5) {
            $chamCong->so_cong = 0.5;
        }

        // Phút về sớm
        $phutVeSom = 0;
        if ($gioRa->lt($gioKetThuc)) {
            $phutVeSom = $gioRa->diffInMinutes($gioKetThuc);
        }
        $chamCong->phut_ve_som = $phutVeSom;

        // Cập nhật trạng thái
        $choPhepVeSom = $ca->so_phut_cho_phep_ve_som ?? 15;
        if ($phutVeSom > $choPhepVeSom) {
            $chamCong->trang_thai = 've_som';
        } elseif ($chamCong->trang_thai == 'di_muon' && $phutVeSom <= $choPhepVeSom) {
            // Giữ nguyên trạng thái đi muộn
        } elseif ($chamCong->trang_thai == 'den_som') {
            $chamCong->trang_thai = 'dung_gio';
        }
    }

    /**
     * Lấy tổng công trong ngày
     */
    public function tongCongTrongNgay($nguoiDungId, $ngay = null)
    {
        $ngay = $ngay ? Carbon::parse($ngay) : Carbon::today();

        $records = ChamCong::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', $ngay)
            ->where('loai_cham_cong', 'check_in')
            ->get();

        $tongCong = $records->sum('so_cong');
        $tongGio = $records->sum('so_gio_lam');

        // Kiểm tra ca sáng + chiều
        $caSang = $records->where('ca_lam_viec_id', $this->caSang->id)->first();
        $caChieu = $records->where('ca_lam_viec_id', $this->caChieu->id)->first();

        return [
            'tong_cong' => $tongCong,
            'tong_gio_lam' => $tongGio,
            'ca_sang' => $caSang ? [
                'so_cong' => $caSang->so_cong,
                'so_gio' => $caSang->so_gio_lam,
                'trang_thai' => $caSang->trang_thai
            ] : null,
            'ca_chieu' => $caChieu ? [
                'so_cong' => $caChieu->so_cong,
                'so_gio' => $caChieu->so_gio_lam,
                'trang_thai' => $caChieu->trang_thai
            ] : null,
        ];
    }
}
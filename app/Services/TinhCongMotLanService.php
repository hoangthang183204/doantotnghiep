<?php

namespace App\Services;

use App\Models\ChamCong;
use App\Models\DangKyTangCa;
use App\Models\ThucHienTangCa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * Mô hình 2: một lần check-in và một lần check-out.
 * Nghỉ trưa không chấm. Giờ sau giờ ra chuẩn chỉ tính tăng ca khi có đơn đã duyệt.
 */
class TinhCongMotLanService
{
    public function mocKetThuc(Carbon $ngay): Carbon
    {
        $lich = LichLamViecService::cauHinh();

        return Carbon::parse($ngay->toDateString() . ' ' . ($lich->gio_ra ?: '17:30'), 'Asia/Ho_Chi_Minh');
    }

    /**
     * @return array{ve_som: bool, phut: int, gio_ket_thuc: string}
     */
    public function kiemTraVeSom(Carbon $gioRa, Carbon $ngay, int $choPhepPhut = 15): array
    {
        $moc = $this->mocKetThuc($ngay);
        $phut = max(0, (int) round(($moc->getTimestamp() - $gioRa->getTimestamp()) / 60));
        $veSom = $phut > $choPhepPhut;

        return [
            've_som' => $veSom,
            'phut' => $veSom ? $phut : 0,
            'gio_ket_thuc' => $moc->format('H:i'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function tinh(Carbon $gioVao, Carbon $gioRa, int $nguoiDungId): array
    {
        $lich = LichLamViecService::cauHinh();
        $ngay = $gioVao->copy()->timezone('Asia/Ho_Chi_Minh')->startOfDay();

        $vao = $this->moc($ngay, $gioVao);
        $ra = $this->moc($ngay, $gioRa);
        if ($ra->lessThanOrEqualTo($vao)) {
            $ra->addDay();
        }

        $batDauCa = $this->mocGio($ngay, $lich->gio_vao ?: '08:00');
        $ketThucCa = $this->mocGio($ngay, $lich->gio_ra ?: '17:30');
        if ($ketThucCa->lessThanOrEqualTo($batDauCa)) {
            $ketThucCa->addDay();
        }

        $nghiTu = $this->mocGio($ngay, $lich->gio_nghi_trua_bat_dau ?: '12:00');
        $nghiDen = $this->mocGio($ngay, $lich->gio_nghi_trua_ket_thuc ?: '13:30');

        $phutCoMat = $this->phut($vao, $ra);
        $phutNghi = $this->phutGiao($vao, $ra, $nghiTu, $nghiDen);
        $phutCaChuan = max(1, $this->phut($batDauCa, $ketThucCa) - $this->phutGiao($batDauCa, $ketThucCa, $nghiTu, $nghiDen));

        $mocChia = $ra->lessThan($ketThucCa) ? $ra->copy() : $ketThucCa->copy();
        $phutCaChinh = max(0, $this->phut($vao, $mocChia) - $this->phutGiao($vao, $mocChia, $nghiTu, $nghiDen));
        $phutSauGio = max(0, $this->phut($ketThucCa, $ra));

        $don = DangKyTangCa::with('thuc_hien')
            ->where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_tang_ca', $ngay->toDateString())
            ->where('trang_thai', 'da_duyet')
            ->orderBy('gio_bat_dau')
            ->get();

        $phutOtDuyet = (int) round($don->sum(fn ($d) => (float) $d->so_gio_tang_ca) * 60);
        $phutOt = min($phutSauGio, $phutOtDuyet);
        $phutOLai = max(0, $phutSauGio - $phutOt);

        $this->capNhatDon($don, $phutOt, $ra, $ketThucCa);

        $gioCaChinh = round($phutCaChinh / 60, 2);
        $gioTangCa = round($phutOt / 60, 2);
        $gioOLai = round($phutOLai / 60, 2);
        $gioNghi = round($phutNghi / 60, 2);
        $gioCoMat = round($phutCoMat / 60, 2);
        $gioThucTe = round(($phutCoMat - $phutNghi) / 60, 2);
        $soCong = round(min(1, $phutCaChinh / $phutCaChuan), 2);

        $ghiChu = sprintf(
            'Có mặt %s, trừ nghỉ trưa %s, giờ công thực tế %s. Ca chính %s.',
            $this->dinhDang($gioCoMat),
            $this->dinhDang($gioNghi),
            $this->dinhDang($gioThucTe),
            $this->dinhDang($gioCaChinh)
        );
        if ($gioTangCa > 0) {
            $ghiChu .= ' Tăng ca đã duyệt ' . $this->dinhDang($gioTangCa) . '.';
        }
        if ($gioOLai > 0) {
            $ghiChu .= ' Ở lại sau giờ ' . $this->dinhDang($gioOLai) . ' — không có đơn tăng ca, không tính lương.';
        }

        return [
            'so_gio_lam' => round($gioCaChinh + $gioTangCa, 2),
            'so_cong' => $soCong,
            'so_gio_ca_chinh' => $gioCaChinh,
            'so_gio_tang_ca' => $gioTangCa,
            'so_gio_nghi_trua' => $gioNghi,
            'so_gio_o_lai' => $gioOLai,
            'gio_co_mat' => $gioCoMat,
            'co_tang_ca' => $gioTangCa > 0,
            'gio_tang_ca' => $gioTangCa,
            'tang_ca_id' => $don->first()?->id,
            'ghi_chu' => $ghiChu,
            'thong_bao' => $ghiChu,
        ];
    }

    public function apDung(ChamCong $chamCong, Carbon $gioVao, Carbon $gioRa, int $nguoiDungId): array
    {
        $ketQua = $this->tinh($gioVao, $gioRa, $nguoiDungId);
        $chamCong->so_gio_lam = $ketQua['so_gio_lam'];
        $chamCong->so_cong = $ketQua['so_cong'];
        $chamCong->so_gio_ca_chinh = $ketQua['so_gio_ca_chinh'];
        $chamCong->so_gio_tang_ca = $ketQua['so_gio_tang_ca'];
        $chamCong->so_gio_nghi_trua = $ketQua['so_gio_nghi_trua'];
        $chamCong->co_tang_ca = $ketQua['co_tang_ca'];
        $chamCong->gio_tang_ca = $ketQua['gio_tang_ca'];
        $chamCong->tang_ca_id = $ketQua['tang_ca_id'];
        $chamCong->ghi_chu = $ketQua['ghi_chu'];

        return $ketQua;
    }

    private function capNhatDon($don, int $phutOt, Carbon $gioRa, Carbon $ketThucCa): void
    {
        $conLai = $phutOt / 60;
        foreach ($don as $item) {
            $phan = round(min($conLai, (float) $item->so_gio_tang_ca), 2);
            $conLai = max(0, round($conLai - $phan, 2));

            $thucHien = $item->thuc_hien ?: new ThucHienTangCa(['dang_ky_tang_ca_id' => $item->id]);
            $thucHien->so_gio_tang_ca_thuc_te = $phan;
            $thucHien->trang_thai = $phan > 0 ? 'quan_ly_xac_nhan' : 'chua_xac_nhan';
            $thucHien->ghi_chu = 'Tính từ check-out một lần.';
            if (Schema::hasColumn('thuc_hien_tang_ca', 'thoi_gian_bat_dau')) {
                $thucHien->thoi_gian_bat_dau = $ketThucCa;
                $thucHien->thoi_gian_ket_thuc = $gioRa;
            }
            if (Schema::hasColumn('thuc_hien_tang_ca', 'gio_bat_dau_thuc_te')) {
                $thucHien->gio_bat_dau_thuc_te = $ketThucCa->format('H:i:s');
                $thucHien->gio_ket_thuc_thuc_te = $gioRa->format('H:i:s');
            }
            $thucHien->save();

            $item->da_hoan_thanh = true;
            $item->thoi_gian_hoan_thanh = $gioRa;
            $item->save();
        }
    }

    private function moc(Carbon $ngay, Carbon $gio): Carbon
    {
        return Carbon::parse(
            $ngay->toDateString() . ' ' . $gio->format('H:i:s'),
            'Asia/Ho_Chi_Minh'
        );
    }

    private function mocGio(Carbon $ngay, string $gio): Carbon
    {
        return Carbon::parse($ngay->toDateString() . ' ' . $gio, 'Asia/Ho_Chi_Minh');
    }

    private function phut(Carbon $tu, Carbon $den): int
    {
        return max(0, (int) round(($den->getTimestamp() - $tu->getTimestamp()) / 60));
    }

    private function phutGiao(Carbon $a1, Carbon $a2, Carbon $b1, Carbon $b2): int
    {
        $start = $a1->greaterThan($b1) ? $a1->copy() : $b1->copy();
        $end = $a2->lessThan($b2) ? $a2->copy() : $b2->copy();

        return $this->phut($start, $end);
    }

    private function dinhDang(float $gio): string
    {
        $phut = (int) round($gio * 60);
        $h = intdiv($phut, 60);
        $m = $phut % 60;

        return $m === 0 ? "{$h} giờ" : "{$h} giờ {$m} phút";
    }
}

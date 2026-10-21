<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CauHinhBhxhThue;
use App\Models\HoSo;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Services\TinhLuongService;
use Illuminate\Http\Request;

class QuyetToanThueController extends Controller
{
    public function index(Request $request)
    {
        $nam = (int) $request->input('nam', now()->year);
        $thang = (int) $request->input('thang', now()->month);
        $dongThang = $this->tongHop($nam, $thang);
        $dongNam = $this->quyetToanNam($nam);

        return view('admin.quyet-toan-thue.index', compact('nam', 'thang', 'dongThang', 'dongNam'));
    }

    public function kk(Request $request)
    {
        $nam = (int) $request->input('nam', now()->year);
        $thang = (int) $request->input('thang', now()->month);
        $dong = $this->tongHop($nam, $thang);
        $mau = '05/KK-TNCN';
        return view('admin.quyet-toan-thue.to-khai', compact('nam', 'thang', 'dong', 'mau'));
    }

    public function qtt(Request $request)
    {
        $nam = (int) $request->input('nam', now()->year);
        $thang = null;
        $dong = $this->quyetToanNam($nam);
        $mau = '05/QTT-TNCN';
        return view('admin.quyet-toan-thue.to-khai', compact('nam', 'thang', 'dong', 'mau'));
    }

    private function tongHop(int $nam, int $thang): array
    {
        $luongs = LuongNhanVien::with('nguoiDung.hoSo')
            ->where('luong_nam', $nam)
            ->where('luong_thang', $thang)
            ->get();

        return $this->dongGop($luongs, false, $nam);
    }

    private function quyetToanNam(int $nam): array
    {
        $luongs = LuongNhanVien::with('nguoiDung.hoSo')->where('luong_nam', $nam)->get();
        return $this->dongGop($luongs, true, $nam);
    }

    private function dongGop($luongs, bool $quyetToanNam, int $nam): array
    {
        $cfg = CauHinhBhxhThue::choNam($nam);
        $bacNam = array_map(function ($bac) {
            return [
                'bac' => $bac['bac'] ?? 0,
                'den' => isset($bac['den']) && $bac['den'] !== null ? $bac['den'] * 12 : null,
                'thue_suat' => $bac['thue_suat'],
            ];
        }, $cfg->bacThue());

        $nhom = $luongs->groupBy('nguoi_dung_id');
        $dong = [];
        foreach ($nhom as $userId => $rows) {
            $user = $rows->first()->nguoiDung;
            $hoSo = $user?->hoSo;
            $tnct = (float) $rows->sum('thu_nhap_chiu_thue');
            $bh = (float) $rows->sum('tong_bao_hiem');
            $giamTru = (float) $rows->sum('giam_tru_gia_canh');
            $tinhThue = max(0, round($tnct - $bh - $giamTru, 2));
            $daKhauTru = (float) $rows->sum('thue_thu_nhap_ca_nhan');

            if ($quyetToanNam) {
                $phaiNop = round(array_sum(array_column(
                    TinhLuongService::chiTietBacThue($tinhThue, $bacNam),
                    'thue'
                )), 2);
            } else {
                $phaiNop = $daKhauTru;
            }

            $dong[] = [
                'ho_ten' => trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? '')) ?: ($user->ten_dang_nhap ?? ('#' . $userId)),
                'mst' => $hoSo->ma_so_thue ?? '',
                'thu_nhap_chiu_thue' => $tnct,
                'bao_hiem' => $bh,
                'giam_tru' => $giamTru,
                'thu_nhap_tinh_thue' => $tinhThue,
                'thue_phai_nop' => $phaiNop,
                'thue_da_khau_tru' => $daKhauTru,
                'chenh_lech' => round($phaiNop - $daKhauTru, 2),
            ];
        }

        return $dong;
    }
}

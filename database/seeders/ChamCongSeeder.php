<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChamCongSeeder extends Seeder
{
    /**
     * Sinh dữ liệu chấm công cho 3 tháng gần nhất + tháng hiện tại (đến hôm nay).
     * Nghỉ Chủ nhật. Có ngày tăng ca, nghỉ phép, đi muộn. Tất cả đã được duyệt.
     */
    public function run(): void
    {
        $userIds = DB::table('nguoi_dung')->pluck('id');

        $start = Carbon::now()->subMonthsNoOverflow(3)->startOfMonth();
        $end   = Carbon::now()->startOfDay();

        $rows = [];

        foreach ($userIds as $uid) {
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                if ($d->isSunday()) {
                    continue; // chỉ nghỉ Chủ nhật
                }

                $day = $d->day;

                // Giá trị mặc định: đi làm đúng giờ
                $gioVao   = '08:25:00';
                $gioRa    = '17:35:00';
                $soGioLam = 8.5;
                $soCong   = 1.0;
                $gioTC    = 0;
                $phutMuon = 0;
                $phutSom  = 0;
                $trang    = 'dung_gio';

                if ($day == 15) {
                    // Nghỉ phép
                    $trang = 'nghi_phep';
                    $gioVao = null; $gioRa = null;
                    $soGioLam = 0; $soCong = 0;
                } elseif ($day == 8) {
                    // Đi muộn
                    $trang = 'di_muon';
                    $gioVao = '08:50:00';
                    $phutMuon = 20;
                    $soGioLam = 8.0;
                } elseif (in_array($day, [5, 12, 20], true)) {
                    // Tăng ca 2 giờ
                    $gioRa = '19:35:00';
                    $soGioLam = 10.5;
                    $gioTC = 2.0;
                }

                $rows[] = [
                    'nguoi_dung_id'         => $uid,
                    'ngay_cham_cong'        => $d->toDateString(),
                    'gio_vao'               => $gioVao,
                    'gio_ra'                => $gioRa,
                    'so_gio_lam'            => $soGioLam,
                    'so_cong'               => $soCong,
                    'gio_tang_ca'           => $gioTC,
                    'phut_di_muon'          => $phutMuon,
                    'phut_ve_som'           => $phutSom,
                    'trang_thai'            => $trang,
                    'dia_chi_ip'            =>  '192.168.1.' . (100 + $uid),
                    'ten_wifi'              => 'HRFlow_WiFi',
                    'dia_chi_mac'           => 'AA:BB:CC:DD:EE:' . str_pad((string) $uid, 2, '0', STR_PAD_LEFT),
                    'ten_thiet_bi'          => 'PC-' . $uid,
                    'phuong_thuc_cham_cong' => 'wifi',
                    'ghi_chu'               => null,
                    'nguoi_phe_duyet_id'    => 1,
                    'trang_thai_duyet'      => 1, // đã duyệt -> được tính lương
                    'ghi_chu_duyet'         => 'OK',
                    'thoi_gian_phe_duyet'   => now(),
                    'created_at'            => $d->copy()->setTime(18, 0)->toDateTimeString(),
                    'updated_at'            => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('cham_cong')->insert($chunk);
        }

        $this->command->info('  → Đã tạo ' . count($rows) . ' bản ghi chấm công (3 tháng gần nhất + tháng hiện tại).');
    }
}

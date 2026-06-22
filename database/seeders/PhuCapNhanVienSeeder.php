<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhuCapNhanVienSeeder extends Seeder
{
    /**
     * Gán phụ cấp cho nhân viên.
     * phu_cap ids (theo PhuCapSeeder): 1=Ăn trưa, 2=Xăng xe, 3=Trách nhiệm(%), 4=Điện thoại
     */
    public function run(): void
    {
        $hieuLuc = Carbon::now()->startOfYear()->toDateString();

        // [nguoi_dung_id => [ [phu_cap_id, so_tien], ... ]]
        // so_tien của phụ cấp loại % (id=3) là PHẦN TRĂM lương cơ bản
        $ganPhuCap = [
            1 => [[1, 650000], [2, 1000000], [3, 10]],   // GĐ: ăn trưa + xăng xe + trách nhiệm 10%
            2 => [[1, 650000], [2, 1000000], [3, 10]],   // TP HR
            3 => [[1, 650000], [2, 1000000], [3, 10]],   // TP IT
            4 => [[1, 650000], [4, 300000]],             // NV dev: ăn trưa + điện thoại
            5 => [[1, 650000], [4, 300000]],             // NV kế toán
        ];

        $rows = [];
        foreach ($ganPhuCap as $nguoiDungId => $dsPhuCap) {
            foreach ($dsPhuCap as [$phuCapId, $soTien]) {
                $rows[] = [
                    'nguoi_dung_id' => $nguoiDungId,
                    'phu_cap_id'    => $phuCapId,
                    'so_tien'       => $soTien,
                    'ngay_hieu_luc' => $hieuLuc,
                    'ngay_ket_thuc' => null,
                    'trang_thai'    => 'hieu_luc',
                    'ghi_chu'       => null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        }

        DB::table('phu_cap_nhan_vien')->insert($rows);
    }
}

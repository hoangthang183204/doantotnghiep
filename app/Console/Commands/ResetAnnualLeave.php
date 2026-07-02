<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SoDuPhep;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResetAnnualLeave extends Command
{
    protected $signature = 'leave:reset-annual';
    protected $description = 'Tự động cấp phép năm mới và chuyển phép dư năm cũ (Tối đa 5 ngày)';

    public function handle()
    {
        $namHienTai = Carbon::now()->year;
        $namCu = $namHienTai - 1;

        // Lấy danh sách nhân viên đang làm việc
        $nhanViens = DB::table('nguoi_dung')->where('trang_thai_cong_viec', 'dang_lam')->get();

        foreach ($nhanViens as $nv) {
            $phepChuyenSang = 0;

            // Kiểm tra số dư phép của năm cũ
            $thongTinNamCu = SoDuPhep::where('nguoi_dung_id', $nv->id)->where('nam', $namCu)->first();
            if ($thongTinNamCu) {
                $phepConLaiNamCu = ($thongTinNamCu->phep_nam_moi + $thongTinNamCu->phep_cu_chuyen_sang) - $thongTinNamCu->phep_da_dung;
                if ($phepConLaiNamCu > 0) {
                    $phepChuyenSang = min($phepConLaiNamCu, 5); // Tối đa lấy 5 ngày
                }
            }

            // Tạo mới hoặc cập nhật số phép cho năm mới
            SoDuPhep::updateOrCreate(
                ['nguoi_dung_id' => $nv->id, 'nam' => $namHienTai],
                [
                    'phep_nam_moi' => 12.0,
                    'phep_cu_chuyen_sang' => $phepChuyenSang,
                    'phep_da_dung' => 0.0
                ]
            );
        }

        $this->info("Đã cập nhật phép năm mới thành công cho năm {$namHienTai}!");
    }
}
<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use App\Services\TinhLuongService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Sinh bảng lương mẫu cho 3 tháng gần nhất bằng chính engine TinhLuongService,
 * để dữ liệu khớp 100% với công thức của hệ thống.
 *
 *  - 2 tháng trước nữa : ĐÃ CHỐT  (da_chot)
 *  - tháng liền trước  : NHÁP     (dang_xu_ly) - để demo nút "Chốt lương"
 */
class LuongDemoSeeder extends Seeder
{
    public function run(): void
    {
        /** @var TinhLuongService $service */
        $service = app(TinhLuongService::class);

        $nhanVienIds = NguoiDung::where('trang_thai', 1)->pluck('id')->all();

        foreach ([3, 2, 1] as $offset) {
            $moc   = Carbon::now()->startOfMonth()->subMonthsNoOverflow($offset);
            $thang = $moc->month;
            $nam   = $moc->year;

            if ($offset === 1) {
                // Tháng liền trước -> để nháp cho người dùng tự chốt
                $service->taoBangLuong($thang, $nam, $nhanVienIds, 1, 'dang_xu_ly');
                $this->command->info("  → Đã tính lương (nháp) tháng {$thang}/{$nam}.");
            } else {
                $service->chotThang($thang, $nam, 1);
                $this->command->info("  → Đã chốt lương tháng {$thang}/{$nam}.");
            }
        }
    }
}

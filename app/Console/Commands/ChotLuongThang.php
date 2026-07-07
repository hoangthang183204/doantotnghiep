<?php

namespace App\Console\Commands;

use App\Mail\PhieuLuongMail;
use App\Models\BangLuong;
use App\Services\TinhLuongService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ChotLuongThang extends Command
{
    /**
     * Mặc định chốt lương cho THÁNG TRƯỚC (vì chạy vào ngày 1 hàng tháng).
     * Có thể chỉ định: php artisan luong:chot --thang=5 --nam=2026 --force --gui-email
     */
    protected $signature = 'luong:chot
                            {--thang= : Tháng cần chốt (mặc định: tháng trước)}
                            {--nam= : Năm cần chốt (mặc định: năm của tháng trước)}
                            {--force : Tính lại dù bảng lương đã tồn tại}
                            {--gui-email : Tự động gửi phiếu lương qua email sau khi chốt}';

    protected $description = 'Chốt (khoá) bảng lương theo tháng cho toàn bộ nhân viên đang làm việc';

    public function handle(TinhLuongService $service): int
    {
        $moc   = Carbon::now()->subMonthNoOverflow();
        $thang = (int) ($this->option('thang') ?: $moc->month);
        $nam   = (int) ($this->option('nam') ?: $moc->year);

        $this->info("Đang chốt lương tháng {$thang}/{$nam}...");

        $daCo = BangLuong::where('thang', $thang)->where('nam', $nam)->first();
        if ($daCo && $daCo->da_chot && !$this->option('force')) {
            $this->warn("Bảng lương tháng {$thang}/{$nam} đã được chốt (mã {$daCo->ma_bang_luong}). Bỏ qua.");
            $this->line('Dùng --force để tính lại.');
            return self::SUCCESS;
        }

        $bangLuong = $service->chotThang($thang, $nam, null);

        $soNV   = $bangLuong->luongNhanViens()->count();
        $tongNet = $bangLuong->luongNhanViens()->sum('luong_thuc_nhan');

        $this->info("✔ Đã chốt {$bangLuong->ma_bang_luong}: {$soNV} nhân viên, tổng thực nhận "
            . number_format($tongNet) . ' đ.');

        if ($this->option('gui-email')) {
            $this->guiEmailPhieuLuong($bangLuong);
        }

        return self::SUCCESS;
    }

    /** Gửi phiếu lương qua email cho toàn bộ nhân viên trong bảng lương */
    private function guiEmailPhieuLuong(BangLuong $bangLuong): void
    {
        $this->line('Đang gửi phiếu lương qua email...');

        $bangLuong->loadMissing('luongNhanViens.nguoiDung');
        $daGui = 0;
        $boQua = 0;

        foreach ($bangLuong->luongNhanViens as $luong) {
            $email = $luong->nguoiDung?->email;
            if (!$email) {
                $boQua++;
                continue;
            }

            try {
                Mail::to($email)->send(new PhieuLuongMail($luong));
                $daGui++;
            } catch (\Throwable $e) {
                $boQua++;
                $this->warn("  ✗ Lỗi gửi cho {$email}: {$e->getMessage()}");
            }
        }

        $this->info("✔ Đã gửi {$daGui} phiếu lương qua email" . ($boQua ? " ({$boQua} bị bỏ qua/không có email)." : '.'));
    }
}

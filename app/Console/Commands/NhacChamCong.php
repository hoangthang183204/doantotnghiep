<?php

namespace App\Console\Commands;

use App\Models\ChamCong;
use App\Models\NguoiDung;
use App\Notifications\ThongBaoChung;
use App\Services\LichLamViecService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NhacChamCong extends Command
{
    protected $signature = 'cham-cong:nhac';

    protected $description = 'Nhắc nhân viên chưa chấm công vào trong ngày làm việc';

    public function handle(): int
    {
        $homNay = Carbon::now('Asia/Ho_Chi_Minh');
        $thu = (int) $homNay->dayOfWeekIso;
        if (!in_array($thu, LichLamViecService::ngayLamTrongTuan(), true) || LichLamViecService::laNgayLe($homNay)) {
            $this->info('Hôm nay không phải ngày làm việc.');
            return self::SUCCESS;
        }

        $daCham = ChamCong::whereDate('ngay_cham_cong', $homNay->toDateString())
            ->whereNotNull('gio_vao')
            ->pluck('nguoi_dung_id');

        $chuaCham = NguoiDung::where('trang_thai_cong_viec', 'dang_lam')
            ->whereNotIn('id', $daCham)
            ->get();

        foreach ($chuaCham as $nv) {
            $nv->notify(new ThongBaoChung(
                'Nhắc chấm công',
                'Bạn chưa check-in hôm nay (' . $homNay->format('d/m/Y') . ').',
                url('/employee/cham-cong-face')
            ));
        }

        $this->info('Đã nhắc ' . $chuaCham->count() . ' nhân viên.');
        return self::SUCCESS;
    }
}

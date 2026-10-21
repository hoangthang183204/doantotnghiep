<?php

namespace App\Jobs;

use App\Exports\ChiTietBangLuongExport;
use App\Models\BangLuong;
use App\Models\NguoiDung;
use App\Notifications\ThongBaoChung;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class XuatBangLuongJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $bangLuongId,
        public int $nguoiYeuCauId,
        public ?array $phongBanIds = null
    ) {
    }

    public function handle(): void
    {
        $bang = BangLuong::find($this->bangLuongId);
        $user = NguoiDung::find($this->nguoiYeuCauId);
        if (!$bang || !$user) {
            return;
        }

        $ten = $this->nguoiYeuCauId . '_bang_luong_' . $bang->thang . '_' . $bang->nam . '_' . now()->format('YmdHis') . '.xlsx';
        Excel::store(
            new ChiTietBangLuongExport($bang->id, $this->phongBanIds),
            'exports/' . $ten,
            'local'
        );

        $user->notify(new ThongBaoChung(
            'File bảng lương đã sẵn sàng',
            'Bảng lương tháng ' . $bang->thang . '/' . $bang->nam . ' đã xuất xong. Bấm để tải file Excel.',
            route('admin.bang-luong.tep-xuat', ['ten' => $ten])
        ));
    }
}

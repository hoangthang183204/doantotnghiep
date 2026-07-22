<?php

namespace App\Notifications;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HopDongDaKyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $hopDong;

    public function __construct(HopDongLaoDong $hopDong)
    {
        $this->hopDong = $hopDong;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $hoTen = $this->hopDong->hoSoNguoiDung 
            ? ($this->hopDong->hoSoNguoiDung->ho . ' ' . $this->hopDong->hoSoNguoiDung->ten) 
            : 'Nhân viên';

        return [
            'title' => '✅ Nhân viên đã ký hợp đồng',
            'message' => 'Nhân viên ' . $hoTen . ' đã ký hợp đồng ' . $this->hopDong->so_hop_dong . '. Vui lòng kiểm tra.',
            'icon' => 'check-circle',
            'color' => 'success',
            'hop_dong_id' => $this->hopDong->id,
            'so_hop_dong' => $this->hopDong->so_hop_dong,
            'nhan_vien' => $hoTen,
            'url' => route('admin.hop-dong.show', $this->hopDong->id),
            'time' => now()->toIso8601String(),
        ];
    }
}
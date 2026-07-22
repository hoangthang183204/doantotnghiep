<?php
// app/Notifications/HopDongBiHuyNotification.php

namespace App\Notifications;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HopDongBiHuyNotification extends Notification
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
        return [
            'title' => '❌ Hợp đồng đã bị hủy',
            'message' => 'Hợp đồng ' . $this->hopDong->so_hop_dong . ' của bạn đã bị hủy. Lý do: ' . ($this->hopDong->ly_do_huy ?? 'Không có lý do'),
            'icon' => 'x-circle',
            'color' => 'danger',
            'hop_dong_id' => $this->hopDong->id,
            'so_hop_dong' => $this->hopDong->so_hop_dong,
            'url' => route('employee.hop-dong.index'),
            'time' => now()->toIso8601String(),
        ];
    }
}
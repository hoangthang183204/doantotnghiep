<?php

namespace App\Notifications;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HopDongGuiKyNotification extends Notification implements ShouldQueue
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
            'title' => '📄 Hợp đồng cần ký',
            'message' => 'Hợp đồng ' . $this->hopDong->so_hop_dong . ' đã được gửi đến bạn. Vui lòng đăng nhập để ký hợp đồng.',
            'icon' => 'file-text',
            'color' => 'info',
            'hop_dong_id' => $this->hopDong->id,
            'so_hop_dong' => $this->hopDong->so_hop_dong,
            'url' => route('employee.hop-dong.index'),
            'time' => now()->toIso8601String(),
        ];
    }
}
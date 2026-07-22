<?php

namespace App\Notifications;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HopDongChoDuyetNotification extends Notification implements ShouldQueue
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
            'title' => '📄 Hợp đồng mới cần duyệt',
            'message' => 'Hợp đồng ' . $this->hopDong->so_hop_dong . ' vừa được HR tạo và đang chờ bạn duyệt.',
            'icon' => 'file-text',
            'color' => 'warning',
            'hop_dong_id' => $this->hopDong->id,
            'so_hop_dong' => $this->hopDong->so_hop_dong,
            'nhan_vien' => $this->hopDong->hoSoNguoiDung ? 
                ($this->hopDong->hoSoNguoiDung->ho . ' ' . $this->hopDong->hoSoNguoiDung->ten) : 
                'N/A',
            'url' => route('admin.hop-dong.show', $this->hopDong->id),
            'time' => now()->toIso8601String(),
        ];
    }
}
<?php

namespace App\Notifications;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HopDongDuyetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $hopDong;
    protected $action;

    public function __construct(HopDongLaoDong $hopDong, string $action)
    {
        $this->hopDong = $hopDong;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $isDuyet = $this->action === 'duyet';
        
        return [
            'title' => $isDuyet ? '✅ Hợp đồng đã được duyệt' : '❌ Hợp đồng bị từ chối duyệt',
            'message' => $isDuyet
                ? 'Hợp đồng ' . $this->hopDong->so_hop_dong . ' đã được Giám đốc duyệt. Vui lòng gửi cho nhân viên ký.'
                : 'Hợp đồng ' . $this->hopDong->so_hop_dong . ' bị từ chối duyệt. Lý do: ' . $this->hopDong->ly_do_tu_choi,
            'icon' => $isDuyet ? 'check-circle' : 'x-circle',
            'color' => $isDuyet ? 'success' : 'danger',
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
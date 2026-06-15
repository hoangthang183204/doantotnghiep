<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PheDuyetYeuCauChinhCong extends Notification
{
    use Queueable;

    protected $yeuCau;
    protected $trangThai;

    public function __construct($yeuCau, $trangThai)
    {
        $this->yeuCau = $yeuCau;
        $this->trangThai = $trangThai;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->trangThai === 'da_duyet' ? '✅ Yêu cầu điều chỉnh công được duyệt' : '❌ Yêu cầu điều chỉnh công bị từ chối',
            'message' => $this->trangThai === 'da_duyet' 
                ? 'Yêu cầu điều chỉnh công ngày ' . $this->yeuCau->ngay->format('d/m/Y') . ' của bạn đã được duyệt.'
                : 'Yêu cầu điều chỉnh công ngày ' . $this->yeuCau->ngay->format('d/m/Y') . ' của bạn đã bị từ chối.',
            'yeu_cau_id' => $this->yeuCau->id,
            'url' => route('admin.yeu-cau-dieu-chinh-cong.show', $this->yeuCau->id),
        ];
    }
}
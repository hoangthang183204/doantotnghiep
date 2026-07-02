<?php
// app/Notifications/PheDuyetYeuCauChinhCong.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PheDuyetYeuCauChinhCong extends Notification implements ShouldQueue
{
    use Queueable;

    protected $yeuCau;
    protected $trangThai;

    public function __construct($yeuCau, $trangThai)
    {
        $this->yeuCau = $yeuCau;
        $this->trangThai = $trangThai;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $employeeName = 'Nhân viên';
        if ($this->yeuCau->nguoiDung && $this->yeuCau->nguoiDung->hoSo) {
            $employeeName = $this->yeuCau->nguoiDung->hoSo->ho . ' ' . $this->yeuCau->nguoiDung->hoSo->ten;
        }

        $isAdmin = false;
        if ($notifiable && method_exists($notifiable, 'vaiTros')) {
            $roles = $notifiable->vaiTros->pluck('name')->toArray();
            $isAdmin = array_intersect($roles, ['admin', 'Super Admin']);
        }

        if ($this->trangThai === 'da_duyet') {
            $data = [
                'title' => '✅ Yêu cầu chỉnh công được duyệt',
                'message' => "Yêu cầu chỉnh công ngày " . $this->yeuCau->ngay->format('d/m/Y') . " của bạn đã được duyệt.",
                'icon' => 'check-circle',
                'color' => 'success'
            ];
        } else {
            $data = [
                'title' => '❌ Yêu cầu chỉnh công bị từ chối',
                'message' => "Yêu cầu chỉnh công ngày " . $this->yeuCau->ngay->format('d/m/Y') . " của bạn đã bị từ chối.",
                'icon' => 'x-circle',
                'color' => 'danger'
            ];
        }

        $prefix = $isAdmin ? 'admin' : 'employee';
        $url = url('/' . $prefix . '/yeu-cau-chinh-cong/' . $this->yeuCau->id);

        return array_merge($data, [
            'yeu_cau_id' => $this->yeuCau->id,
            'url' => $url,
            'time' => now()->toISOString(),
        ]);
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
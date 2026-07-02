<?php
// app/Notifications/OvertimeNotification.php

namespace App\Notifications;

use App\Models\DangKyTangCa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OvertimeNotification extends Notification
{
    use Queueable;

    protected $tangCa;
    protected $action;

    public function __construct($tangCa, $action)
    {
        $this->tangCa = $tangCa;
        $this->action = $action;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $employeeName = 'Nhân viên';
        if ($this->tangCa->nguoiDung && $this->tangCa->nguoiDung->hoSo) {
            $employeeName = $this->tangCa->nguoiDung->hoSo->ho . ' ' . $this->tangCa->nguoiDung->hoSo->ten;
        }

        $config = [
            'created' => [
                'title' => '⏰ Đăng ký tăng ca mới',
                'message' => "Nhân viên {$employeeName} đã đăng ký tăng ca ngày {$this->tangCa->ngay_tang_ca->format('d/m/Y')}.",
                'icon' => 'clock',
                'color' => 'info'
            ],
            'approved' => [
                'title' => '✅ Đăng ký tăng ca được duyệt',
                'message' => "Đăng ký tăng ca ngày {$this->tangCa->ngay_tang_ca->format('d/m/Y')} đã được duyệt.",
                'icon' => 'check-circle',
                'color' => 'success'
            ],
            'rejected' => [
                'title' => '❌ Đăng ký tăng ca bị từ chối',
                'message' => "Đăng ký tăng ca ngày {$this->tangCa->ngay_tang_ca->format('d/m/Y')} đã bị từ chối.",
                'icon' => 'x-circle',
                'color' => 'danger'
            ],
            'cancelled' => [
                'title' => '🔄 Đăng ký tăng ca đã hủy',
                'message' => "Đăng ký tăng ca đã được hủy bỏ.",
                'icon' => 'minus-circle',
                'color' => 'warning'
            ]
        ];

        $data = $config[$this->action] ?? $config['created'];

        // ⭐ Xác định user là admin hay nhân viên
        $isAdmin = false;
        if ($notifiable && method_exists($notifiable, 'vaiTros')) {
            $roles = $notifiable->vaiTros->pluck('name')->toArray();
            $isAdmin = array_intersect($roles, ['admin', 'Super Admin']);
        }

        // ⭐ Tạo URL đúng theo role
        $prefix = $isAdmin ? 'admin' : 'employee';
        $url = url('/' . $prefix . '/tang-ca/' . $this->tangCa->id);

        return array_merge($data, [
            'tang_ca_id' => $this->tangCa->id,
            'ngay_tang_ca' => $this->tangCa->ngay_tang_ca->format('d/m/Y'),
            'so_gio' => $this->tangCa->so_gio_tang_ca,
            'loai_tang_ca' => $this->tangCa->loai_tang_ca,
            'url' => $url,
            'time' => now()->toISOString(),
        ]);
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
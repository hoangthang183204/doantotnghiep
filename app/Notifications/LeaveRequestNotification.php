<?php
// app/Notifications/LeaveRequestNotification.php

namespace App\Notifications;

use App\Models\DonXinNghi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donNghi;
    protected $action;

    public function __construct($donNghi, $action)
    {
        $this->donNghi = $donNghi;
        $this->action = $action;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        // Lấy tên nhân viên
        $employeeName = 'Nhân viên';
        if ($this->donNghi->nguoiDung && $this->donNghi->nguoiDung->hoSo) {
            $employeeName = $this->donNghi->nguoiDung->hoSo->ho . ' ' . $this->donNghi->nguoiDung->hoSo->ten;
        }

        // Cấu hình theo action
        $config = [
            'created' => [
                'title' => '📝 Đơn nghỉ phép mới',
                'message' => "Nhân viên {$employeeName} đã tạo đơn nghỉ phép mới.",
                'icon' => 'file-text',
                'color' => 'info'
            ],
            'approved' => [
                'title' => '✅ Đơn nghỉ phép được duyệt',
                'message' => "Đơn nghỉ phép của bạn đã được duyệt.",
                'icon' => 'check-circle',
                'color' => 'success'
            ],
            'rejected' => [
                'title' => '❌ Đơn nghỉ phép bị từ chối',
                'message' => "Đơn nghỉ phép của bạn đã bị từ chối.",
                'icon' => 'x-circle',
                'color' => 'danger'
            ],
            'cancelled' => [
                'title' => '🔄 Đơn nghỉ phép đã hủy',
                'message' => "Đơn nghỉ phép đã được hủy bỏ.",
                'icon' => 'minus-circle',
                'color' => 'warning'
            ]
        ];

        $data = $config[$this->action] ?? $config['created'];

        // Xác định user là admin hay nhân viên
        $isAdmin = false;
        if ($notifiable && $notifiable->vaiTro) {
            $isAdmin = in_array($notifiable->vaiTro->name, ['admin', 'Super Admin', 'Admin']);
        }

        // Tạo URL đúng theo role
        $prefix = $isAdmin ? 'admin' : 'employee';
        $url = '/' . $prefix . '/don-nghi/' . $this->donNghi->id;

        return array_merge($data, [
            'don_nghi_id' => $this->donNghi->id,
            'ma_don_nghi' => $this->donNghi->ma_don_nghi ?? 'DN' . time(),
            'url' => $url,
            'time' => now()->toISOString(),
        ]);
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
<?php
// app/Notifications/LeaveRequestNotification.php

namespace App\Notifications;

use App\Models\DonXinNghi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestNotification extends Notification
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
        $employeeName = 'Nhân viên';
        if ($this->donNghi->nguoiDung && $this->donNghi->nguoiDung->hoSo) {
            $employeeName = $this->donNghi->nguoiDung->hoSo->ho . ' ' . $this->donNghi->nguoiDung->hoSo->ten;
        }

        // Lấy tên phòng ban của nhân viên
        $phongBanName = '';
        if ($this->donNghi->nguoiDung && $this->donNghi->nguoiDung->phongBan) {
            $phongBanName = $this->donNghi->nguoiDung->phongBan->ten_phong_ban;
        }

        $config = [
            'created' => [
                'title' => '📝 Đơn nghỉ phép mới',
                'message' => "Nhân viên {$employeeName} đã tạo đơn nghỉ phép mới." . ($phongBanName ? " (Phòng: {$phongBanName})" : ""),
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
                'message' => "Nhân viên {$employeeName} đã hủy đơn nghỉ phép.",
                'icon' => 'minus-circle',
                'color' => 'warning'
            ]
        ];

        $data = $config[$this->action] ?? $config['created'];

        // Xác định user là admin, trưởng phòng hay nhân viên
        $isAdmin = false;
        $isTruongPhong = false;
        if ($notifiable && method_exists($notifiable, 'vaiTros')) {
            $roles = $notifiable->vaiTros->pluck('name')->toArray();
            $isAdmin = array_intersect($roles, ['admin', 'Super Admin']);
            $isTruongPhong = array_intersect($roles, ['truong_phong', 'quan_ly']);
        }

        // Tạo URL đúng theo role
        $prefix = 'employee';
        if ($isAdmin) {
            $prefix = 'admin';
        } elseif ($isTruongPhong) {
            $prefix = 'truong-phong';
        }
        
        $url = url('/' . $prefix . '/don-nghi/' . $this->donNghi->id);

        // Thêm thông tin phòng ban và người tạo
        $data['phong_ban'] = $phongBanName;
        $data['nhan_vien'] = $employeeName;
        $data['so_ngay_nghi'] = $this->donNghi->so_ngay_nghi;
        $data['loai_nghi'] = $this->donNghi->loaiNghiPhep ? $this->donNghi->loaiNghiPhep->ten : 'N/A';

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
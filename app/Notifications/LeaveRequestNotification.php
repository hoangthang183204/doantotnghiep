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

        $tuNgay = optional($this->donNghi->ngay_bat_dau)->format('d/m/Y') ?? '';
        $denNgay = optional($this->donNghi->ngay_ket_thuc)->format('d/m/Y') ?? '';
        $loaiNghi = $this->donNghi->loaiNghiPhep->ten ?? 'N/A';
        $soNgay = $this->donNghi->so_ngay_nghi;
        $lyDo = $this->donNghi->ly_do ?: '';
        $khoangNgay = trim($tuNgay . ($denNgay ? ' – ' . $denNgay : ''));
        $moTa = "{$employeeName}" . ($phongBanName ? " · {$phongBanName}" : '') . " xin {$loaiNghi}, {$soNgay} ngày" . ($khoangNgay ? " ({$khoangNgay})" : '') . ($lyDo ? ". Lý do: {$lyDo}" : '');

        $config = [
            'created' => [
                'title' => 'Đơn nghỉ phép mới',
                'message' => $moTa,
                'icon' => 'file-alt',
                'color' => 'info'
            ],
            'approved' => [
                'title' => 'Đơn nghỉ phép được duyệt',
                'message' => "Đơn {$loaiNghi} ({$khoangNgay}) đã được duyệt.",
                'icon' => 'check-circle',
                'color' => 'success'
            ],
            'rejected' => [
                'title' => 'Đơn nghỉ phép bị từ chối',
                'message' => "Đơn {$loaiNghi} ({$khoangNgay}) đã bị từ chối." . ($this->donNghi->ghi_chu ? ' Ghi chú: ' . $this->donNghi->ghi_chu : ''),
                'icon' => 'times-circle',
                'color' => 'danger'
            ],
            'cancelled' => [
                'title' => 'Đơn nghỉ phép đã hủy',
                'message' => "{$employeeName} đã hủy đơn {$loaiNghi} ({$khoangNgay}).",
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
            $isAdmin = array_intersect($roles, ['admin', 'Super Admin', 'Admin', 'hr', 'HR', 'ke_toan']);
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
        $data['so_ngay_nghi'] = $soNgay;
        $data['loai_nghi'] = $loaiNghi;
        $data['tu_ngay'] = $tuNgay;
        $data['den_ngay'] = $denNgay;
        $data['ly_do'] = $lyDo;

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
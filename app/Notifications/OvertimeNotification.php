<?php
// app/Notifications/OvertimeNotification.php

namespace App\Notifications;

use App\Models\DangKyTangCa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OvertimeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tangCa;
    protected $action;

    public function __construct(DangKyTangCa $tangCa, $action)
    {
        $this->tangCa = $tangCa;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $employee = $this->tangCa->nguoi_dung;
        $tenNhanVien = optional($employee->hoSo)
            ? $employee->hoSo->ho . ' ' . $employee->hoSo->ten
            : $employee->ten_dang_nambah;

        $baseMessage = "Ngày: {$this->tangCa->ngay_tang_ca->format('d/m/Y')}\n";
        $baseMessage .= "Thời gian: {$this->tangCa->gio_bat_dau} - {$this->tangCa->gio_ket_thuc}\n";
        $baseMessage .= "Số giờ: {$this->tangCa->so_gio_tang_ca} giờ";

        switch ($this->action) {
            case 'created':
                $title = "📢 Đơn tăng ca mới từ {$tenNhanVien}";
                $message = "Nhân viên {$tenNhanVien} vừa tạo đơn tăng ca.\n" . $baseMessage;
                $color = 'info';
                $icon = 'bell';
                $link = route('duyet-tang-ca.show', $this->tangCa->id);
                break;

            case 'created_by_manager':
                $title = "📢 Trưởng phòng đã tạo đơn tăng ca cho bạn";
                $message = "Trưởng phòng đã tạo đơn tăng ca cho bạn.\n" . $baseMessage;
                $color = 'info';
                $icon = 'user-tie';
                $link = route('employee.tang-ca.show', $this->tangCa->id);
                break;

            case 'approved':
                $title = "✅ Đơn tăng ca đã được duyệt";
                $message = "Đơn tăng ca của bạn đã được duyệt.\n" . $baseMessage;
                $color = 'success';
                $icon = 'check-circle';
                $link = route('employee.tang-ca.show', $this->tangCa->id);
                break;

            case 'rejected':
                $title = "❌ Đơn tăng ca bị từ chối";
                $message = "Đơn tăng ca của bạn đã bị từ chối.\n" . $baseMessage;
                if ($this->tangCa->ly_do_tu_choi) {
                    $message .= "\nLý do: {$this->tangCa->ly_do_tu_choi}";
                }
                $color = 'danger';
                $icon = 'times-circle';
                $link = route('employee.tang-ca.show', $this->tangCa->id);
                break;

            case 'cancelled':
                $title = "🗑️ Đơn tăng ca đã bị hủy";
                $message = "Đơn tăng ca đã bị hủy.\n" . $baseMessage;
                $color = 'warning';
                $icon = 'trash';
                $link = route('duyet-tang-ca.show', $this->tangCa->id);
                break;

            case 'employee_confirmed':
                $title = "✅ Nhân viên đã xác nhận làm tăng ca";
                $message = "Nhân viên {$tenNhanVien} đã xác nhận đã làm tăng ca.\n" . $baseMessage;
                $color = 'success';
                $icon = 'check-double';
                $link = route('duyet-tang-ca.show', $this->tangCa->id);
                break;

            case 'employee_rejected':
                $title = "❌ Nhân viên từ chối đơn tăng ca";
                $message = "Nhân viên {$tenNhanVien} đã từ chối đơn tăng ca.\n" . $baseMessage;
                if ($this->tangCa->ly_do_tu_choi) {
                    $message .= "\nLý do từ chối: {$this->tangCa->ly_do_tu_choi}";
                }
                $color = 'danger';
                $icon = 'user-slash';
                $link = route('duyet-tang-ca.show', $this->tangCa->id);
                break;

            case 'manager_approved':
                $title = "✅ Hoàn thành tăng ca";
                $message = "Quản lý đã xác nhận hoàn thành tăng ca.\n" . $baseMessage;
                $color = 'success';
                $icon = 'check-circle';
                $link = route('employee.tang-ca.show', $this->tangCa->id);
                break;

            default:
                $title = "📢 Thông báo tăng ca";
                $message = $baseMessage;
                $color = 'info';
                $icon = 'bell';
                $link = route('employee.tang-ca.index');
                break;
        }

        return [
            'title' => $title,
            'message' => $message,
            'type' => 'overtime',
            'color' => $color,
            'icon' => $icon,
            'tang_ca_id' => $this->tangCa->id,
            'action' => $this->action,
            'url' => $link,
            'created_at' => now()->toISOString(),
        ];
    }
}
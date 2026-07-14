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

        $ngay = $this->tangCa->ngay_tang_ca->format('d/m/Y');
        $gio = $this->tangCa->gio_bat_dau . ' - ' . $this->tangCa->gio_ket_thuc;

        // ⭐ CẤU HÌNH CHO TỪNG ACTION
        $config = [
            'created' => [
                'title' => '⏰ Đăng ký tăng ca mới',
                'message' => "Nhân viên {$employeeName} đã đăng ký tăng ca ngày {$ngay} ({$gio}).",
                'icon' => 'clock',
                'color' => 'info',
                'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
            ],
            'approved' => [
                'title' => '✅ Đăng ký tăng ca được duyệt',
                'message' => "Đăng ký tăng ca ngày {$ngay} ({$gio}) của {$employeeName} đã được phê duyệt.",
                'icon' => 'check-circle',
                'color' => 'success',
                'badge' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
            ],
            'rejected' => [
                'title' => '❌ Đăng ký tăng ca bị từ chối',
                'message' => "Đăng ký tăng ca ngày {$ngay} ({$gio}) của {$employeeName} đã bị từ chối.",
                'icon' => 'x-circle',
                'color' => 'danger',
                'badge' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
            ],
            'cancelled' => [
                'title' => '🔄 Đăng ký tăng ca đã hủy',
                'message' => "Nhân viên {$employeeName} đã hủy đăng ký tăng ca ngày {$ngay} ({$gio}).",
                'icon' => 'minus-circle',
                'color' => 'warning',
                'badge' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
            ],
            // ⭐ THÊM MỚI: Nhân viên xác nhận đã làm tăng ca
            'employee_confirmed' => [
                'title' => '👤 Xác nhận đã làm tăng ca',
                'message' => "Nhân viên {$employeeName} đã xác nhận đã làm tăng ca ngày {$ngay} ({$gio}). Vui lòng kiểm tra và xác nhận hoàn thành.",
                'icon' => 'user-check',
                'color' => 'info',
                'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                'requires_action' => true
            ],
            // ⭐ THÊM MỚI: Quản lý xác nhận hoàn thành
            'manager_approved' => [
                'title' => '✅ Hoàn thành tăng ca',
                'message' => "Quản lý đã xác nhận hoàn thành tăng ca ngày {$ngay} ({$gio}) cho {$employeeName}." . 
                    ($this->tangCa->luong_tang_ca ? " Lương tăng ca: " . number_format($this->tangCa->luong_tang_ca, 0) . 'đ' : ''),
                'icon' => 'award',
                'color' => 'success',
                'badge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400'
            ],
        ];

        $data = $config[$this->action] ?? $config['created'];

        // ⭐ Xác định user là admin hay nhân viên
        $isAdmin = false;
        $isManager = false;
        if ($notifiable && method_exists($notifiable, 'vaiTros')) {
            $roles = $notifiable->vaiTros->pluck('name')->toArray();
            $isAdmin = array_intersect($roles, ['admin', 'Super Admin']);
            $isManager = array_intersect($roles, ['truong_phong', 'quan_ly']);
        }

        // ⭐ Tạo URL đúng theo role
        if ($isAdmin || $isManager) {
            $url = url('/admin/tang-ca/' . $this->tangCa->id);
        } else {
            $url = url('/employee/tang-ca/' . $this->tangCa->id);
        }

        // ⭐ Thêm thông tin thực hiện tăng ca (nếu có)
        $thucHien = null;
        if ($this->tangCa->thuc_hien) {
            $thucHien = [
                'gio_bat_dau_thuc_te' => $this->tangCa->thuc_hien->gio_bat_dau_thuc_te,
                'gio_ket_thuc_thuc_te' => $this->tangCa->thuc_hien->gio_ket_thuc_thuc_te,
                'so_gio_thuc_te' => $this->tangCa->thuc_hien->so_gio_tang_ca_thuc_te,
                'trang_thai' => $this->tangCa->thuc_hien->trang_thai,
            ];
        }

        return array_merge($data, [
            'tang_ca_id' => $this->tangCa->id,
            'ngay_tang_ca' => $this->tangCa->ngay_tang_ca->format('d/m/Y'),
            'gio_bat_dau' => $this->tangCa->gio_bat_dau,
            'gio_ket_thuc' => $this->tangCa->gio_ket_thuc,
            'so_gio' => $this->tangCa->so_gio_tang_ca,
            'loai_tang_ca' => $this->tangCa->loai_tang_ca,
            'trang_thai' => $this->tangCa->trang_thai,
            'employee_name' => $employeeName,
            'url' => $url,
            'is_admin' => $isAdmin,
            'is_manager' => $isManager,
            'thuc_hien' => $thucHien,
            'luong_tang_ca' => $this->tangCa->luong_tang_ca,
            'time' => now()->toISOString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
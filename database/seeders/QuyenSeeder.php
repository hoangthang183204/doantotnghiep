<?php
// database/seeders/QuyenSeeder.php

namespace Database\Seeders;

use App\Models\Quyen;
use Illuminate\Database\Seeder;

class QuyenSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // ============================================
            // 1. DASHBOARD
            // ============================================
            ['name' => 'dashboard.view', 'ten_hien_thi' => 'Xem Dashboard', 'nhom' => 'dashboard'],
            ['name' => 'dashboard.admin', 'ten_hien_thi' => 'Xem Dashboard Admin', 'nhom' => 'dashboard'],
            ['name' => 'dashboard.manager', 'ten_hien_thi' => 'Xem Dashboard Trưởng phòng', 'nhom' => 'dashboard'],
            ['name' => 'dashboard.employee', 'ten_hien_thi' => 'Xem Dashboard Nhân viên', 'nhom' => 'dashboard'],

            // ============================================
            // 2. HỒ SƠ NHÂN VIÊN
            // ============================================
            ['name' => 'hoso.index', 'ten_hien_thi' => 'Xem danh sách hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.show', 'ten_hien_thi' => 'Xem chi tiết hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.create', 'ten_hien_thi' => 'Thêm hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.edit', 'ten_hien_thi' => 'Sửa hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.resign', 'ten_hien_thi' => 'Xử lý nghỉ việc', 'nhom' => 'hoso'],
            ['name' => 'hoso.activate', 'ten_hien_thi' => 'Kích hoạt nhân viên', 'nhom' => 'hoso'],
            ['name' => 'hoso.personal', 'ten_hien_thi' => 'Xem hồ sơ cá nhân', 'nhom' => 'hoso'],

            // ============================================
            // 3. NGƯỜI DÙNG
            // ============================================
            ['name' => 'user.view', 'ten_hien_thi' => 'Xem danh sách người dùng', 'nhom' => 'user'],
            ['name' => 'user.create', 'ten_hien_thi' => 'Thêm người dùng', 'nhom' => 'user'],
            ['name' => 'user.edit', 'ten_hien_thi' => 'Sửa người dùng', 'nhom' => 'user'],
            ['name' => 'user.delete', 'ten_hien_thi' => 'Xóa người dùng', 'nhom' => 'user'],

            // ============================================
            // 4. PHÒNG BAN
            // ============================================
            ['name' => 'department.view', 'ten_hien_thi' => 'Xem danh sách phòng ban', 'nhom' => 'department'],
            ['name' => 'department.create', 'ten_hien_thi' => 'Thêm phòng ban', 'nhom' => 'department'],
            ['name' => 'department.edit', 'ten_hien_thi' => 'Sửa phòng ban', 'nhom' => 'department'],
            ['name' => 'department.delete', 'ten_hien_thi' => 'Xóa phòng ban', 'nhom' => 'department'],
            ['name' => 'department.org_chart', 'ten_hien_thi' => 'Xem sơ đồ tổ chức', 'nhom' => 'department'],
            ['name' => 'department.statistics', 'ten_hien_thi' => 'Xem thống kê phòng ban', 'nhom' => 'department'],

            // ============================================
            // 5. CHỨC VỤ
            // ============================================
            ['name' => 'chucvu.view', 'ten_hien_thi' => 'Xem danh sách chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.create', 'ten_hien_thi' => 'Thêm chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.edit', 'ten_hien_thi' => 'Sửa chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.delete', 'ten_hien_thi' => 'Xóa chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.org_chart', 'ten_hien_thi' => 'Xem sơ đồ chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.statistics', 'ten_hien_thi' => 'Xem thống kê chức vụ', 'nhom' => 'chucvu'],

            // ============================================
            // 6. CHẤM CÔNG
            // ============================================
            ['name' => 'attendance.index', 'ten_hien_thi' => 'Xem danh sách chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.show', 'ten_hien_thi' => 'Xem chi tiết chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.export', 'ten_hien_thi' => 'Xuất Excel chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.checkin', 'ten_hien_thi' => 'Chấm công vào', 'nhom' => 'attendance'],
            ['name' => 'attendance.checkout', 'ten_hien_thi' => 'Chấm công ra', 'nhom' => 'attendance'],
            ['name' => 'attendance.history', 'ten_hien_thi' => 'Xem lịch sử chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.save_device', 'ten_hien_thi' => 'Lưu thông tin thiết bị', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime_approve', 'ten_hien_thi' => 'Phê duyệt tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime_reject', 'ten_hien_thi' => 'Từ chối tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime_bulk', 'ten_hien_thi' => 'Duyệt hàng loạt tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment_approve', 'ten_hien_thi' => 'Duyệt chỉnh công', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment_reject', 'ten_hien_thi' => 'Từ chối chỉnh công', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment_bulk', 'ten_hien_thi' => 'Duyệt hàng loạt chỉnh công', 'nhom' => 'attendance'],

            // ============================================
            // 7. TĂNG CA
            // ============================================
            ['name' => 'overtime.create', 'ten_hien_thi' => 'Tạo đơn tăng ca', 'nhom' => 'overtime'],
            ['name' => 'overtime.index', 'ten_hien_thi' => 'Xem danh sách tăng ca', 'nhom' => 'overtime'],
            ['name' => 'overtime.show', 'ten_hien_thi' => 'Xem chi tiết tăng ca', 'nhom' => 'overtime'],
            ['name' => 'overtime.huy', 'ten_hien_thi' => 'Hủy đơn tăng ca', 'nhom' => 'overtime'],

            // ============================================
            // 8. CHỈNH CÔNG
            // ============================================
            ['name' => 'adjustment.create', 'ten_hien_thi' => 'Tạo yêu cầu chỉnh công', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.index', 'ten_hien_thi' => 'Xem danh sách yêu cầu', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.show', 'ten_hien_thi' => 'Xem chi tiết yêu cầu', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.huy', 'ten_hien_thi' => 'Hủy yêu cầu chỉnh công', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.download', 'ten_hien_thi' => 'Tải file đính kèm', 'nhom' => 'adjustment'],

            // ============================================
            // 9. LƯƠNG
            // ============================================
            ['name' => 'salary.index', 'ten_hien_thi' => 'Xem danh sách lương', 'nhom' => 'salary'],
            ['name' => 'salary.show', 'ten_hien_thi' => 'Xem chi tiết lương', 'nhom' => 'salary'],
            ['name' => 'salary.create', 'ten_hien_thi' => 'Tạo bảng lương', 'nhom' => 'salary'],
            ['name' => 'salary.calculate', 'ten_hien_thi' => 'Tính lương', 'nhom' => 'salary'],
            ['name' => 'salary.approve', 'ten_hien_thi' => 'Duyệt lương', 'nhom' => 'salary'],
            ['name' => 'salary.export', 'ten_hien_thi' => 'Xuất bảng lương', 'nhom' => 'salary'],
            ['name' => 'salary.destroy', 'ten_hien_thi' => 'Xóa bảng lương', 'nhom' => 'salary'],
            ['name' => 'salary.chot', 'ten_hien_thi' => 'Chốt lương', 'nhom' => 'salary'],
            ['name' => 'salary.thanh_toan', 'ten_hien_thi' => 'Thanh toán lương', 'nhom' => 'salary'],
            ['name' => 'salary.gui_email', 'ten_hien_thi' => 'Gửi email bảng lương', 'nhom' => 'salary'],
            ['name' => 'salary.gui_tat_ca', 'ten_hien_thi' => 'Gửi email hàng loạt', 'nhom' => 'salary'],
            ['name' => 'salary.chi_tiet_nhan_vien', 'ten_hien_thi' => 'Xem chi tiết lương nhân viên', 'nhom' => 'salary'],
            ['name' => 'salary.allowance', 'ten_hien_thi' => 'Quản lý phụ cấp', 'nhom' => 'salary'],

            // ============================================
            // 10. BẢNG LƯƠNG CÁ NHÂN
            // ============================================
            ['name' => 'payroll.index', 'ten_hien_thi' => 'Xem bảng lương cá nhân', 'nhom' => 'payroll'],
            ['name' => 'payroll.show', 'ten_hien_thi' => 'Xem chi tiết phiếu lương', 'nhom' => 'payroll'],

            // ============================================
            // 11. PHỤ CẤP
            // ============================================
            ['name' => 'allowance.index', 'ten_hien_thi' => 'Xem danh sách phụ cấp', 'nhom' => 'allowance'],
            ['name' => 'allowance.create', 'ten_hien_thi' => 'Thêm phụ cấp', 'nhom' => 'allowance'],
            ['name' => 'allowance.edit', 'ten_hien_thi' => 'Sửa phụ cấp', 'nhom' => 'allowance'],
            ['name' => 'allowance.delete', 'ten_hien_thi' => 'Xóa phụ cấp', 'nhom' => 'allowance'],

            // ============================================
            // 12. HỢP ĐỒNG
            // ============================================
            ['name' => 'contract.index', 'ten_hien_thi' => 'Xem danh sách hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.show', 'ten_hien_thi' => 'Xem chi tiết hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.create', 'ten_hien_thi' => 'Tạo hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.edit', 'ten_hien_thi' => 'Sửa hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.delete', 'ten_hien_thi' => 'Xóa hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.export', 'ten_hien_thi' => 'Xuất hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.gui_ky', 'ten_hien_thi' => 'Gửi ký hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.huy', 'ten_hien_thi' => 'Hủy hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.tai_ky', 'ten_hien_thi' => 'Tái ký hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.cua_toi', 'ten_hien_thi' => 'Xem hợp đồng của tôi', 'nhom' => 'contract'],
            ['name' => 'contract.luu_tru', 'ten_hien_thi' => 'Xem hợp đồng lưu trữ', 'nhom' => 'contract'],
            ['name' => 'contract.thong_ke', 'ten_hien_thi' => 'Xem thống kê hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.personal', 'ten_hien_thi' => 'Xem hợp đồng cá nhân', 'nhom' => 'contract'],
            ['name' => 'contract.personal_update', 'ten_hien_thi' => 'Cập nhật trạng thái ký', 'nhom' => 'contract'],
            ['name' => 'contract.personal_reject', 'ten_hien_thi' => 'Từ chối ký hợp đồng', 'nhom' => 'contract'],

            // ============================================
            // 13. TĂNG LƯƠNG
            // ============================================
            ['name' => 'tangluong.create', 'ten_hien_thi' => 'Tăng lương cho nhân viên', 'nhom' => 'contract'],
            ['name' => 'tangluong.store', 'ten_hien_thi' => 'Lưu tăng lương', 'nhom' => 'contract'],
            ['name' => 'tangluong.duyet', 'ten_hien_thi' => 'Duyệt tăng lương', 'nhom' => 'contract'],
            ['name' => 'tangluong.tu-choi', 'ten_hien_thi' => 'Từ chối tăng lương', 'nhom' => 'contract'],

            // ============================================
            // 14. ĐÀO TẠO
            // ============================================
            ['name' => 'dao-tao.index', 'ten_hien_thi' => 'Xem danh sách đào tạo', 'nhom' => 'training'],
            ['name' => 'dao-tao.show', 'ten_hien_thi' => 'Xem chi tiết đào tạo', 'nhom' => 'training'],
            ['name' => 'dao-tao.create', 'ten_hien_thi' => 'Đăng ký đào tạo', 'nhom' => 'training'],
            ['name' => 'dao-tao.edit', 'ten_hien_thi' => 'Cập nhật kết quả đào tạo', 'nhom' => 'training'],
            ['name' => 'dao-tao.delete', 'ten_hien_thi' => 'Xóa đào tạo', 'nhom' => 'training'],

            // ============================================
            // 15. CHỨNG CHỈ
            // ============================================
            ['name' => 'chung-chi.index', 'ten_hien_thi' => 'Xem danh sách chứng chỉ', 'nhom' => 'training'],
            ['name' => 'chung-chi.show', 'ten_hien_thi' => 'Xem chi tiết chứng chỉ', 'nhom' => 'training'],
            ['name' => 'chung-chi.edit', 'ten_hien_thi' => 'Cập nhật chứng chỉ', 'nhom' => 'training'],
            ['name' => 'chung-chi.delete', 'ten_hien_thi' => 'Xóa chứng chỉ', 'nhom' => 'training'],

            // ============================================
            // 16. VAI TRÒ ⭐ THÊM VÀO ĐÂY
            // ============================================
            ['name' => 'role.view', 'ten_hien_thi' => 'Xem danh sách vai trò', 'nhom' => 'role'],
            ['name' => 'role.create', 'ten_hien_thi' => 'Thêm vai trò', 'nhom' => 'role'],
            ['name' => 'role.edit', 'ten_hien_thi' => 'Sửa vai trò', 'nhom' => 'role'],
            ['name' => 'role.delete', 'ten_hien_thi' => 'Xóa vai trò', 'nhom' => 'role'],

            // ============================================
            // 17. PHÂN QUYỀN
            // ============================================
            ['name' => 'setting.permission', 'ten_hien_thi' => 'Phân quyền hệ thống', 'nhom' => 'setting'],
            ['name' => 'permission.index', 'ten_hien_thi' => 'Xem phân quyền', 'nhom' => 'permission'],
            ['name' => 'permission.edit', 'ten_hien_thi' => 'Sửa phân quyền', 'nhom' => 'permission'],
            ['name' => 'permission.update', 'ten_hien_thi' => 'Cập nhật phân quyền', 'nhom' => 'permission'],

            // ============================================
            // 18. LOẠI NGHỈ PHÉP
            // ============================================
            ['name' => 'leave_type.index', 'ten_hien_thi' => 'Xem danh sách loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.create', 'ten_hien_thi' => 'Thêm loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.edit', 'ten_hien_thi' => 'Sửa loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.delete', 'ten_hien_thi' => 'Xóa loại nghỉ phép', 'nhom' => 'leave_type'],

            // ============================================
            // 19. NGHỈ PHÉP - ADMIN
            // ============================================
            ['name' => 'leave.admin.index', 'ten_hien_thi' => 'Xem danh sách đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.admin.show', 'ten_hien_thi' => 'Xem chi tiết đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.admin.approve', 'ten_hien_thi' => 'Duyệt đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.admin.reject', 'ten_hien_thi' => 'Từ chối đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.admin.bulk', 'ten_hien_thi' => 'Duyệt hàng loạt đơn nghỉ', 'nhom' => 'leave'],

            // ============================================
            // 20. NGHỈ PHÉP - EMPLOYEE
            // ============================================
            ['name' => 'leave.employee.create', 'ten_hien_thi' => 'Tạo đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.employee.index', 'ten_hien_thi' => 'Xem đơn nghỉ của tôi', 'nhom' => 'leave'],
            ['name' => 'leave.employee.show', 'ten_hien_thi' => 'Xem chi tiết đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.employee.edit', 'ten_hien_thi' => 'Sửa đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.employee.cancel', 'ten_hien_thi' => 'Hủy đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.employee.balance', 'ten_hien_thi' => 'Xem số dư phép', 'nhom' => 'leave'],
            ['name' => 'leave.employee.history', 'ten_hien_thi' => 'Xem lịch sử nghỉ phép', 'nhom' => 'leave'],

            // ============================================
            // 21. QUY ĐỊNH
            // ============================================
            ['name' => 'setting.general', 'ten_hien_thi' => 'Cài đặt chung', 'nhom' => 'setting'],
            ['name' => 'regulation.view', 'ten_hien_thi' => 'Xem quy định', 'nhom' => 'regulation'],
            ['name' => 'regulation.edit', 'ten_hien_thi' => 'Sửa quy định', 'nhom' => 'regulation'],
            ['name' => 'regulation.update', 'ten_hien_thi' => 'Cập nhật quy định', 'nhom' => 'regulation'],
            ['name' => 'regulation.employee', 'ten_hien_thi' => 'Xem quy định công ty', 'nhom' => 'regulation'],

            // ============================================
            // 22. QUẢN LÝ THỜI GIAN
            // ============================================
            ['name' => 'time.index', 'ten_hien_thi' => 'Xem cấu hình thời gian', 'nhom' => 'time'],
            ['name' => 'time.update', 'ten_hien_thi' => 'Cập nhật cấu hình thời gian', 'nhom' => 'time'],

            // ============================================
            // 23. DUYỆT ĐƠN
            // ============================================
            ['name' => 'approval.leave', 'ten_hien_thi' => 'Duyệt đơn nghỉ phép', 'nhom' => 'approval'],
            ['name' => 'approval.overtime', 'ten_hien_thi' => 'Duyệt đơn tăng ca', 'nhom' => 'approval'],
            ['name' => 'approval.adjustment', 'ten_hien_thi' => 'Duyệt chỉnh công', 'nhom' => 'approval'],

            // ============================================
            // 24. THÔNG BÁO
            // ============================================
            ['name' => 'notification.view', 'ten_hien_thi' => 'Xem thông báo', 'nhom' => 'notification'],
            ['name' => 'notification.mark_read', 'ten_hien_thi' => 'Đánh dấu đã đọc', 'nhom' => 'notification'],
            ['name' => 'notification.mark_all_read', 'ten_hien_thi' => 'Đánh dấu tất cả đã đọc', 'nhom' => 'notification'],
            ['name' => 'notification.delete', 'ten_hien_thi' => 'Xóa thông báo', 'nhom' => 'notification'],

            // ============================================
            // 25. HỒ SƠ CÁ NHÂN
            // ============================================
            ['name' => 'profile.view', 'ten_hien_thi' => 'Xem hồ sơ cá nhân', 'nhom' => 'profile'],
            ['name' => 'profile.update', 'ten_hien_thi' => 'Cập nhật hồ sơ', 'nhom' => 'profile'],
            ['name' => 'profile.change_password', 'ten_hien_thi' => 'Đổi mật khẩu', 'nhom' => 'profile'],

            // ============================================
            // 26. KHEN THƯỞNG / KỶ LUẬT
            // ============================================
            ['name' => 'khen_thuong.view', 'ten_hien_thi' => 'Xem danh sách khen thưởng/kỷ luật', 'nhom' => 'khen_thuong'],
            ['name' => 'khen_thuong.create', 'ten_hien_thi' => 'Thêm khen thưởng/kỷ luật', 'nhom' => 'khen_thuong'],
            ['name' => 'khen_thuong.edit', 'ten_hien_thi' => 'Sửa khen thưởng/kỷ luật', 'nhom' => 'khen_thuong'],
            ['name' => 'khen_thuong.delete', 'ten_hien_thi' => 'Xóa khen thưởng/kỷ luật', 'nhom' => 'khen_thuong'],
            ['name' => 'khen_thuong.export', 'ten_hien_thi' => 'Xuất Excel khen thưởng/kỷ luật', 'nhom' => 'khen_thuong'],
            ['name' => 'khen_thuong.statistics', 'ten_hien_thi' => 'Xem thống kê khen thưởng/kỷ luật', 'nhom' => 'khen_thuong'],
        ];

        foreach ($permissions as $permission) {
            Quyen::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'ten_hien_thi' => $permission['ten_hien_thi'],
                    'nhom' => $permission['nhom'],
                ]
            );
        }
    }
}
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
            // 2. HỒ SƠ (NHÂN SỰ) - Có route admin.ho-so.*
            // ============================================
            ['name' => 'hoso.index', 'ten_hien_thi' => 'Xem danh sách hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.show', 'ten_hien_thi' => 'Xem chi tiết hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.create', 'ten_hien_thi' => 'Thêm hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.edit', 'ten_hien_thi' => 'Sửa hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.resign', 'ten_hien_thi' => 'Xử lý nghỉ việc', 'nhom' => 'hoso'],
            ['name' => 'hoso.activate', 'ten_hien_thi' => 'Kích hoạt nhân viên', 'nhom' => 'hoso'],
            ['name' => 'hoso.personal', 'ten_hien_thi' => 'Xem hồ sơ cá nhân', 'nhom' => 'hoso'],

            // ❌ XÓA: hoso.delete (không có route xóa trong web.php)

            // ============================================
            // 3. NGƯỜI DÙNG - Có route admin.nguoi-dung.*
            // ============================================
            ['name' => 'user.view', 'ten_hien_thi' => 'Xem danh sách người dùng', 'nhom' => 'user'],
            ['name' => 'user.create', 'ten_hien_thi' => 'Thêm người dùng', 'nhom' => 'user'],
            ['name' => 'user.edit', 'ten_hien_thi' => 'Sửa người dùng', 'nhom' => 'user'],
            ['name' => 'user.delete', 'ten_hien_thi' => 'Xóa người dùng', 'nhom' => 'user'],

            // ❌ XÓA: user.reset_password, user.lock (không có route)

            // ============================================
            // 4. PHÒNG BAN - Có route admin.phong-ban.*
            // ============================================
            ['name' => 'department.view', 'ten_hien_thi' => 'Xem danh sách phòng ban', 'nhom' => 'department'],
            ['name' => 'department.create', 'ten_hien_thi' => 'Thêm phòng ban', 'nhom' => 'department'],
            ['name' => 'department.edit', 'ten_hien_thi' => 'Sửa phòng ban', 'nhom' => 'department'],
            ['name' => 'department.delete', 'ten_hien_thi' => 'Xóa phòng ban', 'nhom' => 'department'],
            ['name' => 'department.org_chart', 'ten_hien_thi' => 'Xem sơ đồ tổ chức', 'nhom' => 'department'],
            ['name' => 'department.statistics', 'ten_hien_thi' => 'Xem thống kê phòng ban', 'nhom' => 'department'],

            // ============================================
            // 5. CHỨC VỤ - Có route admin.chuc-vu.*
            // ============================================
            ['name' => 'chucvu.view', 'ten_hien_thi' => 'Xem danh sách chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.create', 'ten_hien_thi' => 'Thêm chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.edit', 'ten_hien_thi' => 'Sửa chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.delete', 'ten_hien_thi' => 'Xóa chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.org_chart', 'ten_hien_thi' => 'Xem sơ đồ chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.statistics', 'ten_hien_thi' => 'Xem thống kê chức vụ', 'nhom' => 'chucvu'],

            // ============================================
            // 6. CHẤM CÔNG (QUẢN LÝ) - Có route admin.cham-cong.*
            // ============================================
            ['name' => 'attendance.index', 'ten_hien_thi' => 'Xem danh sách chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.show', 'ten_hien_thi' => 'Xem chi tiết chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.export', 'ten_hien_thi' => 'Xuất Excel chấm công', 'nhom' => 'attendance'],

            // ❌ XÓA: attendance.import, attendance.bulk_action, attendance.phe_duyet (không có route)

            // ============================================
            // 7. CHẤM CÔNG (CÁ NHÂN - EMPLOYEE) - Có route employee.cham-cong.*
            // ============================================
            ['name' => 'attendance.checkin', 'ten_hien_thi' => 'Chấm công vào', 'nhom' => 'attendance'],
            ['name' => 'attendance.checkout', 'ten_hien_thi' => 'Chấm công ra', 'nhom' => 'attendance'],
            ['name' => 'attendance.history', 'ten_hien_thi' => 'Xem lịch sử chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.save_device', 'ten_hien_thi' => 'Lưu thông tin thiết bị', 'nhom' => 'attendance'],

            // ============================================
            // 8. TĂNG CA (QUẢN LÝ) - Có route admin.tang-ca.*
            // ============================================
            ['name' => 'attendance.overtime_approve', 'ten_hien_thi' => 'Phê duyệt tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime_reject', 'ten_hien_thi' => 'Từ chối tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime_bulk', 'ten_hien_thi' => 'Duyệt hàng loạt tăng ca', 'nhom' => 'attendance'],

            // ❌ XÓA: attendance.overtime (không có route riêng)

            // ============================================
            // 9. TĂNG CA (CÁ NHÂN - EMPLOYEE) - Có route employee.tang-ca.*
            // ============================================
            ['name' => 'overtime.create', 'ten_hien_thi' => 'Tạo đơn tăng ca', 'nhom' => 'overtime'],
            ['name' => 'overtime.index', 'ten_hien_thi' => 'Xem danh sách tăng ca', 'nhom' => 'overtime'],
            ['name' => 'overtime.show', 'ten_hien_thi' => 'Xem chi tiết tăng ca', 'nhom' => 'overtime'],
            ['name' => 'overtime.huy', 'ten_hien_thi' => 'Hủy đơn tăng ca', 'nhom' => 'overtime'],

            // ============================================
            // 10. YÊU CẦU CHỈNH CÔNG (ADMIN) - Có route admin.yeu-cau-dieu-chinh-cong.*
            // ============================================
            ['name' => 'attendance.adjustment_approve', 'ten_hien_thi' => 'Duyệt chỉnh công', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment_reject', 'ten_hien_thi' => 'Từ chối chỉnh công', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment_bulk', 'ten_hien_thi' => 'Duyệt hàng loạt chỉnh công', 'nhom' => 'attendance'],

            // ============================================
            // 11. YÊU CẦU CHỈNH CÔNG (EMPLOYEE) - Có route employee.yeu-cau-chinh-cong.*
            // ============================================
            ['name' => 'adjustment.create', 'ten_hien_thi' => 'Tạo yêu cầu chỉnh công', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.index', 'ten_hien_thi' => 'Xem danh sách yêu cầu', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.show', 'ten_hien_thi' => 'Xem chi tiết yêu cầu', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.huy', 'ten_hien_thi' => 'Hủy yêu cầu chỉnh công', 'nhom' => 'adjustment'],
            ['name' => 'adjustment.download', 'ten_hien_thi' => 'Tải file đính kèm', 'nhom' => 'adjustment'],

            // ============================================
            // 12. LƯƠNG (QUẢN LÝ) - Có route admin.bang-luong.*
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

            // ============================================
            // 13. BẢNG LƯƠNG (CÁ NHÂN) - Có route employee.bang-luong.*
            // ============================================
            ['name' => 'payroll.index', 'ten_hien_thi' => 'Xem bảng lương cá nhân', 'nhom' => 'payroll'],
            ['name' => 'payroll.show', 'ten_hien_thi' => 'Xem chi tiết phiếu lương', 'nhom' => 'payroll'],

            // ============================================
            // 14. PHỤ CẤP - Có route admin.phu-cap.*
            // ============================================
            ['name' => 'salary.allowance', 'ten_hien_thi' => 'Quản lý phụ cấp', 'nhom' => 'salary'],
            ['name' => 'allowance.index', 'ten_hien_thi' => 'Xem danh sách phụ cấp', 'nhom' => 'allowance'],
            ['name' => 'allowance.create', 'ten_hien_thi' => 'Thêm phụ cấp', 'nhom' => 'allowance'],
            ['name' => 'allowance.edit', 'ten_hien_thi' => 'Sửa phụ cấp', 'nhom' => 'allowance'],
            ['name' => 'allowance.delete', 'ten_hien_thi' => 'Xóa phụ cấp', 'nhom' => 'allowance'],

            // ============================================
            // 15. HỢP ĐỒNG (QUẢN LÝ) - Có route admin.hop-dong.*
            // ============================================
            ['name' => 'contract.index', 'ten_hien_thi' => 'Xem danh sách hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.show', 'ten_hien_thi' => 'Xem chi tiết hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.create', 'ten_hien_thi' => 'Tạo hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.edit', 'ten_hien_thi' => 'Sửa hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.delete', 'ten_hien_thi' => 'Xóa hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.export', 'ten_hien_thi' => 'Xuất hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.gui_ky', 'ten_hien_thi' => 'Gửi ký hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.huy', 'ten_hien_thi' => 'Hủy hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.tai_ky', 'ten_hien_thi' => 'Tải lại hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.cua_toi', 'ten_hien_thi' => 'Xem hợp đồng của tôi', 'nhom' => 'contract'],
            ['name' => 'contract.luu_tru', 'ten_hien_thi' => 'Xem hợp đồng lưu trữ', 'nhom' => 'contract'],
            ['name' => 'contract.thong_ke', 'ten_hien_thi' => 'Xem thống kê hợp đồng', 'nhom' => 'contract'],

            // ============================================
            // 16. HỢP ĐỒNG (CÁ NHÂN) - Có route employee.hop-dong-cua-toi.*
            // ============================================
            ['name' => 'contract.personal', 'ten_hien_thi' => 'Xem hợp đồng cá nhân', 'nhom' => 'contract'],
            ['name' => 'contract.personal_update', 'ten_hien_thi' => 'Cập nhật trạng thái ký', 'nhom' => 'contract'],
            ['name' => 'contract.personal_reject', 'ten_hien_thi' => 'Từ chối ký hợp đồng', 'nhom' => 'contract'],

            // ============================================
            // 17. TUYỂN DỤNG - Có route admin.tin-tuyen-dung.*
            // ============================================
            ['name' => 'recruitment.index', 'ten_hien_thi' => 'Xem danh sách tuyển dụng', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.show', 'ten_hien_thi' => 'Xem chi tiết tin tuyển dụng', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.create', 'ten_hien_thi' => 'Đăng tin tuyển dụng', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.edit', 'ten_hien_thi' => 'Sửa tin tuyển dụng', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.delete', 'ten_hien_thi' => 'Xóa tin tuyển dụng', 'nhom' => 'recruitment'],

            // ============================================
            // 18. ỨNG VIÊN - Có route admin.ung-vien.*
            // ============================================
            ['name' => 'recruitment.candidate', 'ten_hien_thi' => 'Xem danh sách ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_show', 'ten_hien_thi' => 'Xem chi tiết ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_create', 'ten_hien_thi' => 'Thêm ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_edit', 'ten_hien_thi' => 'Sửa ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_delete', 'ten_hien_thi' => 'Xóa ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_archive', 'ten_hien_thi' => 'Lưu trữ ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_restore', 'ten_hien_thi' => 'Khôi phục ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_archived', 'ten_hien_thi' => 'Xem ứng viên đã lưu trữ', 'nhom' => 'recruitment'],

            // ============================================
            // 19. EMAIL ỨNG VIÊN - Có route admin.ung-vien.email.*
            // ============================================
            ['name' => 'recruitment.email', 'ten_hien_thi' => 'Gửi email cho ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.email_list', 'ten_hien_thi' => 'Xem danh sách email đã gửi', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.email_create', 'ten_hien_thi' => 'Tạo email phỏng vấn', 'nhom' => 'recruitment'],

            // ============================================
            // 20. TRÚNG TUYỂN - Có route admin.trung-tuyen.*
            // ============================================
            ['name' => 'recruitment.passed', 'ten_hien_thi' => 'Xử lý trúng tuyển', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.passed_index', 'ten_hien_thi' => 'Xem danh sách trúng tuyển', 'nhom' => 'recruitment'],

            // ============================================
            // 21. VAI TRÒ - Có route admin.vai-tro.*
            // ============================================
            ['name' => 'role.view', 'ten_hien_thi' => 'Xem danh sách vai trò', 'nhom' => 'role'],
            ['name' => 'role.create', 'ten_hien_thi' => 'Thêm vai trò', 'nhom' => 'role'],
            ['name' => 'role.edit', 'ten_hien_thi' => 'Sửa vai trò', 'nhom' => 'role'],
            ['name' => 'role.delete', 'ten_hien_thi' => 'Xóa vai trò', 'nhom' => 'role'],
            ['name' => 'role.permission', 'ten_hien_thi' => 'Phân quyền cho vai trò', 'nhom' => 'role'],

            // ============================================
            // 22. PHÂN QUYỀN - Có route admin.phan-quyen.*
            // ============================================
            ['name' => 'setting.permission', 'ten_hien_thi' => 'Phân quyền hệ thống', 'nhom' => 'setting'],
            ['name' => 'permission.index', 'ten_hien_thi' => 'Xem phân quyền', 'nhom' => 'permission'],
            ['name' => 'permission.edit', 'ten_hien_thi' => 'Sửa phân quyền', 'nhom' => 'permission'],
            ['name' => 'permission.update', 'ten_hien_thi' => 'Cập nhật phân quyền', 'nhom' => 'permission'],

            // ============================================
            // 23. LOẠI NGHỈ PHÉP - Có route admin.loai-nghi-phep.*
            // ============================================
            ['name' => 'leave_type.index', 'ten_hien_thi' => 'Xem danh sách loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.create', 'ten_hien_thi' => 'Thêm loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.edit', 'ten_hien_thi' => 'Sửa loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.delete', 'ten_hien_thi' => 'Xóa loại nghỉ phép', 'nhom' => 'leave_type'],

            // ============================================
            // 24. ĐƠN NGHỈ PHÉP (QUẢN LÝ) - Có route admin.don-nghi.*
            // ============================================
            ['name' => 'leave.index', 'ten_hien_thi' => 'Xem danh sách đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.show', 'ten_hien_thi' => 'Xem chi tiết đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.approve', 'ten_hien_thi' => 'Duyệt đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.reject', 'ten_hien_thi' => 'Từ chối đơn nghỉ', 'nhom' => 'leave'],
            ['name' => 'leave.bulk', 'ten_hien_thi' => 'Duyệt hàng loạt đơn nghỉ', 'nhom' => 'leave'],

            // ============================================
            // 25. ĐƠN NGHỈ PHÉP (CÁ NHÂN) - Có route employee.don-nghi.*
            // ============================================
            ['name' => 'leave.request', 'ten_hien_thi' => 'Tạo đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.history', 'ten_hien_thi' => 'Xem lịch sử nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.balance', 'ten_hien_thi' => 'Xem số dư phép', 'nhom' => 'leave'],
            ['name' => 'leave.cancel', 'ten_hien_thi' => 'Hủy đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.edit', 'ten_hien_thi' => 'Sửa đơn nghỉ phép', 'nhom' => 'leave'],

            // ============================================
            // 26. QUY ĐỊNH (ADMIN) - Có route admin.quy-dinh.*
            // ============================================
            ['name' => 'setting.general', 'ten_hien_thi' => 'Cài đặt chung', 'nhom' => 'setting'],
            ['name' => 'regulation.view', 'ten_hien_thi' => 'Xem quy định', 'nhom' => 'regulation'],
            ['name' => 'regulation.edit', 'ten_hien_thi' => 'Sửa quy định', 'nhom' => 'regulation'],
            ['name' => 'regulation.update', 'ten_hien_thi' => 'Cập nhật quy định', 'nhom' => 'regulation'],

            // ============================================
            // 27. QUẢN LÝ THỜI GIAN - Có route admin.quan-ly-thoi-gian.*
            // ============================================
            ['name' => 'time.index', 'ten_hien_thi' => 'Xem cấu hình thời gian', 'nhom' => 'time'],
            ['name' => 'time.update', 'ten_hien_thi' => 'Cập nhật cấu hình thời gian', 'nhom' => 'time'],

            // ============================================
            // 28. DUYỆT ĐƠN TUYỂN DỤNG - Có route admin.duyetdon.tuyendung.*
            // ============================================
            ['name' => 'approval.recruitment', 'ten_hien_thi' => 'Duyệt đơn tuyển dụng', 'nhom' => 'approval'],
            ['name' => 'approval.recruitment_show', 'ten_hien_thi' => 'Xem chi tiết đơn tuyển dụng', 'nhom' => 'approval'],
            ['name' => 'approval.recruitment_approve', 'ten_hien_thi' => 'Duyệt đơn tuyển dụng', 'nhom' => 'approval'],
            ['name' => 'approval.recruitment_reject', 'ten_hien_thi' => 'Từ chối đơn tuyển dụng', 'nhom' => 'approval'],

            // ============================================
            // 29. THÔNG BÁO - Có route admin.notifications.* và employee.notifications.*
            // ============================================
            ['name' => 'notification.view', 'ten_hien_thi' => 'Xem thông báo', 'nhom' => 'notification'],
            ['name' => 'notification.mark_read', 'ten_hien_thi' => 'Đánh dấu đã đọc', 'nhom' => 'notification'],
            ['name' => 'notification.mark_all_read', 'ten_hien_thi' => 'Đánh dấu tất cả đã đọc', 'nhom' => 'notification'],
            ['name' => 'notification.delete', 'ten_hien_thi' => 'Xóa thông báo', 'nhom' => 'notification'],

            // ❌ XÓA: notification.send (không có route gửi thông báo)

            // ============================================
            // 30. HỒ SƠ CÁ NHÂN (EMPLOYEE) - Có route employee.ho-so.*
            // ============================================
            ['name' => 'profile.view', 'ten_hien_thi' => 'Xem hồ sơ cá nhân', 'nhom' => 'profile'],
            ['name' => 'profile.update', 'ten_hien_thi' => 'Cập nhật hồ sơ', 'nhom' => 'profile'],
            ['name' => 'profile.change_password', 'ten_hien_thi' => 'Đổi mật khẩu', 'nhom' => 'profile'],

            // ============================================
            // 31. QUY ĐỊNH (EMPLOYEE) - Có route employee.quydinh.index
            // ============================================
            ['name' => 'regulation.employee', 'ten_hien_thi' => 'Xem quy định công ty', 'nhom' => 'regulation'],

            // ❌ XÓA TOÀN BỘ BÁO CÁO (không có route report.*)
            // ❌ XÓA TOÀN BỘ EMPLOYEE (đã gộp vào các nhóm trên)
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
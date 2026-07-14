<?php
// database/migrations/2026_07_14_000000_update_trang_thai_enum_in_thuc_hien_tang_ca_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra bảng tồn tại
        if (Schema::hasTable('thuc_hien_tang_ca')) {
            // Cập nhật enum với các giá trị mới
            DB::statement("ALTER TABLE thuc_hien_tang_ca MODIFY COLUMN trang_thai ENUM('chua_lam', 'dang_lam', 'hoan_thanh', 'khong_hoan_thanh', 'nhan_vien_xac_nhan', 'quan_ly_xac_nhan') DEFAULT 'chua_lam'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('thuc_hien_tang_ca')) {
            // Quay lại enum cũ
            DB::statement("ALTER TABLE thuc_hien_tang_ca MODIFY COLUMN trang_thai ENUM('chua_lam', 'dang_lam', 'hoan_thanh', 'khong_hoan_thanh') DEFAULT 'chua_lam'");
        }
    }
};
<?php
// database/migrations/2026_08_23_xxxxxx_fix_trang_thai_enum_in_thuc_hien_tang_ca.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bước 1: Thêm cột mới với ENUM đầy đủ
        Schema::table('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->enum('trang_thai_tmp', [
                'chua_lam',
                'dang_lam',
                'hoan_thanh',
                'khong_hoan_thanh',
                'nhan_vien_xac_nhan',
                'quan_ly_xac_nhan',
                'cho_xac_nhan_sua_chua',
                'tu_choi_sua_chua'
            ])->default('chua_lam')->nullable()->after('trang_thai');
        });

        // Bước 2: Copy dữ liệu từ cột cũ sang cột mới
        // Kiểm tra các giá trị hợp lệ, nếu không hợp lệ thì set default
        DB::statement("
            UPDATE thuc_hien_tang_ca 
            SET trang_thai_tmp = 
                CASE 
                    WHEN trang_thai IN ('chua_lam', 'dang_lam', 'hoan_thanh', 'khong_hoan_thanh', 'nhan_vien_xac_nhan', 'quan_ly_xac_nhan') 
                    THEN trang_thai 
                    ELSE 'chua_lam' 
                END
        ");

        // Bước 3: Xóa cột cũ
        Schema::table('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });

        // Bước 4: Đổi tên cột mới thành cột cũ
        Schema::table('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->renameColumn('trang_thai_tmp', 'trang_thai');
        });

        // Bước 5: Set NOT NULL và default
        Schema::table('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->enum('trang_thai', [
                'chua_lam',
                'dang_lam',
                'hoan_thanh',
                'khong_hoan_thanh',
                'nhan_vien_xac_nhan',
                'quan_ly_xac_nhan',
                'cho_xac_nhan_sua_chua',
                'tu_choi_sua_chua'
            ])->default('chua_lam')->change();
        });
    }

    public function down(): void
    {
        // Rollback: Tạo lại cột cũ
        Schema::table('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->enum('trang_thai_tmp', [
                'chua_lam',
                'dang_lam',
                'hoan_thanh',
                'khong_hoan_thanh',
                'nhan_vien_xac_nhan',
                'quan_ly_xac_nhan'
            ])->default('chua_lam')->nullable()->after('trang_thai');
        });

        // Copy dữ liệu
        DB::statement("
            UPDATE thuc_hien_tang_ca 
            SET trang_thai_tmp = 
                CASE 
                    WHEN trang_thai IN ('chua_lam', 'dang_lam', 'hoan_thanh', 'khong_hoan_thanh', 'nhan_vien_xac_nhan', 'quan_ly_xac_nhan') 
                    THEN trang_thai 
                    ELSE 'chua_lam' 
                END
        ");

        Schema::table('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
            $table->renameColumn('trang_thai_tmp', 'trang_thai');
        });
    }
};
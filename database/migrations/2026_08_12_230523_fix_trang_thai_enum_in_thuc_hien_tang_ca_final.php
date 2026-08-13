<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('thuc_hien_tang_ca')) {
            // ⭐ KIỂM TRA VÀ THÊM ENUM
            try {
                DB::statement("ALTER TABLE thuc_hien_tang_ca 
                    MODIFY COLUMN trang_thai ENUM('chua_xac_nhan', 'dang_thuc_hien', 'nhan_vien_xac_nhan', 'quan_ly_xac_nhan') 
                    DEFAULT 'chua_xac_nhan'");
            } catch (\Exception $e) {
                // Nếu lỗi, thử tạo lại bảng
                Schema::dropIfExists('thuc_hien_tang_ca');
                Schema::create('thuc_hien_tang_ca', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('dang_ky_tang_ca_id')->constrained('dang_ky_tang_ca')->onDelete('cascade');
                    $table->foreignId('nguoi_dung_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
                    $table->timestamp('thoi_gian_bat_dau')->nullable();
                    $table->timestamp('thoi_gian_ket_thuc')->nullable();
                    $table->decimal('so_gio_tang_ca_thuc_te', 4, 2)->default(0);
                    $table->enum('trang_thai', ['chua_xac_nhan', 'dang_thuc_hien', 'nhan_vien_xac_nhan', 'quan_ly_xac_nhan'])->default('chua_xac_nhan');
                    $table->text('ghi_chu')->nullable();
                    $table->timestamps();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('thuc_hien_tang_ca');
    }
};
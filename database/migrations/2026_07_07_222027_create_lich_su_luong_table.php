<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lich_su_luong', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại
            $table->foreignId('nguoi_dung_id')
                ->constrained('nguoi_dung')
                ->onDelete('cascade');
            
            $table->foreignId('hop_dong_id')
                ->constrained('hop_dong_lao_dong')
                ->onDelete('cascade');
            
            // Thông tin lương cũ và mới
            $table->decimal('luong_cu', 15, 2)->default(0);
            $table->decimal('luong_moi', 15, 2)->default(0);
            $table->decimal('phu_cap_cu', 15, 2)->default(0);
            $table->decimal('phu_cap_moi', 15, 2)->default(0);
            
            // Thông tin thay đổi
            $table->date('ngay_ap_dung');
            $table->enum('loai', ['tang_luong', 'giam_luong', 'dieu_chinh'])->default('tang_luong');
            $table->string('ly_do', 255)->nullable();
            
            // Trạng thái duyệt
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet');
            $table->foreignId('nguoi_tao_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->foreignId('nguoi_duyet_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_duyet')->nullable();
            
            // Ghi chú
            $table->text('ghi_chu')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['nguoi_dung_id', 'ngay_ap_dung']);
            $table->index('trang_thai');
            $table->index('loai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_luong');
    }
};
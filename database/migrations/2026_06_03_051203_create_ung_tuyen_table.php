<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ung_tuyen', function (Blueprint $table) {
            $table->id();
            $table->string('ma_ung_tuyen', 20)->unique();
            $table->foreignId('tin_tuyen_dung_id')->constrained('tin_tuyen_dung')->cascadeOnDelete();
            $table->string('ten_ung_vien');
            $table->string('email');
            $table->string('so_dien_thoai');
            $table->string('kinh_nghiem')->nullable();
            $table->string('ky_nang')->nullable();
            $table->text('thu_gioi_thieu')->nullable();
            $table->string('tai_cv')->nullable();
            $table->decimal('diem_danh_gia', 5, 2)->nullable();
            $table->enum('trang_thai_pv', ['chua', 'da', 'huy'])->nullable();
            $table->decimal('diem_phong_van', 5, 2)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->enum('trang_thai', ['moi', 'dang_xu_ly', 'trung_tuyen', 'tu_choi'])->default('moi');
            $table->enum('trang_thai_email', ['chua_gui', 'da_gui'])->default('chua_gui');
            $table->enum('trang_thai_email_trungtuyen', ['chua_gui', 'da_gui'])->default('chua_gui');
            $table->text('ly_do')->nullable();
            $table->timestamp('ngay_cap_nhat')->nullable();
            $table->foreignId('nguoi_cap_nhat')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->foreignId('nguoi_cap_nhat_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->foreignId('nguoi_cap_nhat_cuoi_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->foreignId('vai_tro_id')->nullable()->constrained('vai_tro')->nullOnDelete();
            $table->foreignId('phong_ban_id')->nullable()->constrained('phong_ban')->nullOnDelete();
            $table->foreignId('chuc_vu_id')->nullable()->constrained('chuc_vu')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ung_tuyen');
    }
};
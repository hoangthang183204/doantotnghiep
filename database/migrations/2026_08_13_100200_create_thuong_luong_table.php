<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Chi tiết thưởng đã áp dụng cho 1 dòng lương (snapshot tại thời điểm tính lương).
 * Cùng vai trò với phu_cap_luong nhưng cho phần thưởng.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thuong_luong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('luong_nhan_vien_id')->constrained('luong_nhan_vien')->cascadeOnDelete();
            $table->foreignId('loai_thuong_id')->nullable()->constrained('loai_thuong')->nullOnDelete();
            $table->foreignId('thuong_nhan_vien_id')->nullable()->constrained('thuong_nhan_vien')->nullOnDelete();

            $table->string('ten');                                   // tên loại thưởng tại thời điểm chốt
            $table->enum('hinh_thuc', ['dinh_ky', 'mot_lan'])->default('mot_lan');
            $table->decimal('so_tien', 12, 2)->default(0);
            $table->boolean('chiu_thue')->default(true);
            $table->string('ghi_chu', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thuong_luong');
    }
};

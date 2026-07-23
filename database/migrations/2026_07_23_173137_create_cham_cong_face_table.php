<?php
// database/migrations/2026_xx_xx_create_cham_cong_face_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cham_cong_face', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoi_dung_id');
            $table->unsignedBigInteger('cham_cong_id')->nullable();
            $table->string('face_id');
            $table->float('confidence')->default(0)->comment('Độ tin cậy (0-1)');
            $table->string('image_path')->nullable()->comment('Đường dẫn ảnh chụp khuôn mặt khi chấm công');
            $table->enum('loai', ['check_in', 'check_out']);
            $table->enum('trang_thai', ['thanh_cong', 'that_bai', 'can_xac_nhan'])->default('thanh_cong');
            $table->string('ip_address')->nullable();
            $table->text('device_info')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('nguoi_dung_id')
                  ->references('id')
                  ->on('nguoi_dung')
                  ->onDelete('cascade');
                  
            $table->foreign('cham_cong_id')
                  ->references('id')
                  ->on('cham_cong')
                  ->onDelete('set null');
                  
            $table->index('nguoi_dung_id');
            $table->index('face_id');
            $table->index(['loai', 'trang_thai']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cham_cong_face');
    }
};
<?php
// database/migrations/2026_xx_xx_create_face_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoi_dung_id');
            $table->string('embedding_path')->comment('Đường dẫn lưu file vector đặc trưng');
            $table->string('image_path')->nullable()->comment('Đường dẫn ảnh khuôn mặt');
            $table->string('face_id')->unique()->comment('ID duy nhất cho khuôn mặt');
            $table->json('metadata')->nullable()->comment('Thông tin metadata');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->foreign('nguoi_dung_id')
                  ->references('id')
                  ->on('nguoi_dung')
                  ->onDelete('cascade');
                  
            $table->index('nguoi_dung_id');
            $table->index('face_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_data');
    }
};
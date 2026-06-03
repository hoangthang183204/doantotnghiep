<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tai_lieu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->foreignId('ung_vien_id')->nullable()->constrained('ung_vien')->nullOnDelete();
            $table->enum('loai_tai_lieu', ['cv', 'bang_cap', 'chung_chi', 'giay_to', 'khac']);
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->string('ten_file_goc');
            $table->string('duong_dan_file');
            $table->bigInteger('kich_thuoc_file')->nullable();
            $table->string('loai_mime')->nullable();
            $table->tinyInteger('bao_mat')->default(0);
            $table->date('ngay_het_han')->nullable();
            $table->foreignId('nguoi_tai_len_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_tai_len')->useCurrent();
            $table->enum('trang_thai', ['hoat_dong', 'da_xoa'])->default('hoat_dong');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tai_lieu');
    }
};
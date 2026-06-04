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
            $table->enum('loai_tai_lieu', ['cv', 'bang_cap', 'chung_chi', 'hop_dong', 'khac']);
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->string('ten_file_goc');
            $table->string('duong_dan_file');
            $table->bigInteger('kich_thuoc_file');
            $table->string('loai_mime');
            $table->boolean('bao_mat')->default(false);
            $table->date('ngay_het_han')->nullable();
            $table->foreignId('nguoi_tai_len_id')->constrained('nguoi_dung');
            $table->timestamp('thoi_gian_tai_len');
            $table->enum('trang_thai', ['dang_xu_ly', 'hop_le', 'khong_hop_le'])->default('dang_xu_ly');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tai_lieu');
    }
};
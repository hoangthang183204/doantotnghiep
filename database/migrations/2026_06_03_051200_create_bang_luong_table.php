<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bang_luong', function (Blueprint $table) {
            $table->id();
            $table->string('ma_bang_luong')->unique();
            $table->enum('loai_bang_luong', ['thang', 'quy', 'nam'])->default('thang');
            $table->year('nam');
            $table->tinyInteger('thang')->nullable();
            $table->enum('trang_thai', ['dang_tao', 'cho_duyet', 'da_duyet', 'da_khoa'])->default('dang_tao');
            $table->foreignId('nguoi_xu_ly_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_xu_ly')->nullable();
            $table->foreignId('nguoi_phe_duyet_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_phe_duyet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bang_luong');
    }
};
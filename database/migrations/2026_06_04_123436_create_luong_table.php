<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('luong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('hop_dong_lao_dong_id')->constrained('hop_dong_lao_dong');
            $table->decimal('luong_co_ban', 15, 2)->default(0);
            $table->decimal('phu_cap', 15, 2)->default(0);
            $table->decimal('tien_thuong', 15, 2)->default(0);
            $table->decimal('tien_phat', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luong');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_phu_thuoc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ho_so_id')->constrained('ho_so_nguoi_dung')->onDelete('cascade');
            $table->string('ho_ten');
            $table->date('ngay_sinh');
            $table->enum('quan_he', ['con', 'vo', 'chong', 'cha', 'me', 'khac'])->default('con');
            $table->string('ma_so_thue')->nullable();
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc')->nullable();
            $table->string('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_phu_thuoc');
    }
};
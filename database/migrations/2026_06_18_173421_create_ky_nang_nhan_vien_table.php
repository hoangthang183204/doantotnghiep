<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ky_nang_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ho_so_id')->constrained('ho_so_nguoi_dung')->onDelete('cascade');
            $table->string('ten_ky_nang');
            $table->enum('cap_do', ['Cơ bản', 'Trung cấp', 'Thành thạo', 'Chuyên gia'])->default('Trung cấp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ky_nang_nhan_vien');
    }
};
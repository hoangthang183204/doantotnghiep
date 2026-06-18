<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('du_an_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ho_so_id')->constrained('ho_so_nguoi_dung')->onDelete('cascade');
            $table->string('ten_du_an');
            $table->string('vai_tro');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc')->nullable();
            $table->text('mo_ta')->nullable();
            $table->enum('trang_thai', ['Đang thực hiện', 'Hoàn thành', 'Tạm dừng'])->default('Hoàn thành');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('du_an_nhan_vien');
    }
};
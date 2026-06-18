<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khen_thuong_ky_luat_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ho_so_id')->constrained('ho_so_nguoi_dung')->onDelete('cascade');
            $table->enum('loai', ['khen_thuong', 'ky_luat']);
            $table->string('ten');
            $table->date('ngay');
            $table->text('noi_dung')->nullable();
            $table->string('hinh_thuc')->nullable();
            $table->decimal('so_tien', 15, 2)->nullable();
            $table->string('quyet_dinh_so')->nullable();
            $table->foreignId('nguoi_ky_id')->nullable()->constrained('nguoi_dung')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khen_thuong_ky_luat_nhan_vien');
    }
};
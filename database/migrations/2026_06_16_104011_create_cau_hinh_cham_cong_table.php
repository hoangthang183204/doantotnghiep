<?php
// database/migrations/2026_06_16_000000_create_cau_hinh_cham_cong_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cau_hinh_cham_cong', function (Blueprint $table) {
            $table->id();
            $table->string('ten')->comment('Tên cấu hình');
            $table->enum('loai', ['ip', 'wifi', 'mac'])->comment('Loại cấu hình');
            $table->string('gia_tri')->comment('Giá trị IP/WiFi/MAC');
            $table->text('mo_ta')->nullable()->comment('Mô tả');
            $table->unsignedBigInteger('chi_nhanh_id')->nullable()->comment('Chi nhánh áp dụng');
            $table->boolean('trang_thai')->default(1)->comment('1: Hoạt động, 0: Tạm dừng');
            $table->timestamps();
            
            $table->foreign('chi_nhanh_id')->references('id')->on('chi_nhanh_cong_ty')->onDelete('set null');
            
            $table->index(['loai', 'trang_thai']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cau_hinh_cham_cong');
    }
};
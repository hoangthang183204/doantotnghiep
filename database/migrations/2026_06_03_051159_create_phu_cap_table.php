<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phu_cap', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ma')->unique();
            $table->text('mo_ta')->nullable();
            $table->enum('loai_phu_cap', ['co_dinh', 'theo_luong', 'theo_cong'])->default('co_dinh');
            $table->decimal('so_tien_mac_dinh', 15, 2)->default(0);
            $table->enum('cach_tinh', ['so_tien_co_dinh', 'phan_tram_luong'])->default('so_tien_co_dinh');
            $table->tinyInteger('chiu_thue')->default(1);
            $table->json('dieu_kien_ap_dung')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phu_cap');
    }
};
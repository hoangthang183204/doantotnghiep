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
            $table->enum('loai_phu_cap', ['co_dinh', 'theo_cap_bac', 'theo_hieu_suat']);
            $table->decimal('so_tien_mac_dinh', 12, 2);
            $table->enum('cach_tinh', ['so_tien_co_dinh', 'phan_tram_luong_cb']);
            $table->boolean('chiu_thue')->default(true);
            $table->json('dieu_kien_ap_dung')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phu_cap');
    }
};
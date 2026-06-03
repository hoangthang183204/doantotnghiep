<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_cham_cong_ip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cham_cong_id')->constrained('cham_cong')->cascadeOnDelete();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung');
            $table->enum('hanh_dong', ['check_in', 'check_out']);
            $table->string('dia_chi_ip', 45)->nullable();
            $table->string('ten_wifi')->nullable();
            $table->string('dia_chi_mac')->nullable();
            $table->string('ten_thiet_bi')->nullable();
            $table->text('trinh_duyet_thiet_bi')->nullable();
            $table->tinyInteger('duoc_phep')->default(1);
            $table->enum('phuong_thuc_cham_cong', ['ip', 'wifi', 'mac', 'manual'])->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_cham_cong_ip');
    }
};
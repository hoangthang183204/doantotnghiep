<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cham_cong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->date('ngay_cham_cong');
            $table->time('gio_vao')->nullable();
            $table->time('gio_ra')->nullable();
            $table->decimal('so_gio_lam', 5, 2)->default(0);
            $table->decimal('so_cong', 5, 2)->default(0);
            $table->decimal('gio_tang_ca', 5, 2)->default(0);
            $table->smallInteger('phut_di_muon')->default(0);
            $table->smallInteger('phut_ve_som')->default(0);
            $table->enum('trang_thai', ['dung_gio', 'di_muon', 've_som', 'khong_cham_cong'])->default('dung_gio');
            $table->string('dia_chi_ip', 45)->nullable();
            $table->string('ten_wifi')->nullable();
            $table->string('dia_chi_mac')->nullable();
            $table->string('ten_thiet_bi')->nullable();
            $table->enum('phuong_thuc_cham_cong', ['ip', 'wifi', 'mac', 'manual'])->nullable();
            $table->text('ghi_chu')->nullable();
            $table->foreignId('nguoi_phe_duyet_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->tinyInteger('trang_thai_duyet')->default(0);
            $table->string('ghi_chu_duyet')->nullable();
            $table->timestamp('thoi_gian_phe_duyet')->nullable();
            $table->timestamps();
            
            $table->unique(['nguoi_dung_id', 'ngay_cham_cong']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cham_cong');
    }
};
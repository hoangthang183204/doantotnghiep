<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loai_nghi_phep', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ma')->unique();
            $table->text('mo_ta')->nullable();
            $table->integer('so_ngay_nam')->default(12);
            $table->integer('toi_da_ngay_lien_tiep')->default(30);
            $table->tinyInteger('so_ngay_bao_truoc')->default(1);
            $table->boolean('cho_phep_chuyen_nam')->default(false);
            $table->tinyInteger('toi_da_ngay_chuyen')->default(0);
            $table->enum('gioi_tinh_ap_dung', ['tat_ca', 'nam', 'nu'])->default('tat_ca');
            $table->boolean('yeu_cau_giay_to')->default(false);
            $table->boolean('co_luong')->default(true);
            $table->boolean('trang_thai')->default(true);
            $table->boolean('tinh_theo_ty_le')->default(false);
            $table->boolean('nghi_che_do')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_nghi_phep');
    }
};
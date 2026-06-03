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
            $table->tinyInteger('cho_phep_chuyen_nam')->default(1);
            $table->tinyInteger('toi_da_ngay_chuyen')->default(5);
            $table->enum('gioi_tinh_ap_dung', ['tat_ca', 'nam', 'nu'])->default('tat_ca');
            $table->tinyInteger('yeu_cau_giay_to')->default(0);
            $table->tinyInteger('co_luong')->default(1);
            $table->tinyInteger('trang_thai')->default(1);
            $table->tinyInteger('tinh_theo_ty_le')->default(0);
            $table->tinyInteger('nghi_che_do')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_nghi_phep');
    }
};
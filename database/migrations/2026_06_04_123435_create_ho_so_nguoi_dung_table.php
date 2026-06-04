<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ho_so_nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->unique()->constrained('nguoi_dung')->cascadeOnDelete();
            $table->string('ma_nhan_vien')->unique();
            $table->string('ho');
            $table->string('ten');
            $table->string('email_cong_ty')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->nullable();
            $table->text('dia_chi_hien_tai')->nullable();
            $table->text('dia_chi_thuong_tru')->nullable();
            $table->string('cmnd_cccd')->unique()->nullable();
            $table->string('so_ho_chieu')->nullable();
            $table->enum('tinh_trang_hon_nhan', ['doc_than', 'da_ket_hon', 'ly_hon', 'goa'])->nullable();
            $table->string('anh_dai_dien')->nullable();
            $table->string('lien_he_khan_cap')->nullable();
            $table->string('sdt_khan_cap')->nullable();
            $table->string('quan_he_khan_cap')->nullable();
            $table->string('anh_cccd_truoc')->nullable();
            $table->string('anh_cccd_sau')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ho_so_nguoi_dung');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gio_lam_viec', function (Blueprint $table) {
            $table->id();
            $table->time('gio_bat_dau')->default('08:30:00');
            $table->time('gio_ket_thuc')->default('17:30:00');
            $table->decimal('gio_nghi_trua', 3, 1)->default(1.0);
            $table->integer('so_phut_cho_phep_di_tre')->default(15);
            $table->integer('so_phut_cho_phep_ve_som')->default(15);
            $table->time('gio_bat_dau_tang_ca')->default('18:30:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gio_lam_viec');
    }
};
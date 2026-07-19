// database/migrations/xxxx_xx_xx_create_ca_lam_viec_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ca_lam_viec', function (Blueprint $table) {
            $table->id();
            $table->string('ten')->unique(); // 'Sáng', 'Chiều', 'Hành chính'
            $table->string('ma')->unique(); // 'SANG', 'CHIEU', 'HANH_CHINH'
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->decimal('so_gio_lam_viec', 4, 1)->default(4.0);
            $table->time('gio_bat_dau_tang_ca')->nullable();
            $table->integer('so_phut_cho_phep_di_tre')->default(15);
            $table->integer('so_phut_cho_phep_ve_som')->default(15);
            $table->boolean('is_default')->default(false);
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ca_lam_viec');
    }
};
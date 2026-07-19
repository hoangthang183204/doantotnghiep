// database/migrations/xxxx_xx_xx_add_ca_lam_viec_id_to_cham_cong_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            $table->foreignId('ca_lam_viec_id')->nullable()->constrained('ca_lam_viec')->nullOnDelete();
            $table->enum('loai_cham_cong', ['check_in', 'check_out'])->default('check_in');
            $table->text('ly_do_ve_som')->nullable();
            $table->boolean('da_xac_nhan_ve_som')->default(false);
            
            // Index cho truy vấn nhanh
            $table->index(['nguoi_dung_id', 'ca_lam_viec_id', 'loai_cham_cong']);
        });
    }

    public function down()
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            $table->dropForeign(['ca_lam_viec_id']);
            $table->dropColumn(['ca_lam_viec_id', 'loai_cham_cong', 'ly_do_ve_som', 'da_xac_nhan_ve_som']);
        });
    }
};
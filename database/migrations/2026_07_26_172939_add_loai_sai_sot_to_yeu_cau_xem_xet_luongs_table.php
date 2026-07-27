<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('yeu_cau_xem_xet_luongs', function (Blueprint $table) {

    $table->enum('loai_sai_sot', [
    'cham_cong',
    'tang_ca',
    'phu_cap',
    'khau_tru',
    'khac'
]);

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yeu_cau_xem_xet_luongs', function (Blueprint $table) {
            $table->dropColumn('loai_sai_sot');
        });
    }
};

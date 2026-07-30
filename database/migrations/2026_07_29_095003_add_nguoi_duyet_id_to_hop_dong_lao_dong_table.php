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
    Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
        $table->foreign('nguoi_duyet_id')->references('id')->on('nguoi_dung')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
        $table->dropForeign(['nguoi_duyet_id']);
        $table->dropColumn('nguoi_duyet_id');
    });
}
};

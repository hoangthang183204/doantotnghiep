<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            if (!Schema::hasColumn('ung_vien', 'cv_path')) {
                $table->string('cv_path')->nullable()->after('trang_thai');
            }
            if (!Schema::hasColumn('ung_vien', 'ghi_chu')) {
                $table->text('ghi_chu')->nullable()->after('cv_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            if (Schema::hasColumn('ung_vien', 'cv_path')) {
                $table->dropColumn('cv_path');
            }
            if (Schema::hasColumn('ung_vien', 'ghi_chu')) {
                $table->dropColumn('ghi_chu');
            }
        });
    }
};
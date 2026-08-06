<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuyTacToLoaiNghiPhepTable extends Migration
{
    public function up()
    {
        Schema::table('loai_nghi_phep', function (Blueprint $table) {
            $table->text('quy_tac')->nullable()->after('co_luong');
        });
    }

    public function down()
    {
        Schema::table('loai_nghi_phep', function (Blueprint $table) {
            $table->dropColumn('quy_tac');
        });
    }
}
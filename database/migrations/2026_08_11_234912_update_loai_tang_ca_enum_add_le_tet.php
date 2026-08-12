<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Nếu dùng ENUM trong MySQL
        DB::statement("ALTER TABLE dang_ky_tang_ca MODIFY COLUMN loai_tang_ca ENUM('ngay_thuong', 'ngay_nghi', 'le_tet') DEFAULT 'ngay_thuong'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE dang_ky_tang_ca MODIFY COLUMN loai_tang_ca ENUM('ngay_thuong', 'ngay_nghi') DEFAULT 'ngay_thuong'");
    }
};
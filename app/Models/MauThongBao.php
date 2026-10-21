<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MauThongBao extends Model
{
    protected $table = 'mau_thong_bao';
    protected $fillable = ['ma', 'ten', 'tieu_de', 'noi_dung'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quyen extends Model
{
    protected $table = 'quyen';
    
    protected $fillable = ['name', 'ten_hien_thi', 'nhom', 'mo_ta'];
    
    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'vai_tro_quyen', 'quyen_id', 'vai_tro_id');
    }
}
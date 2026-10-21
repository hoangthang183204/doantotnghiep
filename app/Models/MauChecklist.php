<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MauChecklist extends Model
{
    protected $table = 'mau_checklist';
    protected $fillable = ['ten', 'loai'];

    public function hangMucs()
    {
        return $this->hasMany(HangMucChecklist::class, 'mau_checklist_id')->orderBy('thu_tu');
    }
}

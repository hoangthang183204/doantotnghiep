<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HangMucChecklist extends Model
{
    protected $table = 'hang_muc_checklist';
    protected $fillable = ['mau_checklist_id', 'ten', 'thu_tu'];
}

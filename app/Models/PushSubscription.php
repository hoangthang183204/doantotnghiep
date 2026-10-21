<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $table = 'push_subscriptions';
    protected $fillable = ['nguoi_dung_id', 'endpoint', 'p256dh', 'auth'];
}

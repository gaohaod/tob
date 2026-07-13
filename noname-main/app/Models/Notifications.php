<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'userId',
        'type',
        'icon',
        'content',
        'created_at',
        'updated_at',
    ];
}

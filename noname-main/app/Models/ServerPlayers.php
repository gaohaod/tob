<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerPlayers extends Model
{
    use HasFactory;

    protected $table = 'server_players';

    protected $fillable = [
        'id',
        'userId',
        'placeId',
        'jobId',
        'created_at',
        'updated_at',
    ];
}

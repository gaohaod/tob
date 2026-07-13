<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTickets extends Model
{
    use HasFactory;
    /*
            $table->id();
            $table->string('token');
            $table->bigInteger('placeId');
            $table->integer('year');
            $table->integer('port');
            $table->timestamps();
    */

    protected $table = 'game_tickets';

    protected $fillable = [
        'id',
        'token',
        'placeId',
        'userId',
        'year',
        'port',
        'created_at',
        'updated_at',
    ];
}

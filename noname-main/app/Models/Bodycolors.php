<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodycolors extends Model
{
    use HasFactory;
    protected $table = 'bodycolors';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'userId',
        'head',
        'torso',
        'larm',
        'rarm',
        'lleg',
        'releg',
    ];
}

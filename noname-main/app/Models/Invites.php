<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invites extends Model
{
    use HasFactory;
    protected $table = 'invites';

    protected $fillable = [
        'id',
        'key',
        'used',
        'created_at',
        'updated_at',
    ];
}

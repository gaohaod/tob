<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadges extends Model
{
    use HasFactory;
    protected $table = 'user_badges';

    protected $fillable = [
        'id',
        'userId',
        'text',
        'color',
        'created_at',
        'updated_at'
    ];
}

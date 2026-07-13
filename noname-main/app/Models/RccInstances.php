<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RccInstances extends Model
{
    use HasFactory;
    protected $table = 'rcc_instances';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
    ];
}

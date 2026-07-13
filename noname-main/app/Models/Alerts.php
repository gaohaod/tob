<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerts extends Model
{
    /*
            $table->id();
            $table->string('color')->default('success');
            $table->text('content');
            $table->timestamps();
    */
    //
    protected $table = 'alerts';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'color',
        'content',
        'created_at',
        'updated_at',
    ];
}

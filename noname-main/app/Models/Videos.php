<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->bigInteger('creatorId');
            $table->timestamps();
*/

class Videos extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'id',
        'title',
        'description',
        'creatorId',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'creatorId');
    }
}

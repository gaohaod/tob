<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;
    /*
            $table->id();
            $table->string('subject');
            $table->string('body');
            $table->bigInteger('posterId');
            $table->boolean('banned')->default(false);
            $table->integer('category');
            $table->boolean('pinned')->default(false);
            $table->timestamps();
    */

    protected $table = 'forum';

    protected $fillable = [
        'id',
        'subject',
        'body',
        'votes',
        'locked',
        'posterId',
        'banned',
        'category',
        'pinned',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'posterId');
    }

    public function replies()
    {
        return $this->hasMany(Replies::class, 'replyPostId', 'id');
    }
    
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replies extends Model
{
    use HasFactory;
    /*
            $table->id();
            $table->string('reply');
            $table->bigInteger('posterId');
            $table->integer('replyPostId');
            $table->boolean('banned')->default(false);
            $table->timestamps();
    */
    protected $table = 'forum_replies';

    protected $fillable = [
        'id',
        'reply',
        'posterId',
        'replyPostId',
        'banned',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'posterId');
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id'); 
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    //


    protected $table = 'messaging';

    protected $fillable = [
        'id',
        'senderId',
        'recieverId',
        'content',
        'subject',
        'read',
        'moderated',
        'archived',
        'created_at',
        'updated_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderId');
    }

    public function reciever() {
        return $this->belongsTo(User::class, 'recieverId');
    }
}

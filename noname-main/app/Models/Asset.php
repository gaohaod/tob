<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $table = 'asset';

    protected $fillable = [
        'id',
        'name',
        'description',
        'playing',
        'visits',
        'featured',
        'peeps',
        'creator_id',
        'year',
        'type',
        'thumbnailUrl',
        'banned',
        'under_review',
        'ban_reason',
        'visible_in_toolbox',
        'private',
        'created_at',
        'updated_at',
        'gearsEnabled',
        'special',
        'for2012',
        'max_players',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function owned()
    {
        return $this->hasMany(Owned::class, 'itemId');
    }
}

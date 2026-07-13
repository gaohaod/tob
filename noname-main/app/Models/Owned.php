<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owned extends Model
{
    use HasFactory;

    /*
            $table->id();
            $table->bigInteger('userId');
            $table->bigInteger('itemId');
            $table->boolean('wearing')->default(false);
            $table->timestamps();
    */

    protected $table = 'owned';

    protected $fillable = [
        'id',
        'userId',
        'itemId',
        'wearing',
        'for2012',
        'created_at',
        'updated_at',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'itemId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}

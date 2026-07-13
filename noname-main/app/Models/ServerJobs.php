<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerJobs extends Model
{
    use HasFactory;

    /*
            $table->id();
            $table->bigInteger('placeId');
            $table->integer('status');
            $table->integer('port');
            $table->timestamps();
    */

    protected $table = 'server_jobs';

    protected $fillable = [
        'id',
        'placeId',
        'jobId',
        'status',
        'port',
        'created_at',
        'updated_at',
    ];
}

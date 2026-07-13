<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /*
    protected $fillable = [
        'id',
        'userId',
        'placeId',
        'userId',
        'created_at',
        'updated_at',
    ];
    */
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('server_players', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId');
            $table->bigInteger('placeId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('server_players');
    }
};

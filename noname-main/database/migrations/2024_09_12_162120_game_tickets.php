<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('game_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->bigInteger('placeId');
            $table->bigInteger('userId');
            $table->integer('year');
            $table->integer('port');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('game_tickets');
    }
};

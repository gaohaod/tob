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
        Schema::create('asset', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->default('This game has no description.');
            $table->bigInteger('max_players')->default(25);
            $table->bigInteger('playing')->default(0);
            $table->bigInteger('visits')->default(0);
            $table->bigInteger('peeps')->default(1);
            $table->bigInteger('creator_id');
            $table->integer('year');
            $table->enum('type', ['place', 'head', 'shirt', 'pants', 'tshirt', 'hat', 'face', 'model', 'audio', 'decal', 'gear', 'clothing']); //'place', 'clothing', 'hat', 'face', 'model', 'audio', 'decal'
            $table->string('thumbnailUrl');
            $table->boolean('banned')->default(false);
            $table->boolean('under_review')->default(false);
            $table->string('ban_reason')->nullable();
            $table->boolean('visible_in_toolbox')->default(false);
            $table->boolean('private')->default(false);
            $table->boolean('off_sale')->default(false);
            $table->boolean('special')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('asset');
    }
};

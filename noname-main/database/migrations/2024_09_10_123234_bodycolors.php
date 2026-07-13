<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bodycolors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId');
            $table->integer('head')->default(1);
            $table->integer('torso')->default(21);
            $table->integer('larm')->default(1);
            $table->integer('rarm')->default(1);
            $table->integer('lleg')->default(42);
            $table->integer('rleg')->default(42);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bodycolors');
    }
};

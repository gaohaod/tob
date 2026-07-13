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
        Schema::create('messaging', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('senderId');
            $table->bigInteger('recieverId');
            $table->text('content');
            $table->string('subject');
            $table->boolean('read');
            $table->boolean('moderated');
            $table->boolean('archived');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messaging');
    }
};

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
        Schema::table('asset', function(Blueprint $table) {
            $table->enum('type', ['place', 'head', 'shirt', 'pants', 'tshirt', 'hat', 'face', 'model', 'audio', 'decal', 'gear', 'clothing', 'script'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

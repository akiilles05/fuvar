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
        Schema::create('jarmu', function (Blueprint $table) {
            $table->id();
            $table->string('marka');
            $table->string('tipus');
            $table->string('rendszam');
            $table->foreignId('fuvarozo_id')->constrained('fuvarozo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jarmu');
    }
};

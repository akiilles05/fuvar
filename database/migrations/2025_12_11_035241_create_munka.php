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
        Schema::create('munka', function (Blueprint $table) {
            $table->id();
            $table->string('kiindulasi_cim');
            $table->string('erkezesi_cim');
            $table->string('cimzett_neve');
            $table->string('cimzett_telefonszama');
            $table->enum('statusz', ['kiosztva', 'folyamatban', 'elvegezve', 'sikertelen']);
            $table->foreignId('fuvarozo_id')->nullable()->constrained('fuvarozo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('munka');
    }
};

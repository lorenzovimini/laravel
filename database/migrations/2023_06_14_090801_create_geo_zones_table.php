<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('geo_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('active')->default(0);
            $table->timestamps();
        });

        Schema::create('geo_zone_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('geo_zone_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->boolean('active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_zone_zone');
        Schema::dropIfExists('geo_zones');
    }
};

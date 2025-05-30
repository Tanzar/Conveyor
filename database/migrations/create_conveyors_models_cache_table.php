<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConveyorStreamModels extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conveyors_models_cache', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conveyor_id');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->json('current_state');
            $table->timestamps();

            $table->foreign('conveyor_id')->references('id')->on('conveyors_cache');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conveyors_models_cache');
    }
};

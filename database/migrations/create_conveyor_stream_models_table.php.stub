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
        Schema::create('conveyor_stream_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conveyor_stream_id');
            $table->unsignedBigInteger('streamable_model_id');
            $table->string('streamable_model_class');
            $table->json('current_state');
            $table->timestamps();

            $table->foreign('conveyor_stream_id')->references('id')->on('convayor_streams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conveyor_stream_models');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConveyorCells extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conveyor_cells', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conveyor_frame_id');
            $table->string('key');
            $table->boolean('hidden');
            $table->float('value');
            $table->json('options');
            $table->timestamps();

            $table->foreign('conveyor_frame_id')
                ->references('id')
                ->on('conveyor_frames');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conveyors_cache');
    }
};

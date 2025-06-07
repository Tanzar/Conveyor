<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConveyorCellModels extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conveyor_cell_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conveyor_cell_id');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->float('value');
            $table->timestamps();

            $table->foreign('conveyor_cell_id')
                ->references('id')
                ->on('conveyor_cells');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conveyor_cell_models');
    }
};

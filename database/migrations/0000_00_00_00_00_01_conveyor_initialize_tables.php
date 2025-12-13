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
        Schema::create('conveyor_grinder_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('conveyor_params', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('param_value');
        });

        Schema::create('conveyor_cell_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('conveyor_cell_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('grinder_key_id');
            $table->bigInteger('cell_key_id');
            $table->float('cell_value');
            $table->timestamps();

            $table->foreign('grinder_key_id')
                ->references('id')
                ->on('conveyor_grinder_keys');

            $table->foreign('cell_key_id')
                ->references('id')
                ->on('conveyor_cell_keys');
        });

        Schema::create('conveyor_calculable_keys', function (Blueprint $table) {
            $table->id();
            $table->string('model_class_name');
        });

        Schema::create('conveyor_model_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calculable_id');
            $table->unsignedBigInteger('calculable_key_id');
            $table->bigInteger('cell_id');
            $table->float('cell_value');
            $table->timestamps();

            $table->foreign('calculable_key_id')
                ->references('id')
                ->on('conveyor_calculable_keys');

            $table->foreign('cell_id')
                ->references('id')
                ->on('conveyor_cell_values');
        });

        Schema::create('conveyor_cell_params', function (Blueprint $table) {
            $table->bigInteger('cell_id');
            $table->bigInteger('param_id');

            $table->foreign('cell_id')
                ->references('id')
                ->on('conveyor_cells');

            $table->foreign('param_id')
                ->references('id')
                ->on('conveyor_params');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conveyor_extractor_keys');
        Schema::dropIfExists('conveyor_variant_keys');
        Schema::dropIfExists('conveyor_cell_keys');
        Schema::dropIfExists('conveyor_cells');
        Schema::dropIfExists('conveyor_model_values');
    }
};

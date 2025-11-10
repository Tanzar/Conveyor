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
        Schema::create('conveyor_extractor_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('conveyor_variant_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('conveyor_cell_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('conveyor_cells', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conveyor_extractor_key_id');
            $table->bigInteger('conveyor_variant_key_id');
            $table->bigInteger('conveyor_cell_key_id');
            $table->float('cell_value');
            $table->timestamps();

            $table->foreign('conveyor_extractor_key_id')
                ->references('id')
                ->on('conveyor_extractor_keys');

            $table->foreign('conveyor_variant_key_id')
                ->references('id')
                ->on('conveyor_variant_keys');

            $table->foreign('conveyor_cell_key_id')
                ->references('id')
                ->on('conveyor_cell_keys');
        });

        Schema::create('conveyor_model_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calculable_id');
            $table->text('calculable_type');
            $table->bigInteger('conveyor_extractor_key_id');
            $table->bigInteger('conveyor_cell_key_id');
            $table->float('cell_value');
            $table->timestamps();

            $table->foreign('conveyor_extractor_key_id')
                ->references('id')
                ->on('conveyor_extractor_keys');

            $table->foreign('conveyor_cell_key_id')
                ->references('id')
                ->on('conveyor_cell_keys');
        });

        Schema::create('conveyor_cell_model_values', function (Blueprint $table) {
            $table->bigInteger('conveyor_cells_id');
            $table->bigInteger('conveyor_model_values_id');

            $table->foreign('conveyor_cells_id')
                ->references('id')
                ->on('conveyor_cells');

            $table->foreign('conveyor_model_values_id')
                ->references('id')
                ->on('conveyor_model_values');
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

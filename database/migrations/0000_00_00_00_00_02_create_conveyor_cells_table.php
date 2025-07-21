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
        Schema::create('conveyor_cells', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conveyor_frame_id');
            $table->text('key');
            $table->boolean('hidden')->default(false);
            $table->float('value')->default(0);
            $table->json('options')->default('{}');
            $table->json('models')->default('{}');
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
        Schema::dropIfExists('conveyor_cells');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('testing_foods', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('day');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testing_foods');
    }
};

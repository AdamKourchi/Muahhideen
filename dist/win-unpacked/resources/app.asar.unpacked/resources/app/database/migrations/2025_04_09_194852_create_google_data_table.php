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
        Schema::create('google_data', function (Blueprint $table) {
            $table->id();
            $table->string('male_api')->nullable();
            $table->string('male_token')->nullable();
            $table->string('female_api')->nullable();
            $table->string('female_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_data');
    }
};

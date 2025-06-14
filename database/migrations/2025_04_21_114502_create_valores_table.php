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
        Schema::create('valores', function (Blueprint $table) {
            $table->id();
            $table->string('first_title')->nullable();
            $table->string('second_title')->nullable();
            $table->string('third_title')->nullable();
            $table->longText('first_text')->nullable();
            $table->longText('second_text')->nullable();
            $table->longText('third_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valores');
    }
};

<?php

use App\Models\Producto;
use App\Models\User;
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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained('users')
                ->onDelete('cascade'); // Assuming you want to delete ofertas when the user is deleted
            $table->foreignIdFor(Producto::class)
                ->constrained('productos')
                ->onDelete('cascade'); // Assuming you want to delete ofertas when the product is deleted
            #fecha de finalizacion de la oferta
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};

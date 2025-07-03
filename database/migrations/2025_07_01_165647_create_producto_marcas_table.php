<?php

use App\Models\Categoria;
use App\Models\Producto;
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
        Schema::create('producto_marcas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Producto::class, 'producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();
            $table->foreignIdFor(Categoria::class, 'categoria_id')
                ->constrained('categorias')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_marcas');
    }
};

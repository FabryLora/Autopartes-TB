<?php

use App\Models\Producto;
use App\Models\SubCategoria;
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
        Schema::create('producto_modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Producto::class, 'producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();
            $table->foreignIdFor(SubCategoria::class, 'sub_categoria_id')
                ->constrained('sub_categorias')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_modelos');
    }
};

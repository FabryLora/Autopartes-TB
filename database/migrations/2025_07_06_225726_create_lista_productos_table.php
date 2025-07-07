<?php

use App\Models\ListaDePrecios;
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
        Schema::create('lista_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ListaDePrecios::class)
                ->constrained('lista_de_precios')
                ->onDelete('cascade'); // Elimina los productos si se elimina la lista de precios
            $table->foreignIdFor(Producto::class)->constrained('productos')->onDelete('cascade');
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_productos');
    }
};

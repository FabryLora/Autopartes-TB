<?php

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Productos;
use App\Models\SubProducto;
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
        Schema::create('pedido_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pedido::class, 'pedido_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Producto::class, 'producto_id')->constrained()->onDelete('cascade');
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->integer("cantidad");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};

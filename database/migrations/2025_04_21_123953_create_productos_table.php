<?php

use App\Models\Categoria;
use App\Models\ImagenProducto;
use App\Models\Marca;
use App\Models\MarcaProducto;
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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('order')->default("zzz");
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('code_oem')->nullable();
            $table->string('code_competitor')->nullable();
            $table->string('medida')->nullable();
            $table->foreignIdFor(Categoria::class, 'categoria_id')->nullable()
                ->constrained('categorias')
                ->cascadeOnDelete();
            $table->foreignIdFor(SubCategoria::class, 'sub_categoria_id')->nullable()
                ->constrained('sub_categorias')
                ->cascadeOnDelete();
            $table->longText('desc_visible')->nullable();
            $table->longText('desc_invisible')->nullable();
            $table->unsignedBigInteger('unidad_pack')->nullable();
            $table->string('familia')->nullable();
            $table->unsignedBigInteger('stock')->nullable();
            $table->unsignedInteger('descuento_oferta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

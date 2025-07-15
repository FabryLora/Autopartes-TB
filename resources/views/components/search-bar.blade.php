<!-- Script para inicializar los datos -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filtroSubcategorias', () => ({
            categoriaSeleccionada: '{{ request("id") }}',
            modeloSeleccionado: '{{ request("modelo_id") }}',
            subcategorias: @js($subcategorias),

            init() {
                // Resetear modelo si la categoría cambia
                this.$watch('categoriaSeleccionada', () => {
                    this.modeloSeleccionado = '';
                });
            },

            get subcategoriasFiltradas() {
                if (!this.categoriaSeleccionada) {
                    return this.subcategorias;
                }
                return this.subcategorias.filter(sub => sub.categoria_id == this.categoriaSeleccionada);
            }
        }));
    });
</script>

<div class="w-full bg-primary-orange" x-data="filtroSubcategorias">
    <form action="{{ route('productos') }}" method="GET"
        class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-2 w-[1200px] max-sm:w-full max-sm:px-4 mx-auto h-[123px] max-sm:h-auto max-sm:py-4 items-center">

        <select name="id" x-model="categoriaSeleccionada" class="h-[47px] max-sm:h-[40px] border bg-white w-full">
            <option value="">Marca</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
            @endforeach
        </select>

        <select name="modelo_id" x-model="modeloSeleccionado" class="h-[47px] max-sm:h-[40px] border bg-white w-full">
            <option value="">Modelo</option>
            <template x-for="subcategoria in subcategoriasFiltradas" :key="subcategoria.id">
                <option :value="subcategoria.id" x-text="subcategoria.name"></option>
            </template>
        </select>

        <input type="text" value="{{ request('medida') }}" name="medida" placeholder="Medida"
            class="h-[47px] max-sm:h-[40px] pl-2 border bg-white w-full placeholder:text-black" />

        <input type="text" value="{{ request('code') }}" name="code" placeholder="Código"
            class="h-[47px] max-sm:h-[40px] pl-2 border bg-white w-full placeholder:text-black" />

        <input type="text" value="{{ request('code_oem') }}" name="code_oem" placeholder="Cód. OEM"
            class="h-[47px] max-sm:h-[40px] pl-2 border bg-white w-full placeholder:text-black" />

        <input type="text" value="{{ request('desc') }}" name="desc" placeholder="Descripción"
            class="h-[47px] max-sm:h-[40px] pl-2 border bg-white w-full placeholder:text-black" />

        <button type="submit"
            class="border border-white text-white h-[47px] max-sm:h-[40px] w-full hover:bg-white hover:text-black transition duration-300">
            Buscar
        </button>
    </form>
</div>
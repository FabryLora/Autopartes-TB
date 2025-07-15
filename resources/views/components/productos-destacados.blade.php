<div class="mx-auto flex w-[1200px] max-sm:w-full max-sm:px-4 flex-col gap-5 my-10 max-sm:my-6">
    <div class="flex flex-row  max-sm:gap-3 items-center justify-between">
        <h2 class="text-[32px] max-sm:text-[24px] font-semibold">Productos destacados</h2>
        <a href="{{ url('/productos') }}"
            class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] max-sm:h-[36px] w-[127px] max-sm:w-[100px] items-center justify-center border text-base max-sm:text-sm font-semibold transition duration-300 hover:text-white">
            Ver todos
        </a>
    </div>

    <div class="flex flex-row max-sm:flex-col gap-5 max-sm:gap-4">
        @foreach ($productos as $producto)
            <a href="{{ "/p/" . $producto->code }}" class="border-gray-200 transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                        h-[349px] max-sm:h-auto flex flex-col w-[288px] max-sm:w-full">
                <div class="h-full flex flex-col">
                    @if ($producto->imagenes->count() > 0)
                        <img src="{{ $producto->imagenes->first()->image }}" alt="{{ $producto->name }}"
                            class="bg-gray-100 w-full min-h-[243px] max-sm:h-[200px] object-cover ">
                    @else
                        <div
                            class="w-full min-h-[243px] max-sm:min-h-[200px] bg-gray-100 flex items-center justify-center text-gray-500 ">
                            <span>Sin imagen</span>
                        </div>
                    @endif
                    <div class="flex flex-col justify-center h-full max-sm:p-3">
                        <h3
                            class="text-primary-orange group-hover:text-green-700 text-[16px] max-sm:text-[14px] transition-colors duration-300">
                            {{ $producto->code }}
                        </h3>
                        <p class="text-gray-800 max-sm:text-[14px] transition-colors duration-300 ">
                            {{ $producto->marcas->first()->name ?? 'Marca no disponible' }}
                        </p>
                        <p
                            class="text-gray-800 text-[15px] max-sm:text-[14px] font-semibold transition-colors duration-300 ">
                            {{ $producto->name }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
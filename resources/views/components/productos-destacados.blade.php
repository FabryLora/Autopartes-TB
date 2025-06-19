<div class="mx-auto flex w-[1200px] flex-col gap-5 my-10">
    <div class="flex flex-row items-center justify-between">
        <h2 class="text-[32px] font-semibold">Productos destacados</h2>
        <a href="{{ url('/productos') }}"
            class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] w-[127px] items-center justify-center border text-base font-semibold transition duration-300 hover:text-white">
            Ver todos
        </a>
    </div>

    <div class="flex flex-row gap-5">
        {{-- @foreach ($productos as $producto) --}}
        <a {{--
            href="/productos/{{ $producto->sub_categoria->categoria_id }}/{{ $producto->sub_categoria->id }}/{{ $producto->id }}"
            --}} class="flex h-[375px] w-[288px] flex-col rounded-md border group">
            <div class="relative flex min-h-[286px] items-end justify-center overflow-hidden rounded-t-md">
                <img {{-- src="{{ $producto->imagenes[0]->image ?? '' }}" --}} alt=""
                    class="h-full w-full rounded-t-md object-cover transition duration-300 group-hover:scale-105" />
                <div class="bg-primary-color absolute -bottom-[2px] h-[2px] w-[25px]"></div>
            </div>
            <div class="flex h-full flex-col items-center justify-center">
                <h3 class="text-primary-color text-[14px] font-semibold uppercase">{{-- {{
                    $producto->sub_categoria->title }} --}}
                </h3>
                <h2 class="text-[18px] text-black">{{-- {{ $producto->name }} --}}</h2>
            </div>
        </a>
        {{-- @endforeach --}}
    </div>
</div>
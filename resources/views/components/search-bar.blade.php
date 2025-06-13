<div class="w-full bg-primary-orange">
    <form {{-- action="{{ route('productos.buscar') }}" --}} method="GET"
        class="flex flex-row gap-4 w-[1200px] mx-auto  h-[123px] items-center">
        <select name="marca" class="h-[47px] border bg-white w-full">
            <option value="">Marca</option>
            {{-- @foreach($marcas as $marca)
            <option value="{{ $marca }}">{{ $marca }}</option>
            @endforeach --}}
        </select>

        <select name="modelo" class="h-[47px] border bg-white w-full">
            <option value="">Modelo</option>
            {{-- @foreach($modelos as $modelo)
            <option value="{{ $modelo }}">{{ $modelo }}</option>
            @endforeach --}}
        </select>

        <select name="medida" class="h-[47px] border bg-white w-full">
            <option value="">Medida</option>
            {{-- @foreach($medidas as $medida)
            <option value="{{ $medida }}">{{ $medida }}</option>
            @endforeach --}}
        </select>

        <input type="text" name="codigo" placeholder="Código" class="h-[47px] pl-2 border bg-white w-full" />
        <input type="text" name="oem" placeholder="Cód. OEM" class="h-[47px] pl-2 border bg-white w-full" />
        <input type="text" name="equivalente" placeholder="Cód. equivalente"
            class="h-[47px] pl-2 border bg-white w-full" />

        <button type="submit"
            class="border border-white text-white h-[47px] w-full hover:bg-white hover:text-black transition duration-300">
            Buscar
        </button>
    </form>
</div>
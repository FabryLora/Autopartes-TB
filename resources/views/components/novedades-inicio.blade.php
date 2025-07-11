<!-- resources/views/components/novedades-inicio.blade.php -->

<div class="w-full py-10 sm:py-16 md:py-20">

    <div class="mx-auto flex max-w-[1200px] flex-col gap-6 sm:gap-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-0">
            <h2 class="text-2xl font-bold sm:text-2xl md:text-3xl">Lanzamientos</h2>
            <a href="{{ url('/lanzamientos') }}"
                class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] w-[127px] items-center justify-center border text-base font-semibold transition duration-300 hover:text-white">
                Ver todas
            </a>
        </div>
        <div class="flex flex-row gap-6">
            @foreach($novedades as $novedad)
                <a href="{{ url('/lanzamientos/' . $novedad->id) }}" class="flex flex-col gap-2 max-w-[392px] h-[530px]">
                    <div class="max-w-[391px] min-h-[321px]">
                        <img src="{{ $novedad->image }}" alt="{{ $novedad->title }}" class="h-full w-full object-cover">
                    </div>
                    <div class="flex h-full flex-col justify-between">
                        <div class="flex flex-col gap-2">
                            <p class="text-primary-orange text-sm font-bold uppercase">{{ $novedad->type }}</p>
                            <div>
                                <p class="text-xl font-bold sm:text-2xl">{{ $novedad->title }}</p>
                                <div class="line-clamp-3 overflow-hidden break-words">
                                    {!! $novedad->text !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-row items-center justify-between">
                            <p class="font-bold">Leer más</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="#0072C6" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
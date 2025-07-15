<!-- resources/views/components/novedades-inicio.blade.php -->

<div class="w-full py-10 sm:py-16 md:py-20 max-sm:py-6">

    <div class="mx-auto flex max-w-[1200px] max-sm:max-w-full max-sm:px-4 flex-col gap-6 sm:gap-8 max-sm:gap-4">
        <div class="flex flex-row gap-4 sm:flex-row sm:items-center justify-between sm:gap-0 max-sm:gap-3">
            <h2 class="text-2xl font-bold sm:text-2xl md:text-3xl max-sm:text-xl">Lanzamientos</h2>
            <a href="{{ url('/lanzamientos') }}"
                class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] max-sm:h-[36px] w-[127px] max-sm:w-[100px] items-center justify-center border text-base max-sm:text-sm font-semibold transition duration-300 hover:text-white">
                Ver todas
            </a>
        </div>
        <div class="flex flex-row max-sm:flex-col gap-6 max-sm:gap-4">
            @foreach($novedades as $novedad)
                <a href="{{ url('/lanzamientos/' . $novedad->id) }}"
                    class="flex flex-col gap-2 max-w-[392px] max-sm:max-w-full h-[530px] max-sm:h-auto">
                    <div class="max-w-[391px] max-sm:max-w-full min-h-[321px] h-[200px]">
                        <img src="{{ $novedad->image }}" alt="{{ $novedad->title }}" class="h-full w-full object-cover">
                    </div>
                    <div class="flex h-full flex-col justify-between max-sm:p-3">
                        <div class="flex flex-col gap-2">
                            <p class="text-primary-orange text-sm max-sm:text-xs font-bold uppercase">{{ $novedad->type }}
                            </p>
                            <div>
                                <p class="text-xl font-bold sm:text-2xl max-sm:text-lg">{{ $novedad->title }}</p>
                                <div class="line-clamp-3 overflow-hidden break-words max-sm:text-sm">
                                    {!! $novedad->text !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 max-sm:mt-3 flex flex-row items-center justify-between">
                            <p class="font-bold max-sm:text-sm">Leer más</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                class="max-sm:w-4 max-sm:h-4">
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
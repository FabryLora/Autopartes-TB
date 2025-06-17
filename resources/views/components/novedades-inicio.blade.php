<!-- resources/views/components/novedades-inicio.blade.php -->

<div class="w-full py-10 sm:py-16 md:py-20">

    <div class="mx-auto flex max-w-[1200px] flex-col gap-6 px-4 sm:gap-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-0">
            <h2 class="text-2xl font-bold sm:text-2xl md:text-3xl">Novedades</h2>
            <a href="{{ route('novedades.index') }}"
                class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] w-[127px] items-center justify-center border text-base font-semibold transition duration-300 hover:text-white">
                Ver todas
            </a>
        </div>
        <div class="flex flex-col gap-6 md:flex-row md:justify-between flex-wrap">
            @foreach($novedades as $novedad)
                <a href="{{ url('/novedades/' . $novedad->id) }}"
                    class="flex h-auto w-full flex-col gap-2 border border-gray-300 sm:h-[480px] md:h-[520px] md:max-w-[calc(33.33%-16px)] lg:h-[540px]">
                    <div class="aspect-[16/9] w-full sm:aspect-[392/300] sm:max-h-[240px] md:max-h-[300px]">
                        <img src="{{ $novedad->image }}" alt="{{ $novedad->title }}" class="h-full w-full object-cover">
                    </div>
                    <div class="flex h-full flex-col justify-between p-3">
                        <div class="flex flex-col gap-2">
                            <p class="text-primary-orange text-sm font-bold uppercase">{{ $novedad->type }}</p>
                            <div>
                                <p class="text-xl font-bold sm:text-2xl">{{ $novedad->title }}</p>
                                <div>
                                    {!! \Illuminate\Support\Str::limit(strip_tags($novedad->text), 120, '...') !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-row items-center justify-between">
                            <p class="font-bold">Leer más</p>
                            <i class="fas fa-arrow-right text-lg"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
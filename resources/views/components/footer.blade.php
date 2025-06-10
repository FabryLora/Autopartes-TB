{{-- resources/views/components/footer.blade.php --}}
<div class="flex h-fit w-full flex-col bg-[#4D565D]">
    <div
        class="mx-auto flex h-full w-full max-w-[1200px] flex-col items-center justify-between gap-10 px-4 py-10 lg:flex-row lg:items-start lg:gap-0 lg:px-0 lg:py-26">
        {{-- Logo y redes sociales --}}
        <div class="flex h-full flex-col items-center gap-4">
            <a href="/">
                <img src="{{ $logos->logo_principal ?? '' }}" alt="Logo secundario"
                    class="max-w-[200px] sm:max-w-full" />
            </a>

            <div class="flex flex-row items-center justify-center gap-4 sm:gap-2">
                @if(!empty($contacto->fb))
                    <a target="_blank" rel="noopener noreferrer" href="{{ $contacto->fb }}" aria-label="Facebook">
                        <i class="fab fa-facebook-f text-[#E0E0E0] text-lg"></i>
                    </a>
                @endif
                @if(!empty($contacto->ig))
                    <a target="_blank" rel="noopener noreferrer" href="{{ $contacto->ig }}" aria-label="Instagram">
                        <i class="fab fa-instagram text-[#E0E0E0] text-lg"></i>
                    </a>
                @endif
            </div>
        </div>

        {{-- Secciones - Desktop/Tablet --}}
        <div class="hidden flex-col items-center gap-10 lg:flex">
            <h2 class="text-lg font-bold text-white">Secciones</h2>
            <div class="grid h-fit grid-flow-col grid-cols-2 grid-rows-3 gap-x-20 gap-y-3">
                {{-- <a href="{{ route('nosotros') }}" class="text-[15px] text-white/80">Nosotros</a>
                <a href="{{ route('productos') }}" class="text-[15px] text-white/80">Productos</a>
                <a href="{{ route('calidad') }}" class="text-[15px] text-white/80">Calidad</a>
                <a href="{{ route('novedades') }}" class="text-[15px] text-white/80">Novedades</a>
                <a href="{{ route('contacto') }}" class="text-[15px] text-white/80">Contacto</a> --}}
            </div>
        </div>

        {{-- Secciones - Mobile --}}
        <div class="flex flex-col items-center gap-6 sm:hidden">
            <h2 class="text-lg font-bold text-white">Secciones</h2>
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-4">
                {{-- <a href="{{ route('nosotros') }}" class="text-[15px] text-white/80">Nosotros</a>
                <a href="{{ route('productos') }}" class="text-[15px] text-white/80">Productos</a>
                <a href="{{ route('calidad') }}" class="text-[15px] text-white/80">Calidad</a>
                <a href="{{ route('novedades') }}" class="text-[15px] text-white/80">Novedades</a>
                <a href="{{ route('contacto') }}" class="text-[15px] text-white/80">Contacto</a> --}}
            </div>
        </div>

        {{-- Newsletter --}}
        <div class="flex h-full flex-col items-center gap-6 lg:items-start lg:gap-10">
            <h2 class="text-lg font-bold text-white">Suscribite al Newsletter</h2>
            <form {{-- action="{{ route('newsletter.subscribe') }}" --}} method="POST"
                class="flex h-[44px] w-full items-center justify-between border border-[#E0E0E0] px-3 sm:w-[287px]">
                @csrf
                <input name="email" type="email" required
                    class="w-full bg-transparent text-white/80 outline-none focus:outline-none placeholder-white/60"
                    placeholder="Email" />
                <button type="submit">
                    <i class="fas fa-arrow-right text-[#fb7f01]"></i>
                </button>
            </form>
        </div>

        {{-- Datos de contacto --}}
        <div class="flex h-full flex-col items-center gap-6 lg:items-start lg:gap-10">
            <h2 class="text-lg font-bold text-white">Datos de contacto</h2>
            <div class="flex flex-col justify-center gap-4">
                @if(!empty($contacto->location))
                    <a href="https://maps.google.com/?q={{ urlencode($contacto->location) }}" target="_blank"
                        rel="noopener noreferrer"
                        class="flex flex-row items-center gap-2 transition-opacity hover:opacity-80">
                        <i class="fas fa-location-dot text-[#fb7f01] text-lg"></i>
                        <p class="max-w-[250px] text-base break-words text-white/80">{{ $contacto->location }}</p>
                    </a>
                @endif

                @if(!empty($contacto->phone))
                    <a href="tel:{{ preg_replace('/\s/', '', $contacto->phone) }}"
                        class="flex flex-row items-center gap-2 transition-opacity hover:opacity-80">
                        <i class="fas fa-phone text-[#fb7f01] text-lg"></i>
                        <p class="max-w-[250px] text-base break-words text-white/80">{{ $contacto->phone }}</p>
                    </a>
                @endif

                @if(!empty($contacto->wp))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto->wp) }}" target="_blank"
                        rel="noopener noreferrer"
                        class="flex flex-row items-center gap-2 transition-opacity hover:opacity-80">
                        <i class="fab fa-whatsapp text-[#fb7f01] text-lg"></i>
                        <p class="max-w-[250px] text-base break-words text-white/80">{{ $contacto->wp }}</p>
                    </a>
                @endif

                @if(!empty($contacto->mail))
                    <a href="mailto:{{ $contacto->mail }}"
                        class="flex flex-row items-center gap-2 transition-opacity hover:opacity-80">
                        <i class="fas fa-envelope text-[#fb7f01] text-lg"></i>
                        <p class="max-w-[250px] text-base break-words text-white/80">{{ $contacto->mail }}</p>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div
        class="flex min-h-[67px] w-full flex-col items-center justify-center bg-[#4D565D] px-4 py-4 text-[14px] text-white/80 sm:flex-row sm:justify-between sm:px-6 lg:px-0">
        <div
            class="mx-auto flex w-full max-w-[1200px] flex-col items-center justify-center gap-2 text-center sm:flex-row sm:justify-between sm:gap-0 sm:text-left">
            <p>© Copyright {{ date('Y') }} SR Repuestos. Todos los derechos reservados</p>
            <a target="_blank" rel="noopener noreferrer" href="https://osole.com.ar/" class="mt-2 sm:mt-0">
                By <span class="font-bold">Osole</span>
            </a>
        </div>
    </div>
</div>

{{-- Incluir Font Awesome si no está ya incluido --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
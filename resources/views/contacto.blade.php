@extends('layouts.default')

@section('title', 'Contacto - Autopartes TB')

@push('head')
    <meta name="description" content="{{ $metadatos->description ?? '' }}">
    <meta name="keywords" content="{{ $metadatos->keywords ?? '' }}">

@endpush

@section('content')


    <div class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-10 md:gap-10 md:px-0 md:py-20">


        <div class="flex flex-col gap-8 md:flex-row md:gap-0">
            {{-- Contacto info --}}
            <div class="mb-6 flex w-full flex-col gap-4 md:mb-0 md:w-1/3">
                @php
                    $datos = [
                        [
                            'name' => $contacto->location ?? null,
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="23" viewBox="0 0 18 23" fill="none">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <path d="M17 9.26746C17 15.2675 9 21.2675 9 21.2675C9 21.2675 1 15.2675 1 9.26746C1 7.14572 1.84285 5.11089 3.34315 3.6106C4.84344 2.11031 6.87827 1.26746 9 1.26746C11.1217 1.26746 13.1566 2.11031 14.6569 3.6106C16.1571 5.11089 17 7.14572 17 9.26746Z" stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <path d="M9 12.2675C10.6569 12.2675 12 10.9243 12 9.26746C12 7.6106 10.6569 6.26746 9 6.26746C7.34315 6.26746 6 7.6106 6 9.26746C6 10.9243 7.34315 12.2675 9 12.2675Z" stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </svg>',
                            'href' => 'https://maps.google.com/?q=' . urlencode($contacto->location)
                        ],
                        [
                            'name' => $contacto->phone ?? null,
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <path d="M20.9994 15.9201V18.9201C21.0006 19.1986 20.9435 19.4743 20.832 19.7294C20.7204 19.9846 20.5567 20.2137 20.3515 20.402C20.1463 20.5902 19.904 20.7336 19.6402 20.8228C19.3764 20.912 19.0968 20.9452 18.8194 20.9201C15.7423 20.5857 12.7864 19.5342 10.1894 17.8501C7.77327 16.3148 5.72478 14.2663 4.18945 11.8501C2.49942 9.2413 1.44769 6.27109 1.11944 3.1801C1.09446 2.90356 1.12732 2.62486 1.21595 2.36172C1.30457 2.09859 1.44702 1.85679 1.63421 1.65172C1.82141 1.44665 2.04925 1.28281 2.30324 1.17062C2.55722 1.05843 2.83179 1.00036 3.10945 1.0001H6.10945C6.59475 0.995321 7.06524 1.16718 7.43321 1.48363C7.80118 1.80008 8.04152 2.23954 8.10944 2.7201C8.23607 3.68016 8.47089 4.62282 8.80945 5.5301C8.94399 5.88802 8.97311 6.27701 8.89335 6.65098C8.8136 7.02494 8.62831 7.36821 8.35944 7.6401L7.08945 8.9101C8.513 11.4136 10.5859 13.4865 13.0894 14.9101L14.3594 13.6401C14.6313 13.3712 14.9746 13.1859 15.3486 13.1062C15.7225 13.0264 16.1115 13.0556 16.4694 13.1901C17.3767 13.5286 18.3194 13.7635 19.2794 13.8901C19.7652 13.9586 20.2088 14.2033 20.526 14.5776C20.8431 14.9519 21.0116 15.4297 20.9994 15.9201Z" stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </svg>',
                            'href' => 'tel:' . preg_replace('/\s+/', '', $contacto->phone)
                        ],

                        [
                            'name' => $contacto->mail ?? null,
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <path d="M19 1H3C1.89543 1 1 1.89543 1 3V15C1 16.1046 1.89543 17 3 17H19C20.1046 17 21 16.1046 21 15V3C21 1.89543 20.1046 1 19 1Z" stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <path d="M21 4L12.03 9.7C11.7213 9.89343 11.3643 9.99601 11 9.99601C10.6357 9.99601 10.2787 9.89343 9.97 9.7L1 4" stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </svg>',
                            'href' => 'mailto:' . $contacto->mail ?? ''
                        ],
                    ];
                @endphp
                @foreach ($datos as $dato)
                    <a href="{{ $dato['href'] }}" target="_blank"
                        class="flex flex-row items-center gap-3 transition-opacity hover:opacity-80">
                        {!! $dato['icon'] !!}
                        <p class="text-base text-[#74716A]">{{ $dato['name'] }}</p>
                    </a>
                @endforeach
            </div>

            {{-- Formulario --}}
            <form method="POST" action="{{ route('send.contact') }}"
                class="grid w-full grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-x-5 sm:gap-y-10 md:w-2/3">
                @csrf
                <div class="flex flex-col gap-2 sm:gap-3">
                    <label for="name" class="text-base text-[#74716A]">Nombre y Apellido*</label>
                    <input required type="text" name="name" id="name" class="h-[44px] w-full border border-[#EEEEEE] pl-3"
                        value="{{ old('name') }}">
                    @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3">
                    <label for="email" class="text-base text-[#74716A]">Email*</label>
                    <input required type="email" name="email" id="email"
                        class="h-[44px] w-full border border-[#EEEEEE] pl-3" value="{{ old('email') }}">
                    @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3">
                    <label for="celular" class="text-base text-[#74716A]">Celular*</label>
                    <input required type="text" name="celular" id="celular"
                        class="h-[44px] w-full border border-[#EEEEEE] pl-3" value="{{ old('celular') }}">
                    @error('celular') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2 sm:gap-3">
                    <label for="empresa" class="text-base text-[#74716A]">Empresa</label>
                    <input required type="text" name="empresa" id="empresa"
                        class="h-[44px] w-full border border-[#EEEEEE] pl-3" value="{{ old('empresa') }}">
                    @error('empresa') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2  sm:gap-3">
                    <label for="mensaje" class="text-base text-[#74716A]">Mensaje</label>
                    <textarea required name="mensaje" id="mensaje"
                        class="h-[150px] w-full border border-[#EEEEEE] pt-2 pl-3">{{ $mensaje ? "Buenas tardes queria recibir informacion acerca de " . $mensaje : '' }}</textarea>
                    @error('mensaje') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col justify-end gap-3 ">


                    <p class="text-base text-[#74716A]">*Campos obligatorios</p>

                    <button type="submit"
                        class="bg-primary-orange text-bold min-h-[41px] w-full text-[16px] text-white">Enviar
                        consulta</button>
                </div>
            </form>
        </div>



        {{-- Mapa --}}
        <div class="mt-4 w-full">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.119561603928!2d-58.5374282!3d-34.6769318!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc896ab1ec9a1%3A0x1975d6d5259f8f6f!2sTeodoro%20Barth!5e0!3m2!1ses-419!2sar!4v1750333385714!5m2!1ses-419!2sar"
                class="w-full h-[500px]" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
@endsection
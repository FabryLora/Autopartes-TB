@extends('layouts.default')

@section('title', 'Autopartes TB')

@section('content')
    <div class="overflow-hidden">
        <div class="slider-track flex transition-transform duration-500 ease-in-out">
            @foreach ($sliders as $slider)
                @php $ext = pathinfo($slider->path, PATHINFO_EXTENSION); @endphp
                <div class="slider-item min-w-full relative h-[400px] sm:h-[500px] lg:h-[678px]">
                    <div class="absolute inset-0 bg-black z-0">
                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ asset($slider->path) }}" alt="Slider Image" class="w-full h-full object-cover"
                                data-duration="6000">
                        @elseif (in_array($ext, ['mp4', 'webm', 'ogg']))
                            <video class="w-full h-full object-cover" autoplay muted onended="nextSlide()">
                                <source src="{{ asset($slider->path) }}" type="video/{{ $ext }}">
                                {{ __('Tu navegador no soporta el formato de video.') }}
                            </video>
                        @endif
                    </div>
                    <div class="absolute inset-0 bg-black opacity-30 z-10"></div>
                    <div class="absolute inset-0 flex z-20 lg:max-w-[1224px] lg:mx-auto">
                        <div class="relative flex flex-col gap-4 sm:gap-6 lg:gap-19 w-full justify-center">
                            <div class="max-w-[320px] sm:max-w-[400px] lg:max-w-[480px] text-white">
                                <h1
                                    class="text-lg sm:text-xl md:text-3xl lg:text-5xl font-bold leading-tight sm:leading-normal lg:leading-14">
                                    {{ $slider->titulo }}
                                </h1>
                            </div>
                            <a href="{{ route('categorias') }}"
                                class="border border-white w-[180px] sm:w-[200px] lg:w-[230px] text-center py-2 sm:py-2.5 text-sm sm:text-base rounded-full hover:bg-white hover:text-black transition duration-300">Ver
                                productos</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Slider Navigation Dots -->
        <div class="relative lg:max-w-[1224px] lg:mx-auto">
            <div class="absolute bottom-4 sm:bottom-6 lg:bottom-30 w-full z-30">
                <div class="flex space-x-1 lg:space-x-2">
                    @foreach ($sliders as $i => $slider)
                        <button
                            class="cursor-pointer dot w-4 sm:w-6 lg:w-12 h-1 sm:h-1.5 rounded-none transition-colors duration-300 bg-white {{ $i === 0 ? 'opacity-90' : 'opacity-50' }}"
                            data-dot-index="{{ $i }}" onclick="goToSlide({{ $i }})"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Slider JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sliderTrack = document.querySelector('.slider-track');
            const sliderItems = document.querySelectorAll('.slider-item');
            const dots = document.querySelectorAll('.dot');
            let currentIndex = 0,
                autoSlideTimeout, isTransitioning = false;

            window.nextSlide = () => {
                if (isTransitioning) return;
                clearTimeout(autoSlideTimeout);
                currentIndex = (currentIndex + 1) % sliderItems.length;
                updateSlider();
            };
            window.goToSlide = i => {
                if (isTransitioning || i === currentIndex) return;
                clearTimeout(autoSlideTimeout);
                currentIndex = i;
                updateSlider();
            };

            function updateSlider() {
                isTransitioning = true;
                sliderItems.forEach(item => item.querySelector('video')?.pause());
                sliderTrack.style.transform = translateX(-${ currentIndex * 100} %);
                dots.forEach((dot, i) => dot.classList.toggle('opacity-90', i === currentIndex) || dot.classList
                    .toggle('opacity-50', i !== currentIndex));
                scheduleNextSlide();
                setTimeout(() => isTransitioning = false, 500);
            }

            function scheduleNextSlide() {
                clearTimeout(autoSlideTimeout);
                const slide = sliderItems[currentIndex],
                    video = slide.querySelector('video'),
                    img = slide.querySelector('img');
                if (video) {
                    video.currentTime = 0;
                    video.play();
                } else autoSlideTimeout = setTimeout(window.nextSlide, img?.dataset.duration ? +img.dataset
                    .duration : 6000);
            }
            sliderItems.forEach(item => item.querySelector('video') && (item.querySelector('video').onended = window
                .nextSlide));
            updateSlider();
        });
    </script>
@endsection
import { router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';

const Slider = () => {
    const [currentSlide, setCurrentSlide] = useState(0);
    const [isPaused, setIsPaused] = useState(false);

    const { productosOferta } = usePage().props;

    // Auto-advance configuration
    const SLIDE_DURATION = 5000; // 5 segundos por slide

    const goToSlide = (index) => {
        setCurrentSlide(index);
    };

    // Auto-advance effect
    useEffect(() => {
        if (!productosOferta.length || isPaused) return;

        const interval = setInterval(() => {
            setCurrentSlide((prev) => (prev + 1) % productosOferta.length);
        }, SLIDE_DURATION);

        return () => clearInterval(interval);
    }, [productosOferta.length, isPaused]);

    // Pause on hover
    const handleMouseEnter = () => {
        setIsPaused(true);
    };

    const handleMouseLeave = () => {
        setIsPaused(false);
    };

    if (!productosOferta.length) return null;

    const addtocart = (producto) => {
        console.log(producto);

        router.post(
            route('addtocart'),
            {
                id: producto.id,
                name: producto.name,
                qty: 1,
                price: producto?.precio?.precio,
            },
            {
                onSuccess: () => {
                    toast.success('Producto agregado al carrito');
                },
                onError: () => {
                    toast.error('Error al agregar el producto al carrito');
                },
            },
        );
    };

    return (
        <div
            className="relative mx-auto h-[232px] w-full max-sm:h-[180px]"
            style={{
                background: 'linear-gradient(135deg, #4a90e2 0%, #357abd 50%, #1e5f99 100%)',
                minHeight: '200px',
            }}
            onMouseEnter={handleMouseEnter}
            onMouseLeave={handleMouseLeave}
        >
            <div className="relative flex h-full justify-center overflow-hidden shadow-lg">
                <div
                    className="relative flex transition-transform duration-500 ease-in-out"
                    style={{ transform: `translateX(-${currentSlide * 100}%)` }}
                >
                    {productosOferta.map((slide, index) => (
                        <div key={index} className="relative w-full flex-shrink-0">
                            {/* Geometric Background Pattern */}
                            <div className="absolute inset-0 overflow-hidden">
                                <div className="absolute top-0 right-0 h-full w-1/2 max-sm:w-1/3">
                                    <div className="absolute top-0 right-0 h-full w-full opacity-20 max-sm:opacity-10">
                                        <div className="absolute top-4 right-4 h-32 w-32 rotate-45 border-2 border-white/30 max-sm:top-2 max-sm:right-2 max-sm:h-16 max-sm:w-16"></div>
                                        <div className="absolute top-12 right-12 h-24 w-24 rotate-45 border-2 border-white/20 max-sm:top-6 max-sm:right-6 max-sm:h-12 max-sm:w-12"></div>
                                        <div className="absolute top-20 right-20 h-16 w-16 rotate-45 border-2 border-white/10 max-sm:top-10 max-sm:right-10 max-sm:h-8 max-sm:w-8"></div>
                                    </div>
                                    <div className="absolute inset-0">
                                        <div className="absolute top-0 right-0 h-0.5 w-full origin-top-right rotate-45 transform bg-white/20"></div>
                                        <div className="absolute top-8 right-0 h-0.5 w-full origin-top-right rotate-45 transform bg-white/15"></div>
                                        <div className="absolute top-16 right-0 h-0.5 w-full origin-top-right rotate-45 transform bg-white/10"></div>
                                        <div className="absolute top-24 right-0 h-0.5 w-full origin-top-right rotate-45 transform bg-white/5"></div>
                                    </div>
                                </div>
                            </div>

                            {/* Content */}
                            <div className="relative z-10 mx-auto flex h-full w-[1200px] items-center justify-between max-sm:w-full max-sm:flex-col max-sm:px-4 max-sm:py-4 max-sm:text-center">
                                <div className="flex h-full flex-col justify-between pt-6 pb-4 text-white max-sm:h-auto max-sm:items-center max-sm:justify-center max-sm:pt-0 max-sm:pb-0">
                                    <div>
                                        <h2 className="mb-6 text-[32px] font-medium max-sm:mb-4 max-sm:text-[20px] max-sm:leading-tight">
                                            <span className="">{slide?.descuento_oferta}% </span>de descuento en {slide.name}
                                        </h2>
                                        <button
                                            onClick={() => addtocart(slide)}
                                            className="h-[41px] w-[163px] border border-white text-white max-sm:h-[36px] max-sm:w-[140px] max-sm:text-[14px]"
                                        >
                                            Agregar a carrito
                                        </button>
                                    </div>
                                </div>

                                <div className="ml-8 flex-shrink-0 max-sm:mt-4 max-sm:ml-0">
                                    <div className="flex h-32 w-32 items-center justify-center max-sm:h-20 max-sm:w-20">
                                        <img
                                            src={slide?.imagenes[0]?.image}
                                            alt={slide.name}
                                            className="h-full w-full object-contain drop-shadow-lg"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
                <div className="absolute bottom-0 z-10 mx-auto flex w-[1200px] space-x-2 pb-4 max-sm:w-full max-sm:justify-center max-sm:pb-2">
                    {productosOferta.map((_, index) => (
                        <button
                            key={index}
                            onClick={() => goToSlide(index)}
                            className={`h-[5px] w-[28px] transition-all duration-300 max-sm:h-[4px] max-sm:w-[20px] ${
                                index === currentSlide ? 'bg-white' : 'bg-gray-400 hover:bg-gray-300'
                            }`}
                        />
                    ))}
                </div>
            </div>
        </div>
    );
};

export default Slider;

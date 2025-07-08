import { useState } from 'react';

const Slider = ({
    slides = [
        { title: '5% de descuento en soporte', buttonText: 'Test' },
        { title: 'test', buttonText: 'Test' },
    ],
}) => {
    const [currentSlide, setCurrentSlide] = useState(0);

    const goToSlide = (index) => {
        setCurrentSlide(index);
    };

    if (!slides.length) return null;

    return (
        <div className="relative mx-auto h-[232px] w-full">
            <div className="relative overflow-hidden shadow-lg">
                <div className="flex transition-transform duration-500 ease-in-out" style={{ transform: `translateX(-${currentSlide * 100}%)` }}>
                    {slides.map((slide, index) => (
                        <div
                            key={index}
                            className="relative w-full flex-shrink-0"
                            style={{
                                background: 'linear-gradient(135deg, #4a90e2 0%, #357abd 50%, #1e5f99 100%)',
                                minHeight: '200px',
                            }}
                        >
                            {/* Geometric Background Pattern */}
                            <div className="absolute inset-0 overflow-hidden">
                                <div className="absolute top-0 right-0 h-full w-1/2">
                                    <div className="absolute top-0 right-0 h-full w-full opacity-20">
                                        <div className="absolute top-4 right-4 h-32 w-32 rotate-45 border-2 border-white/30"></div>
                                        <div className="absolute top-12 right-12 h-24 w-24 rotate-45 border-2 border-white/20"></div>
                                        <div className="absolute top-20 right-20 h-16 w-16 rotate-45 border-2 border-white/10"></div>
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
                            <div className="relative z-10 mx-auto flex h-full w-[1200px] items-center justify-between">
                                <div className="flex h-full flex-col justify-between pt-6 pb-4 text-white">
                                    <div>
                                        <h2 className="mb-6 max-w-md text-[32px] font-medium">{slide.title}</h2>
                                        <button
                                            onClick={() => slide.action && slide.action()}
                                            className="h-[41px] w-[163px] border border-white text-white"
                                        >
                                            {slide.buttonText}
                                        </button>
                                    </div>

                                    {/* Dots Indicator - Inside container */}
                                    <div className="flex space-x-2">
                                        {slides.map((_, index) => (
                                            <button
                                                key={index}
                                                onClick={() => goToSlide(index)}
                                                className={`h-[5px] w-[28px] transition-all duration-300 ${
                                                    index === currentSlide ? 'bg-white' : 'bg-gray-400 hover:bg-gray-300'
                                                }`}
                                            />
                                        ))}
                                    </div>
                                </div>

                                <div className="ml-8 flex-shrink-0">
                                    <div className="flex h-32 w-32 items-center justify-center">
                                        <img src={slide.image} alt={slide.title} className="h-full w-full object-contain drop-shadow-lg" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default Slider;

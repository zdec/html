/**
 * Sliders Initialization
 * Inicializa los sliders de Swiper después de que los componentes se hayan cargado
 * Esto asegura que los sliders funcionen correctamente cuando se cargan dinámicamente
 */

(function() {
    'use strict';

    /**
     * Inicializar todos los sliders
     */
    function initSliders() {
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper no está disponible aún');
            return;
        }

        // Hero Slider
        const heroSliderElement = document.querySelector('.hero-slider.swiper-container');
        if (heroSliderElement && !heroSliderElement.swiper) {
            new Swiper('.hero-slider.swiper-container', {
                loop: true,
                speed: 2000,
                effect: "fade",
                autoplay: {
                    delay: 7000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.hero-slider .swiper-button-next',
                    prevEl: '.hero-slider .swiper-button-prev',
                },
                pagination: {
                    el: '.hero-slider .swiper-pagination',
                    clickable: true,
                }
            });
        }

        // Testimonial Slider
        const testimonialSliderElement = document.querySelector('.content-top.swiper-container');
        if (testimonialSliderElement && !testimonialSliderElement.swiper) {
            new Swiper('.content-top', {
                slidesPerView: 2,
                spaceBetween: 30,
                speed: 1500,
                loop: true,
                navigation: {
                    nextEl: ".content-top .swiper-button-next",
                    prevEl: ".content-top .swiper-button-prev",
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    478: {
                        slidesPerView: 1,
                    },
                    576: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 2,
                    },
                    1200: {
                        slidesPerView: 2,
                    },
                },
            });
        }

        // Brand Slider
        const brandSliderElement = document.querySelector('.brand-slider.swiper-container');
        if (brandSliderElement && !brandSliderElement.swiper) {
            new Swiper('.brand-slider.swiper-container', {
                slidesPerView: 4,
                speed: 1500,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    480: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 4,
                    },
                }
            });
        }
    }

    /**
     * Inicializar cuando los componentes se hayan cargado
     */
    function initOnComponentsLoaded() {
        // Esperar un poco para que los componentes se inserten en el DOM
        setTimeout(function() {
            initSliders();
        }, 300);
    }

    // Escuchar el evento de componentes cargados
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('componentsLoaded', initOnComponentsLoaded);
        
        // También intentar inicializar después de un delay adicional
        // por si el evento no se dispara
        setTimeout(initOnComponentsLoaded, 500);
    } else {
        // Si jQuery no está disponible, usar eventos nativos
        document.addEventListener('componentsLoaded', initOnComponentsLoaded);
        setTimeout(initOnComponentsLoaded, 500);
    }

    // También intentar inicializar cuando el DOM esté completamente listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initSliders, 1000);
        });
    } else {
        setTimeout(initSliders, 1000);
    }
})();

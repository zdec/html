/**
 * Hero/Intro Slider Component
 * Componente reutilizable para el slider principal de la página de inicio
 * Carga el HTML desde hero-slider.html, procesa data-bg-image e inicializa Swiper
 */
function loadHeroSlider() {
    // Ruta al archivo HTML del componente
    const heroSliderHTMLPath = 'assets/js/components/pages/index/hero-slider/hero-slider.html';
    
    // Función para insertar el HTML, procesar data-bg-image e inicializar Swiper
    function insertHTML(html) {
        // Insertar el hero slider después del header/offcanvas
        const header = document.querySelector('header');
        if (header) {
            header.insertAdjacentHTML('afterend', html);
        } else {
            // Si no hay header, insertar al inicio del main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('afterbegin', html);
            } else {
                console.error('No se encontró lugar para insertar el hero slider');
            }
        }
        
        // Procesar las imágenes de fondo (data-bg-image) después de insertar el HTML
        // Esto es necesario porque el código de main.js ya se ejecutó antes de que el componente se cargara
        setTimeout(function() {
            if (typeof jQuery !== 'undefined') {
                jQuery('[data-bg-image]').each(function() {
                    var $this = jQuery(this),
                        $image = $this.data('bg-image');
                    if ($image) {
                        $this.css('background-image', 'url(' + $image + ')');
                    }
                });
            } else {
                // Fallback sin jQuery
                const elements = document.querySelectorAll('[data-bg-image]');
                elements.forEach(function(element) {
                    const image = element.getAttribute('data-bg-image');
                    if (image) {
                        element.style.backgroundImage = 'url(' + image + ')';
                    }
                });
            }
        }, 100);
        
        // Inicializar el slider de Swiper después de que se inserte el HTML
        // El main.js se encargará de inicializarlo, pero lo hacemos aquí también por si acaso
        if (typeof Swiper !== 'undefined') {
            setTimeout(function() {
                const heroSlider = document.querySelector('.hero-slider.swiper-container');
                if (heroSlider && !heroSlider.swiper) {
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
            }, 200);
        } else {
            // Si Swiper no está disponible, disparar evento para que main.js lo inicialice después
            if (typeof jQuery !== 'undefined') {
                jQuery(document).on('componentsLoaded', function() {
                    setTimeout(function() {
                        if (typeof Swiper !== 'undefined') {
                            const heroSlider = document.querySelector('.hero-slider.swiper-container');
                            if (heroSlider && !heroSlider.swiper) {
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
                        }
                    }, 300);
                });
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(heroSliderHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar hero-slider.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Hero Slider:', error);
            console.error('Ruta intentada:', heroSliderHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/index/hero-slider/hero-slider.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar hero-slider.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Hero Slider cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar hero-slider desde ruta alternativa:', fallbackError);
                });
        });
}

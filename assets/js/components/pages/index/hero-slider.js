/**
 * Hero/Intro Slider Component
 * Componente reutilizable para el slider principal de la página de inicio
 */
function loadHeroSlider() {
    const heroSliderHTML = `
        <!-- Hero/Intro Slider Start -->
        <div class="section ">
            <div class="hero-slider swiper-container slider-nav-style-1 slider-dot-style-1">
                <!-- Hero slider Active -->
                <div class="swiper-wrapper">
                    <!-- Single slider item -->
                    <div class="hero-slide-item slider-height swiper-slide bg-color1" data-bg-image="assets/images/hero/bg/hero-bg-1.webp">
                        <div class="container h-100">
                            <div class="row h-100">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 align-self-center sm-center-view">
                                    <div class="hero-slide-content slider-animated-1">
                                        <span class="category">Bienvenido a IT Secur</span>
                                        <h2 class="title-1">Tu solucion <br>
                                        Dispositivos seguros y <br>
                                         los mejores precios </h2>
                                        <a href="#" realhref="shop-left-sidebar.html" class="btn btn-primary text-capitalize">Catalogo</a>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 d-flex justify-content-center position-relative align-items-end">
                                    <div class="show-case">
                                        <div class="hero-slide-image">
                                            <img src="assets/images/hero/inner-img/hero-1-1.png" alt="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single slider item -->
                    <div class="hero-slide-item slider-height swiper-slide bg-color1" data-bg-image="assets/images/hero/bg/hero-bg-1.webp">
                        <div class="container h-100">
                            <div class="row h-100">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 align-self-center sm-center-view">
                                    <div class="hero-slide-content slider-animated-1">
                                        <span class="category">Nuestra Meta</span>
                                        <h2 class="title-1">Tu Seguridad <br>
                                        Dispositivos seguros y <br>
                                         las mejores soluciones </h2>
                                        <a href="#" realhref="shop-left-sidebar.html" class="btn btn-primary text-capitalize">Catalogo</a>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 d-flex justify-content-center position-relative align-items-end">
                                    <div class="show-case">
                                        <div class="hero-slide-image">
                                            <img src="assets/images/hero/inner-img/hero-1-2.png" alt="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination swiper-pagination-white"></div>
                <!-- Add Arrows -->
                <div class="swiper-buttons">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
        <!-- Hero/Intro Slider End -->
    `;
    
    // Insertar el hero slider después del header/offcanvas
    const header = document.querySelector('header');
    if (header) {
        header.insertAdjacentHTML('afterend', heroSliderHTML);
    } else {
        // Si no hay header, insertar al inicio del main-wrapper
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('afterbegin', heroSliderHTML);
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

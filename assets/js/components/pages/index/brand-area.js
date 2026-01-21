/**
 * Brand Area Component
 * Componente reutilizable para el área de logos de marcas/partners
 */
function loadBrandArea() {
    const brandAreaHTML = `
        <!-- Brand area start -->
        <div class="brand-area pt-100px pb-100px">
            <div class="container">
                <div class="brand-slider swiper-container">
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide brand-slider-item text-center">
                            <a href="#"><img class=" img-fluid" src="assets/images/partner/1.png" alt="" /></a>
                        </div>
                        <div class="swiper-slide brand-slider-item text-center">
                            <a href="#"><img class=" img-fluid" src="assets/images/partner/2.png" alt="" /></a>
                        </div>
                        <div class="swiper-slide brand-slider-item text-center">
                            <a href="#"><img class=" img-fluid" src="assets/images/partner/3.png" alt="" /></a>
                        </div>
                        <div class="swiper-slide brand-slider-item text-center">
                            <a href="#"><img class=" img-fluid" src="assets/images/partner/4.png" alt="" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Brand area end -->
    `;
    
    // Insertar antes del footer
    const footer = document.querySelector('.footer-area');
    if (footer) {
        footer.insertAdjacentHTML('beforebegin', brandAreaHTML);
    } else {
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('beforeend', brandAreaHTML);
        } else {
            console.error('No se encontró lugar para insertar el brand area');
        }
    }
    
    // Inicializar el slider de Swiper después de que se inserte el HTML
    // El main.js se encargará de inicializarlo, pero lo hacemos aquí también por si acaso
    if (typeof Swiper !== 'undefined') {
        setTimeout(function() {
            const brandSlider = document.querySelector('.brand-slider.swiper-container');
            if (brandSlider && !brandSlider.swiper) {
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
        }, 200);
    }
}

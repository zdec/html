/**
 * Testimonial Area Component
 * Componente reutilizable para el área de testimonios de clientes
 */
function loadTestimonialArea() {
    const testimonialAreaHTML = `
        <!-- Testimonial area start -->
        <div class="trstimonial-area pt-100px pb-100px">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center m-0">
                            <h2 class="title">Comentarios de clientes</h2>
                            <p>Agradecemos a nuestros clientes por sus mensajes</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- Swiper -->
                        <div class="swiper-container content-top slider-nav-style-1">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testi-inner">
                                        <div class="testi-content">
                                            <p>
                                                Excelente servicio y productos de alta calidad. Compré un enrutador seguro y una solución de encriptación de datos para mi empresa, y el rendimiento ha sido impecable. La asesoría fue clara y muy profesional. Totalmente recomendados para quienes buscan seguridad y confianza.
                                            </p>
                                        </div>
                                        <div class="testi-author">
                                            <div class="author-image">
                                                <img class="img-responsive" src="assets/images/testimonial/1.png" alt="">
                                            </div>
                                            <div class="author-name">
                                                <h4 class="name">Juan Manuel Ospina<span>Cliente</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testi-inner">
                                        <div class="testi-content">
                                            <p>
                                                Adquirí un celular con enfoque en privacidad y un GPS para mi vehículo. Todo llegó a tiempo y perfectamente configurado. Se nota que manejan tecnología especializada y que saben lo que venden. Sin duda volveré a comprar.
                                            </p>
                                        </div>
                                        <div class="testi-author">
                                            <div class="author-image">
                                                <img class="img-responsive" src="assets/images/testimonial/2.png" alt="">
                                            </div>
                                            <div class="author-name">
                                                <h4 class="name">Elizabeth Ruuth<span>Client</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-buttons">
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial area end-->
    `;
    
    // Insertar antes del brand area o antes del footer
    const brandArea = document.querySelector('.brand-area');
    if (brandArea) {
        brandArea.insertAdjacentHTML('beforebegin', testimonialAreaHTML);
    } else {
        // Si no hay brand area, buscar el footer
        const footer = document.querySelector('.footer-area');
        if (footer) {
            footer.insertAdjacentHTML('beforebegin', testimonialAreaHTML);
        } else {
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', testimonialAreaHTML);
            } else {
                console.error('No se encontró lugar para insertar el testimonial area');
            }
        }
    }
    
    // Inicializar el slider de Swiper después de que se inserte el HTML
    // El main.js se encargará de inicializarlo, pero lo hacemos aquí también por si acaso
    if (typeof Swiper !== 'undefined') {
        setTimeout(function() {
            const testimonialSlider = document.querySelector('.content-top.swiper-container');
            if (testimonialSlider && !testimonialSlider.swiper) {
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
        }, 200);
    }
}

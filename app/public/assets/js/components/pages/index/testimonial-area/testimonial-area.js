/**
 * Testimonial Area Component
 * Componente reutilizable para el área de testimonios de clientes
 * Carga el HTML desde testimonial-area.html e inicializa el slider Swiper
 */
function loadTestimonialArea() {
    // Ruta al archivo HTML del componente
    const testimonialAreaHTMLPath = 'assets/js/components/pages/index/testimonial-area/testimonial-area.html';
    
    // Función para insertar el HTML e inicializar el slider
    function insertHTML(html) {
        // Insertar antes del brand area o antes del footer
        const brandArea = document.querySelector('.brand-area');
        if (brandArea) {
            brandArea.insertAdjacentHTML('beforebegin', html);
        } else {
            // Si no hay brand area, buscar el footer
            const footer = document.querySelector('.footer-area');
            if (footer) {
                footer.insertAdjacentHTML('beforebegin', html);
            } else {
                const mainWrapper = document.querySelector('.main-wrapper');
                if (mainWrapper) {
                    mainWrapper.insertAdjacentHTML('beforeend', html);
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
    
    // Cargar el HTML del componente usando fetch
    fetch(testimonialAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar testimonial-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Testimonial Area:', error);
            console.error('Ruta intentada:', testimonialAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/index/testimonial-area/testimonial-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar testimonial-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Testimonial Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar testimonial-area desde ruta alternativa:', fallbackError);
                });
        });
}

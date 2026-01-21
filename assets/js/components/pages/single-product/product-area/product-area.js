/**
 * Product Area Component
 * Componente reutilizable para el área de productos relacionados
 * Carga el HTML desde product-area.html e inicializa el slider Swiper
 */
function loadProductArea() {
    // Ruta al archivo HTML del componente
    const productAreaHTMLPath = 'assets/js/components/pages/single-product/product-area/product-area.html';
    
    // Función para insertar el HTML e inicializar el slider
    function insertHTML(html) {
        // Insertar antes del footer
        const footer = document.querySelector('.footer-area');
        if (footer) {
            footer.insertAdjacentHTML('beforebegin', html);
        } else {
            // Si no hay footer, insertar al final del main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
            } else {
                console.error('No se encontró lugar para insertar el product area');
            }
        }
        
        // Inicializar el slider de Swiper después de que se inserte el HTML
        // El main.js se encargará de inicializarlo, pero lo hacemos aquí también por si acaso
        if (typeof Swiper !== 'undefined') {
            setTimeout(function() {
                const productSlider = document.querySelector('.new-product-slider.swiper-container');
                if (productSlider && !productSlider.swiper) {
                    new Swiper('.new-product-slider.swiper-container', {
                        slidesPerView: 4,
                        spaceBetween: 30,
                        speed: 1500,
                        loop: true,
                        navigation: {
                            nextEl: ".new-product-slider .swiper-button-next",
                            prevEl: ".new-product-slider .swiper-button-prev",
                        },
                        breakpoints: {
                            0: {
                                slidesPerView: 1,
                            },
                            478: {
                                slidesPerView: 1,
                            },
                            576: {
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
    
    // Cargar el HTML del componente usando fetch
    fetch(productAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar product-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Product Area:', error);
            console.error('Ruta intentada:', productAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/single-product/product-area/product-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar product-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Product Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar product-area desde ruta alternativa:', fallbackError);
                });
        });
}

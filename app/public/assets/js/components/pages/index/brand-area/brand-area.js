/**
 * Brand Area Component
 * Componente reutilizable para el área de logos de marcas/partners
 * Carga el HTML desde brand-area.html e inicializa el slider Swiper
 */
function loadBrandArea() {
    // Ruta al archivo HTML del componente
    const brandAreaHTMLPath = 'assets/js/components/pages/index/brand-area/brand-area.html';
    
    // Función para insertar el HTML e inicializar el slider
    function insertHTML(html) {
        // Insertar antes del footer
        const footer = document.querySelector('.footer-area');
        if (footer) {
            footer.insertAdjacentHTML('beforebegin', html);
        } else {
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
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
    
    // Cargar el HTML del componente usando fetch
    fetch(brandAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar brand-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Brand Area:', error);
            console.error('Ruta intentada:', brandAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/index/brand-area/brand-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar brand-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Brand Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar brand-area desde ruta alternativa:', fallbackError);
                });
        });
}

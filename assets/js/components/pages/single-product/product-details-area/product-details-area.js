/**
 * Product Details Area Component
 * Componente reutilizable para el área de detalles del producto
 * Carga el HTML desde product-details-area.html e inicializa los sliders Swiper
 */
function loadProductDetailsArea() {
    // Ruta al archivo HTML del componente
    const productDetailsAreaHTMLPath = 'assets/js/components/pages/single-product/product-details-area/product-details-area.html';
    
    // Función para insertar el HTML e inicializar los sliders
    function insertHTML(html) {
        // Insertar después del breadcrumb
        const breadcrumb = document.querySelector('.breadcrumb-area');
        if (breadcrumb) {
            breadcrumb.insertAdjacentHTML('afterend', html);
        } else {
            // Si no hay breadcrumb, insertar después del header/offcanvas
            const header = document.querySelector('header');
            if (header) {
                header.insertAdjacentHTML('afterend', html);
            } else {
                const mainWrapper = document.querySelector('.main-wrapper');
                if (mainWrapper) {
                    mainWrapper.insertAdjacentHTML('afterbegin', html);
                } else {
                    console.error('No se encontró lugar para insertar el product details area');
                }
            }
        }
        
        // Inicializar los sliders de Swiper después de que se inserte el HTML
        // El main.js se encargará de inicializarlos, pero lo hacemos aquí también por si acaso
        if (typeof Swiper !== 'undefined') {
            setTimeout(function() {
                // Inicializar zoom-thumbs primero
                const zoomThumbElement = document.querySelector('.zoom-thumbs.swiper-container');
                if (zoomThumbElement && !zoomThumbElement.swiper) {
                    var zoomThumb = new Swiper('.zoom-thumbs', {
                        spaceBetween: 18,
                        slidesPerView: 3,
                        freeMode: true,
                        watchSlidesVisibility: true,
                        watchSlidesProgress: true,
                        navigation: {
                            nextEl: ".zoom-thumbs .swiper-button-next",
                            prevEl: ".zoom-thumbs .swiper-button-prev",
                        },
                    });
                    
                    // Inicializar zoom-top después, con referencia a zoom-thumbs
                    const zoomTopElement = document.querySelector('.zoom-top.swiper-container');
                    if (zoomTopElement && !zoomTopElement.swiper) {
                        var zoomTop = new Swiper('.zoom-top', {
                            spaceBetween: 0,
                            slidesPerView: 1,
                            effect: 'fade',
                            fadeEffect: {
                                crossFade: true,
                            },
                            thumbs: {
                                swiper: zoomThumb
                            }
                        });
                    }
                }
            }, 200);
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(productDetailsAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar product-details-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Product Details Area:', error);
            console.error('Ruta intentada:', productDetailsAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/single-product/product-details-area/product-details-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar product-details-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Product Details Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar product-details-area desde ruta alternativa:', fallbackError);
                });
        });
}

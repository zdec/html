/**
 * Index Product Area Component
 * Componente reutilizable para el área de productos con tabs (Novedades y Mas Vendidos) en index.html
 * Carga el HTML desde product-area.html
 */
function loadIndexProductArea() {
    // Ruta al archivo HTML del componente
    const productAreaHTMLPath = 'assets/js/components/pages/index/product-area/product-area.html';
    
    // Función para insertar el HTML
    function insertHTML(html) {
        // Insertar antes del fashion-area
        const fashionArea = document.querySelector('.fashion-area');
        if (fashionArea) {
            fashionArea.insertAdjacentHTML('beforebegin', html);
        } else {
            // Si no hay fashion-area, buscar brand-area como referencia
            const brandArea = document.querySelector('.brand-area');
            if (brandArea) {
                brandArea.insertAdjacentHTML('afterend', html);
            } else {
                // Si no hay brand-area, buscar testimonial-area
                const testimonialArea = document.querySelector('.testimonial-area');
                if (testimonialArea) {
                    testimonialArea.insertAdjacentHTML('afterend', html);
                } else {
                    // Si no hay testimonial-area, buscar banner-area
                    const bannerArea = document.querySelector('.banner-area');
                    if (bannerArea) {
                        bannerArea.insertAdjacentHTML('afterend', html);
                    } else {
                        // Último recurso: insertar al final del main-wrapper
                        const mainWrapper = document.querySelector('.main-wrapper');
                        if (mainWrapper) {
                            mainWrapper.insertAdjacentHTML('beforeend', html);
                        } else {
                            console.error('No se encontró lugar para insertar el product area');
                        }
                    }
                }
            }
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
            const fallbackPath = '/assets/js/components/pages/index/product-area/product-area.html';
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

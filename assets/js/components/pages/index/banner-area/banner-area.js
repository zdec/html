/**
 * Banner Area Component
 * Componente reutilizable para el área de banners promocionales
 * Carga el HTML desde banner-area.html
 */
function loadBannerArea() {
    // Ruta al archivo HTML del componente
    const bannerAreaHTMLPath = 'assets/js/components/pages/index/banner-area/banner-area.html';
    
    // Función para insertar el HTML
    function insertHTML(html) {
        // Insertar después del hero slider
        const heroSlider = document.querySelector('.hero-slider');
        if (heroSlider) {
            heroSlider.closest('.section').insertAdjacentHTML('afterend', html);
        } else {
            // Si no hay hero slider, buscar otro punto de referencia
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                const firstSection = mainWrapper.querySelector('.section, .banner-area, .product-area');
                if (firstSection) {
                    firstSection.insertAdjacentHTML('beforebegin', html);
                } else {
                    mainWrapper.insertAdjacentHTML('afterbegin', html);
                }
            } else {
                console.error('No se encontró lugar para insertar el banner area');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(bannerAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar banner-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
            // Actualizar los enlaces para incluir el parámetro index en la URL
            updateBannerLinks();
        })
        .catch(error => {
            console.error('Error al cargar el componente Banner Area:', error);
            console.error('Ruta intentada:', bannerAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/index/banner-area/banner-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar banner-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Banner Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar banner-area desde ruta alternativa:', fallbackError);
                });
        });
    
    /**
     * Actualiza los enlaces de los banners para incluir el parámetro index en la URL
     */
    function updateBannerLinks() {
        // Buscar todos los enlaces con data-product-index
        const bannerLinks = document.querySelectorAll('.banner-area a[data-product-index]');
        
        bannerLinks.forEach(link => {
            const productIndex = link.getAttribute('data-product-index');
            if (productIndex) {
                // Actualizar el href para incluir el parámetro index
                link.href = `single-product.html?index=${productIndex}`;
            }
        });
    }
}

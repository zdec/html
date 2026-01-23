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
        // Función auxiliar para intentar insertar después del hero slider
        function tryInsert() {
            // Buscar el hero slider
            const heroSlider = document.querySelector('.hero-slider');
            if (heroSlider) {
                // Encontrar el contenedor padre (section o div que contiene el hero-slider)
                const heroContainer = heroSlider.closest('.section') || heroSlider.parentElement;
                if (heroContainer) {
                    heroContainer.insertAdjacentHTML('afterend', html);
                    return true;
                }
            }
            return false;
        }
        
        // Intentar insertar inmediatamente
        if (tryInsert()) {
            return;
        }
        
        // Si no se encontró el hero slider, esperar y reintentar
        // Esto es necesario porque los componentes se cargan de forma asíncrona
        let attempts = 0;
        const maxAttempts = 30; // Intentar durante 3 segundos (30 * 100ms)
        
        const checkInterval = setInterval(function() {
            attempts++;
            if (tryInsert()) {
                clearInterval(checkInterval);
            } else if (attempts >= maxAttempts) {
                // Si después de varios intentos no se encuentra el hero slider,
                // insertar después del header como fallback seguro
                clearInterval(checkInterval);
                const header = document.querySelector('header');
                if (header) {
                    // Insertar después del header (el hero slider debería estar ahí)
                    header.insertAdjacentHTML('afterend', html);
                } else {
                    const mainWrapper = document.querySelector('.main-wrapper');
                    if (mainWrapper) {
                        // Buscar cualquier sección existente y insertar después
                        const firstSection = mainWrapper.querySelector('.section, .hero-slider, .fashion-area');
                        if (firstSection) {
                            firstSection.insertAdjacentHTML('afterend', html);
                        } else {
                            // Último recurso: insertar al inicio del main-wrapper
                            mainWrapper.insertAdjacentHTML('afterbegin', html);
                        }
                    } else {
                        console.error('No se encontró lugar para insertar el banner area');
                    }
                }
            }
        }, 100); // Revisar cada 100ms
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

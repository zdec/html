/**
 * Shop Page Component
 * Componente reutilizable para el área de catálogo de productos
 * Carga el HTML desde shop-page.html
 */
function loadShopPage() {
    // Ruta al archivo HTML del componente
    const shopPageHTMLPath = 'assets/js/components/pages/catalog/shop-page/shop-page.html';
    
    // Función para insertar el HTML
    function insertHTML(html) {
        // Insertar después del breadcrumb-area y antes del footer
        const breadcrumbArea = document.querySelector('.breadcrumb-area');
        if (breadcrumbArea) {
            breadcrumbArea.insertAdjacentHTML('afterend', html);
        } else {
            // Si no hay breadcrumb, insertar al inicio del main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
            } else {
                console.error('No se encontró lugar para insertar el shop page');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(shopPageHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar shop-page.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Shop Page:', error);
            console.error('Ruta intentada:', shopPageHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/catalog/shop-page/shop-page.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar shop-page.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Shop Page cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar shop-page desde ruta alternativa:', fallbackError);
                });
        });
}

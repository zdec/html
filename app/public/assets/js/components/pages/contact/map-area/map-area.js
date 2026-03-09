/**
 * Map Area Component
 * Componente reutilizable para el área del mapa de Google Maps
 * Carga el HTML desde map-area.html y reemplaza los placeholders con datos de SiteConfig
 */
function loadMapArea() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined') {
        console.warn('SiteConfig no está definido. Se usarán valores por defecto.');
    }
    
    // Ruta al archivo HTML del componente
    const mapAreaHTMLPath = 'assets/js/components/pages/contact/map-area/map-area.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Obtener datos de configuración
        const config = typeof SiteConfig !== 'undefined' ? SiteConfig : {};
        const contact = config.contact || {};
        const address = contact.city && contact.address 
            ? `${contact.city}, ${contact.address}` 
            : (contact.city || contact.address || "Medellin, Colombia Cra 32 # 77 S 371");
        
        // Codificar la dirección para la URL de Google Maps
        const mapAddress = encodeURIComponent(address);
        const mapUrl = `https://maps.google.com/maps?q=${mapAddress}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
        
        // Preparar los datos para reemplazar los placeholders
        const data = {
            mapUrl: mapUrl
        };
        
        // Reemplazar los placeholders con los datos
        let processedHTML = html;
        Object.keys(data).forEach(key => {
            const placeholder = new RegExp(`{{${key}}}`, 'g');
            processedHTML = processedHTML.replace(placeholder, data[key]);
        });
        
        // Insertar antes del footer o al final del main-wrapper
        const footer = document.querySelector('.footer-area');
        if (footer) {
            footer.insertAdjacentHTML('beforebegin', processedHTML);
        } else {
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', processedHTML);
            } else {
                console.error('No se encontró lugar para insertar el map area');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(mapAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar map-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Map Area:', error);
            console.error('Ruta intentada:', mapAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/contact/map-area/map-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar map-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Map Area cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar map-area desde ruta alternativa:', fallbackError);
                });
        });
}

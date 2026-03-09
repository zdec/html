/**
 * Contact Area Component
 * Componente reutilizable para el área de contacto con formulario e información
 * Carga el HTML desde contact-area.html y reemplaza los placeholders con datos de SiteConfig
 */
function loadContactArea() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined') {
        console.warn('SiteConfig no está definido. Se usarán valores por defecto.');
    }
    
    // Ruta al archivo HTML del componente
    const contactAreaHTMLPath = 'assets/js/components/pages/contact/contact-area/contact-area.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Obtener datos de configuración
        const config = typeof SiteConfig !== 'undefined' ? SiteConfig : {};
        const contact = config.contact || {};
        const address = contact.address && contact.city 
            ? `${contact.city}<br>${contact.address}` 
            : (contact.address || "Medellin, Colombia Cra 32 # 77 S 371");
        const phone = contact.phone || "+57 310 6707901";
        const email = contact.email || "ventas@itsecursas.co";
        const web = contact.website || "https://itsecursas.co/";
        
        // Preparar los datos para reemplazar los placeholders
        const data = {
            address: address,
            phone: phone,
            phoneClean: phone.replace(/\s/g, ''),
            email: email,
            web: web,
            webDisplay: web.replace(/^https?:\/\//, '')
        };
        
        // Reemplazar los placeholders con los datos
        let processedHTML = html;
        Object.keys(data).forEach(key => {
            const placeholder = new RegExp(`{{${key}}}`, 'g');
            processedHTML = processedHTML.replace(placeholder, data[key]);
        });
        
        // Insertar después del breadcrumb area
        const breadcrumbArea = document.querySelector('.breadcrumb-area');
        if (breadcrumbArea) {
            breadcrumbArea.insertAdjacentHTML('afterend', processedHTML);
        } else {
            // Si no hay breadcrumb, buscar otro punto de referencia
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                const firstSection = mainWrapper.querySelector('.section, .contact-area, .contact-map');
                if (firstSection) {
                    firstSection.insertAdjacentHTML('beforebegin', processedHTML);
                } else {
                    mainWrapper.insertAdjacentHTML('afterbegin', processedHTML);
                }
            } else {
                console.error('No se encontró lugar para insertar el contact area');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(contactAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar contact-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Contact Area:', error);
            console.error('Ruta intentada:', contactAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/contact/contact-area/contact-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar contact-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Contact Area cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar contact-area desde ruta alternativa:', fallbackError);
                });
        });
}

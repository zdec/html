/**
 * Offcanvas Components
 * Componentes reutilizables para los sidebars (wishlist, cart, mobile menu)
 * Carga el HTML desde offcanvas.html y reemplaza los placeholders con datos de SiteConfig
 */
function loadOffcanvas() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined') {
        console.error('SiteConfig no está definido. Asegúrate de cargar site-config.js primero.');
        return;
    }
    
    // Ruta al archivo HTML del componente
    const offcanvasHTMLPath = 'assets/js/components/offcanvas/offcanvas.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Preparar los datos para reemplazar los placeholders
        const data = {
            wishlistText: SiteConfig.texts.wishlistText || 'Me gusta',
            phone: SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '',
            phoneClean: (SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '').replace(/\s/g, ''),
            email: SiteConfig.contact.email || '',
            accountText: SiteConfig.texts.accountText || 'Mi Cuenta',
            menuItems: SiteConfig.menu ? SiteConfig.menu.map(item => 
                `<li><a href="${item.href}">${item.text}</a></li>`
            ).join('') : '',
            socialFacebook: SiteConfig.social.facebook || '#',
            socialTwitter: SiteConfig.social.twitter || '#',
            socialTumblr: SiteConfig.social.tumblr || '#',
            socialYoutube: SiteConfig.social.youtube || '#',
            socialInstagram: SiteConfig.social.instagram || '#'
        };
        
        // Reemplazar los placeholders con los datos
        let processedHTML = html;
        Object.keys(data).forEach(key => {
            const placeholder = new RegExp(`{{${key}}}`, 'g');
            processedHTML = processedHTML.replace(placeholder, data[key]);
        });
        
        // Insertar los offcanvas después del header
        const header = document.querySelector('header');
        if (header) {
            header.insertAdjacentHTML('afterend', processedHTML);
        } else {
            // Si no hay header, insertar al inicio del main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('afterbegin', processedHTML);
            } else {
                console.error('No se encontró lugar para insertar los offcanvas');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(offcanvasHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar offcanvas.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Offcanvas:', error);
            console.error('Ruta intentada:', offcanvasHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/offcanvas/offcanvas.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar offcanvas.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Offcanvas cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar offcanvas desde ruta alternativa:', fallbackError);
                    
                    // Mostrar mensaje de error claro
                    const mainWrapper = document.querySelector('.main-wrapper');
                    if (mainWrapper) {
                        mainWrapper.insertAdjacentHTML('afterbegin', 
                            '<div style="background: #ff9800; color: white; padding: 20px; text-align: center; margin: 20px;">' +
                            '<strong>⚠️ Error al cargar el componente Offcanvas</strong><br>' +
                            'Este sitio necesita ejecutarse en un servidor local.<br>' +
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">./start-server.sh</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

/**
 * Header Component
 * Componente reutilizable para el header del sitio
 * Carga el HTML desde header.html y reemplaza los placeholders con datos de SiteConfig
 */
function loadHeader() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined') {
        console.error('SiteConfig no está definido. Asegúrate de cargar site-config.js primero.');
        return;
    }
    
    // Ruta al archivo HTML del componente
    const headerHTMLPath = 'assets/js/components/header/header.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Preparar los datos para reemplazar los placeholders
        // Verificar si se debe mostrar el botón del carrito (por defecto false si no está configurado)
        const showCart = SiteConfig.header && SiteConfig.header.showCart === true;
        
        const data = {
            welcomeMessage: SiteConfig.texts.welcomeMessage || '',
            phone: SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '',
            phoneClean: (SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '').replace(/\s/g, ''),
            email: SiteConfig.contact.email || '',
            accountText: SiteConfig.texts.accountText || 'Mi Cuenta',
            logo: SiteConfig.images.logo || 'assets/images/logo/logo.png',
            menuItems: SiteConfig.menu ? SiteConfig.menu.map(item => 
                `<li><a href="${item.href}">${item.text}</a></li>`
            ).join('') : '',
            cartButton: showCart ? 
                `<a href="#" realhref="#offcanvas-cart" class="header-action-btn header-action-btn-cart pr-0">
                    <i class="pe-7s-shopbag"></i>
                    <span class="header-action-num">0</span>
                </a>` : '',
            cartButtonMobile: showCart ? 
                `<a href="#" realhref="#offcanvas-cart" class="header-action-btn header-action-btn-cart offcanvas-toggle pr-0">
                    <i class="pe-7s-shopbag"></i>
                    <span class="header-action-num">01</span>
                </a>` : ''
        };
        
        // Reemplazar los placeholders con los datos
        let processedHTML = html;
        Object.keys(data).forEach(key => {
            const placeholder = new RegExp(`{{${key}}}`, 'g');
            const value = data[key] || ''; // Asegurar que siempre haya un valor (aunque sea vacío)
            processedHTML = processedHTML.replace(placeholder, value);
        });
        
        // Verificar que no queden placeholders sin reemplazar y limpiarlos
        processedHTML = processedHTML.replace(/{{[^}]+}}/g, '');
        
        // Insertar el header al inicio del main-wrapper
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('afterbegin', processedHTML);
        } else {
            console.error('No se encontró .main-wrapper para insertar el header');
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(headerHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar header.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Header:', error);
            console.error('Ruta intentada:', headerHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/header/header.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar header.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Header cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar header desde ruta alternativa:', fallbackError);
                    
                    // Mostrar mensaje de error claro
                    const mainWrapper = document.querySelector('.main-wrapper');
                    if (mainWrapper) {
                        mainWrapper.insertAdjacentHTML('afterbegin', 
                            '<div style="background: #ff9800; color: white; padding: 20px; text-align: center; margin: 20px;">' +
                            '<strong>⚠️ Error al cargar el componente Header</strong><br>' +
                            'Este sitio necesita ejecutarse en un servidor local.<br>' +
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">python -m http.server 8000</code> o <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">npx serve</code><br>' +
                            'Luego accede a: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">http://localhost:8000</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

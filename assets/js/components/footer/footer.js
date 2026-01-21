/**
 * Footer Component
 * Componente reutilizable para el footer del sitio
 * Carga el HTML desde footer.html y reemplaza los placeholders con datos de SiteConfig
 */
function loadFooter() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined') {
        console.error('SiteConfig no está definido. Asegúrate de cargar site-config.js primero.');
        return;
    }
    
    // Ruta al archivo HTML del componente
    const footerHTMLPath = 'assets/js/components/footer/footer.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Preparar los datos para reemplazar los placeholders
        const data = {
            footerLogo: SiteConfig.images.footerLogo || 'assets/images/logo/footer-logo.png',
            socialFacebook: SiteConfig.social.facebook || '#',
            socialTumblr: SiteConfig.social.tumblr || '#',
            socialTwitter: SiteConfig.social.twitter || '#',
            socialInstagram: SiteConfig.social.instagram || '#',
            footerServices: SiteConfig.footer.services ? SiteConfig.footer.services.map(service => 
                `<li class="li"><a class="single-link" href="${service.href}" realhref="${service.realhref}">${service.text}</a></li>`
            ).join('') : '',
            footerMyAccount: SiteConfig.footer.services ? SiteConfig.footer.services.map(service => 
                `<li class="li"><a class="single-link" href="${service.href}" realhref="${service.realhref}">${service.text}</a></li>`
            ).join('') : '',
            contactAddress: SiteConfig.contact.address || '',
            phone: SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '',
            phoneClean: (SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '').replace(/\s/g, ''),
            email: SiteConfig.contact.email || '',
            copyrightYear: SiteConfig.footer.copyright.year || '2026',
            copyrightCompany: SiteConfig.footer.copyright.company || 'IT Secur',
            copyrightCreator: SiteConfig.footer.copyright.creator || 'IT Secur SAS',
            copyrightCreatorUrl: SiteConfig.footer.copyright.creatorUrl || 'https://itsecursas.co/'
        };
        
        // Reemplazar los placeholders con los datos
        let processedHTML = html;
        Object.keys(data).forEach(key => {
            const placeholder = new RegExp(`{{${key}}}`, 'g');
            processedHTML = processedHTML.replace(placeholder, data[key]);
        });
        
        // Insertar el footer antes del cierre de main-wrapper
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('beforeend', processedHTML);
        } else {
            console.error('No se encontró .main-wrapper para insertar el footer');
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(footerHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar footer.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Footer:', error);
            console.error('Ruta intentada:', footerHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/footer/footer.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar footer.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Footer cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar footer desde ruta alternativa:', fallbackError);
                    
                    // Mostrar mensaje de error claro
                    const mainWrapper = document.querySelector('.main-wrapper');
                    if (mainWrapper) {
                        mainWrapper.insertAdjacentHTML('beforeend', 
                            '<div style="background: #ff9800; color: white; padding: 20px; text-align: center; margin: 20px;">' +
                            '<strong>⚠️ Error al cargar el componente Footer</strong><br>' +
                            'Este sitio necesita ejecutarse en un servidor local.<br>' +
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">./start-server.sh</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

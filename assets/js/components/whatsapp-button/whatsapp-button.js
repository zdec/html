/**
 * WhatsApp Floating Button Component
 * Botón flotante de WhatsApp que aparece en todas las páginas
 * Se posiciona en la esquina inferior derecha
 */
function loadWhatsAppButton() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined' || !SiteConfig.contact || !SiteConfig.contact.phone) {
        console.error('SiteConfig.contact.phone no está disponible para el botón de WhatsApp');
        return;
    }
    
    // Ruta al archivo HTML del componente
    const whatsappButtonHTMLPath = 'assets/js/components/whatsapp-button/whatsapp-button.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Obtener el número de teléfono y limpiarlo (remover espacios, guiones, etc.)
        const phoneNumber = SiteConfig.contact.phone.replace(/\s/g, '').replace(/-/g, '');
        
        // Crear el enlace de WhatsApp
        // Formato: https://wa.me/[número]?text=[mensaje]
        const whatsappMessage = encodeURIComponent('Hola, me interesa conocer más sobre sus productos.');
        const whatsappURL = `https://wa.me/${phoneNumber}?text=${whatsappMessage}`;
        
        // Reemplazar el href del enlace
        let processedHTML = html.replace('href="#"', `href="${whatsappURL}"`);
        
        // Insertar el botón al final del body (antes del cierre de </body>)
        const body = document.querySelector('body');
        if (body) {
            body.insertAdjacentHTML('beforeend', processedHTML);
        } else {
            console.error('No se encontró el elemento body para insertar el botón de WhatsApp');
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(whatsappButtonHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar whatsapp-button.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente WhatsApp Button:', error);
            console.error('Ruta intentada:', whatsappButtonHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/whatsapp-button/whatsapp-button.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar whatsapp-button.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('WhatsApp Button cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar whatsapp-button desde ruta alternativa:', fallbackError);
                });
        });
}

/**
 * Modals Component
 * Componentes reutilizables para los modales (quickview, cart, wishlist, compare)
 * Nota: Estos modales solo se necesitan en páginas con productos
 * Carga el HTML desde modals.html
 */
function loadModals() {
    // Verificar que SiteConfig esté disponible (aunque los modales no usan muchos datos dinámicos)
    if (typeof SiteConfig === 'undefined') {
        console.warn('SiteConfig no está definido. Los modales se cargarán sin datos dinámicos.');
    }
    
    // Ruta al archivo HTML del componente
    const modalsHTMLPath = 'assets/js/components/modals/modals.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Los modales no tienen muchos placeholders dinámicos por ahora
        // Si en el futuro necesitas agregar datos dinámicos, puedes hacerlo aquí
        
        // Insertar los modales antes del cierre del body o antes del footer
        const footer = document.querySelector('.footer-area');
        if (footer) {
            footer.insertAdjacentHTML('beforebegin', html);
        } else {
            // Si no hay footer, insertar antes del cierre de main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
            } else {
                console.error('No se encontró lugar para insertar los modales');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(modalsHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar modals.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
        })
        .catch(error => {
            console.error('Error al cargar el componente Modals:', error);
            console.error('Ruta intentada:', modalsHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/modals/modals.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar modals.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Modals cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar modals desde ruta alternativa:', fallbackError);
                    
                    // Mostrar mensaje de error claro
                    const mainWrapper = document.querySelector('.main-wrapper');
                    if (mainWrapper) {
                        mainWrapper.insertAdjacentHTML('beforeend', 
                            '<div style="background: #ff9800; color: white; padding: 20px; text-align: center; margin: 20px;">' +
                            '<strong>⚠️ Error al cargar el componente Modals</strong><br>' +
                            'Este sitio necesita ejecutarse en un servidor local.<br>' +
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">./start-server.sh</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

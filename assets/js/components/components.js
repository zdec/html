/**
 * Sistema de Componentes IT Secur
 * Carga y gestiona todos los componentes reutilizables
 */

// Verificar que SiteConfig esté disponible
if (typeof SiteConfig === 'undefined') {
    console.error('SiteConfig no está definido. Asegúrate de cargar site-config.js primero.');
}

/**
 * Carga todos los componentes comunes
 * @param {Object} options - Opciones de carga
 * @param {boolean} options.loadModals - Si se deben cargar los modales (default: true)
 * @param {boolean} options.loadScripts - Si se deben cargar los scripts (default: false, ya que normalmente están en el HTML)
 */
function loadAllComponents(options = {}) {
    const defaults = {
        loadModals: true,
        loadScripts: false // Por defecto false porque los scripts ya están en el HTML
    };
    
    const config = Object.assign({}, defaults, options);
    
    // Cargar componentes en orden
    if (typeof loadHeader === 'function') {
        loadHeader();
    }
    
    if (typeof loadOffcanvas === 'function') {
        loadOffcanvas();
    }
    
    if (typeof loadFooter === 'function') {
        loadFooter();
    }
    
    if (config.loadModals && typeof loadModals === 'function') {
        loadModals();
    }
    
    // Los scripts normalmente no se cargan dinámicamente porque ya están en el HTML
    // Pero se puede activar si es necesario
    if (config.loadScripts && typeof loadScripts === 'function') {
        loadScripts();
    }
    
    // Notificar que los componentes se han cargado
    if (typeof jQuery !== 'undefined') {
        jQuery(document).trigger('componentsLoaded');
    }
    
    console.log('✅ Componentes cargados correctamente');
}

/**
 * Inicializar componentes cuando el DOM esté listo
 */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAllComponents);
} else {
    // DOM ya está listo
    loadAllComponents();
}

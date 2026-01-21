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
 * @param {boolean} options.loadHeroSlider - Si se debe cargar el hero slider (default: false, solo en index)
 * @param {boolean} options.loadBannerArea - Si se debe cargar el banner area (default: false, solo en index)
 * @param {boolean} options.loadTestimonialArea - Si se debe cargar el testimonial area (default: false, solo en index)
 * @param {boolean} options.loadBrandArea - Si se debe cargar el brand area (default: false, solo en index)
 * @param {boolean} options.loadContactArea - Si se debe cargar el contact area (default: false, solo en contact)
 * @param {boolean} options.loadMapArea - Si se debe cargar el map area (default: false, solo en contact)
 * @param {boolean} options.loadProductDetailsArea - Si se debe cargar el product details area (default: false, solo en single-product)
 * @param {boolean} options.loadProductArea - Si se debe cargar el product area (default: false, solo en single-product)
 */
function loadAllComponents(options = {}) {
    const defaults = {
        loadModals: true,
        loadScripts: false, // Por defecto false porque los scripts ya están en el HTML
        loadHeroSlider: false,
        loadBannerArea: false,
        loadTestimonialArea: false,
        loadBrandArea: false,
        loadContactArea: false,
        loadMapArea: false,
        loadProductDetailsArea: false,
        loadProductArea: false
    };
    
    const config = Object.assign({}, defaults, options);
    
    // Cargar componentes comunes en orden
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
    
    // Componentes específicos de la página de inicio
    if (config.loadHeroSlider && typeof loadHeroSlider === 'function') {
        loadHeroSlider();
    }
    
    if (config.loadBannerArea && typeof loadBannerArea === 'function') {
        loadBannerArea();
    }
    
    if (config.loadTestimonialArea && typeof loadTestimonialArea === 'function') {
        loadTestimonialArea();
    }
    
    if (config.loadBrandArea && typeof loadBrandArea === 'function') {
        loadBrandArea();
    }
    
    // Componentes específicos de la página de contacto
    if (config.loadContactArea && typeof loadContactArea === 'function') {
        loadContactArea();
    }
    
    if (config.loadMapArea && typeof loadMapArea === 'function') {
        loadMapArea();
    }
    
    // Componentes específicos de la página de detalle de producto
    if (config.loadProductDetailsArea && typeof loadProductDetailsArea === 'function') {
        loadProductDetailsArea();
    }
    
    if (config.loadProductArea && typeof loadProductArea === 'function') {
        loadProductArea();
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
}

/**
 * Inicializar componentes cuando el DOM esté listo
 */
function initComponents() {
    // Detectar en qué página estamos para cargar componentes específicos
    const pathname = window.location.pathname;
    const isIndexPage = pathname.endsWith('index.html') || 
                        pathname.endsWith('/') || 
                        pathname === '/';
    const isContactPage = pathname.endsWith('contact.html');
    const isSingleProductPage = pathname.endsWith('single-product.html');
    const isCatalogPage = pathname.endsWith('catalog.html');
    
    const options = {
        loadModals: isIndexPage || isSingleProductPage || isCatalogPage, // Cargar modales en index, single-product y catalog (páginas con productos)
        loadScripts: false,
        loadHeroSlider: isIndexPage,
        loadBannerArea: isIndexPage,
        loadTestimonialArea: isIndexPage,
        loadBrandArea: isIndexPage,
        loadContactArea: isContactPage,
        loadMapArea: isContactPage,
        loadProductDetailsArea: isSingleProductPage,
        loadProductArea: isSingleProductPage
    };
    
    loadAllComponents(options);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initComponents);
} else {
    // DOM ya está listo
    initComponents();
}

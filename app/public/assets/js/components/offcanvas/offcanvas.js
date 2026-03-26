/**
 * Inicializa los event listeners para los offcanvas
 * @param {Object} callbacks - { onWishlistOpen: function } - callback al abrir wishlist
 */
function initOffcanvasListeners(callbacks) {
    // Verificar que jQuery esté disponible
    if (typeof jQuery === 'undefined') {
        console.warn('jQuery no está disponible. Los offcanvas pueden no funcionar correctamente.');
        return;
    }
    
    var $ = jQuery;
    var $body = $('body');
    var $offCanvas = $(".offcanvas");
    var $offCanvasOverlay = $(".offcanvas-overlay");
    var $mobileMenuToggle = $(".mobile-menu-toggle");
    
    $(document).off('click.offcanvas.open', '.offcanvas-toggle');
    $("body").off('click.offcanvas.close', '.offcanvas-close, .offcanvas-overlay');
    
    // Usar delegación: funciona aunque el header se cargue después
    $(document).on("click.offcanvas.open", ".offcanvas-toggle", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $this = $(this),
            $target = $this.attr("href");
        if ($target) {
            if ($target === '#offcanvas-wishlist' && callbacks && typeof callbacks.onWishlistOpen === 'function') {
                callbacks.onWishlistOpen();
            }
            $body.addClass("offcanvas-open");
            $($target).addClass("offcanvas-open");
            $offCanvasOverlay.fadeIn();
            if ($this.parent().hasClass("mobile-menu-toggle")) {
                $this.addClass("close");
            }
        }
    });
    
    $("body").on("click.offcanvas.close", ".offcanvas-close, .offcanvas-overlay", function(e) {
        e.preventDefault();
        $body.removeClass("offcanvas-open");
        $offCanvas.removeClass("offcanvas-open");
        $offCanvasOverlay.fadeOut();
        $mobileMenuToggle.find("a").removeClass("close");
    });
}

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
    
    const offcanvasHTMLPath = '/assets/js/components/offcanvas/offcanvas.html';
    const WISHLIST_STORAGE_KEY = 'itsecursas-wishlist';

    function getWishlistIds() {
        try {
            const stored = localStorage.getItem(WISHLIST_STORAGE_KEY);
            return stored ? JSON.parse(stored) : [];
        } catch (e) { return []; }
    }

    function removeFromWishlist(productId) {
        try {
            const ids = getWishlistIds().filter(id => id !== productId);
            localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(ids));
            const csrfToken = (typeof SiteConfig !== 'undefined' && SiteConfig.auth && SiteConfig.auth.csrfToken) ? SiteConfig.auth.csrfToken : '';
            fetch('/api/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            }).catch(function () {});
            renderWishlistOffcanvas();
            document.dispatchEvent(new CustomEvent('wishlist-updated', {
                detail: { productId: productId, liked: false, count: ids.length }
            }));
        } catch (e) { /* localStorage no disponible */ }
    }

    function renderWishlistOffcanvas() {
        const listEl = document.getElementById('wishlist-product-list');
        if (!listEl) return;

        const ids = getWishlistIds();
        const products = (typeof SiteConfig !== 'undefined' && SiteConfig.products?.items) ? SiteConfig.products.items : [];

        if (ids.length === 0) {
            listEl.innerHTML = '<li class="empty-wishlist text-center p-4">No hay productos en Me gusta</li>';
            return;
        }

        const html = ids.map(id => {
            const product = products.find(p => p.id === parseInt(id, 10));
            if (!product) return `<li data-product-id="${id}"><span>Producto #${id}</span> <a href="#" class="remove">×</a></li>`;
            const imgSrc = product.image || `/assets/images/products/${product.id}/1.webp`;
            const url = product.slug ? `/producto/${product.slug}` : '#';
            return `<li data-product-id="${product.id}">
                <a href="${url}" class="image"><img src="${imgSrc}" alt="${(product.alt || product.title || '').replace(/"/g, '&quot;')}"></a>
                <div class="content">
                    <a href="${url}" class="title">${(product.title || '').replace(/</g, '&lt;')}</a>
                    <span class="quantity-price">1 x <span class="amount">${product.price || ''}</span></span>
                    <a href="#" class="remove" aria-label="Eliminar">×</a>
                </div>
            </li>`;
        }).join('');

        listEl.innerHTML = html;
    }

    function initWishlistListeners() {
        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('#wishlist-product-list .remove');
            if (removeBtn) {
                e.preventDefault();
                const li = removeBtn.closest('li[data-product-id]');
                if (li) {
                    const productId = parseInt(li.getAttribute('data-product-id'), 10);
                    removeFromWishlist(productId);
                }
            }
        });
        document.addEventListener('wishlist-updated', renderWishlistOffcanvas);
    }

    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Preparar los datos para reemplazar los placeholders
        const auth = SiteConfig.auth || {};
        const accountText = SiteConfig.texts.accountText || 'Mi Cuenta';
        const accountHref = (auth.check && auth.adminUrl) ? auth.adminUrl : '/login';
        const data = {
            wishlistText: SiteConfig.texts.wishlistText || 'Me gusta',
            phone: SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '',
            phoneClean: (SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '').replace(/\s/g, ''),
            email: SiteConfig.contact.email || '',
            accountText: accountText,
            accountHref: accountHref,
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
                return;
            }
        }
        
        renderWishlistOffcanvas();
        initWishlistListeners();

        // Inicializar los event listeners del offcanvas después de insertar el HTML
        if (typeof jQuery !== 'undefined') {
            initOffcanvasListeners({ onWishlistOpen: renderWishlistOffcanvas });
        } else {
            var checkJQuery = setInterval(function() {
                if (typeof jQuery !== 'undefined') {
                    clearInterval(checkJQuery);
                    initOffcanvasListeners({ onWishlistOpen: renderWishlistOffcanvas });
                }
            }, 50);
            
            // Timeout de seguridad después de 2 segundos
            setTimeout(function() {
                clearInterval(checkJQuery);
                if (typeof jQuery !== 'undefined') {
                    initOffcanvasListeners({ onWishlistOpen: renderWishlistOffcanvas });
                } else {
                    console.error('jQuery no está disponible después de 2 segundos. Los offcanvas pueden no funcionar.');
                }
            }, 2000);
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
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">./docker-dev.sh</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

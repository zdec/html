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
    
    const modalsHTMLPath = '/assets/js/components/modals/modals.html';
    const WISHLIST_STORAGE_KEY = 'itsecursas-wishlist';

    function saveToWishlist(productId) {
        try {
            const stored = localStorage.getItem(WISHLIST_STORAGE_KEY);
            let ids = stored ? JSON.parse(stored) : [];
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
            }).then(function (r) {
                return r.ok ? r.json() : null;
            }).then(function (data) {
                if (!data || !data.success) return;
                if (data.liked) {
                    if (!ids.includes(productId)) ids.push(productId);
                } else {
                    ids = ids.filter(function (id) { return id !== productId; });
                }
                localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(ids));
                document.dispatchEvent(new CustomEvent('wishlist-updated', {
                    detail: { productId: productId, liked: data.liked, count: data.count || ids.length }
                }));
            }).catch(function () {
                if (!ids.includes(productId)) ids.push(productId);
                localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(ids));
                document.dispatchEvent(new CustomEvent('wishlist-updated', {
                    detail: { productId: productId, liked: true, count: ids.length }
                }));
            });
        } catch (e) { /* localStorage no disponible */ }
    }

    // Función para inicializar el Swiper de la modal de quickview
    function initQuickviewSlider() {
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper no está disponible para inicializar la modal de quickview');
            return;
        }
        
        // Buscar los elementos dentro de la modal
        const modal = document.querySelector('#exampleModal');
        if (!modal) {
            return;
        }
        
        // Verificar si ya está inicializado
        const galleryTopElement = modal.querySelector('.gallery-top');
        const galleryThumbsElement = modal.querySelector('.gallery-thumbs');
        
        if (!galleryTopElement || !galleryThumbsElement) {
            return;
        }
        
        // Si ya tiene una instancia de Swiper, destruirla primero
        if (galleryTopElement.swiper) {
            galleryTopElement.swiper.destroy(true, true);
        }
        if (galleryThumbsElement.swiper) {
            galleryThumbsElement.swiper.destroy(true, true);
        }
        
        // Inicializar gallery-thumbs primero
        const galleryThumb = new Swiper(galleryThumbsElement, {
            spaceBetween: 10,
            slidesPerView: 3,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: galleryThumbsElement.querySelector(".swiper-button-next"),
                prevEl: galleryThumbsElement.querySelector(".swiper-button-prev"),
            },
        });
        
        // Inicializar gallery-top con thumbs
        const galleryTop = new Swiper(galleryTopElement, {
            spaceBetween: 0,
            loop: true,
            slidesPerView: 1,
            thumbs: {
                swiper: galleryThumb
            }
        });
    }
    
    /**
     * Carga la información del producto en la modal de quickview
     * @param {number} productIndex - El índice del producto según el campo 'index' en SiteConfig
     */
    function loadProductInQuickview(idOrIndex, lookupBy) {
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        // Buscar por id (Laravel) o por index (legacy)
        const product = lookupBy === 'id'
            ? SiteConfig.products.items.find(p => p.id === idOrIndex)
            : SiteConfig.products.items.find(p => p.index === idOrIndex);
        if (!product) {
            console.error(`Producto no encontrado`);
            return;
        }
        
        // Generar las imágenes del slider (1.webp a 5.webp)
        // Estructura: /assets/images/products/{id}/1.webp ... 5.webp
        function generateImageSlides(productId) {
            let slidesHTML = '';
            for (let i = 1; i <= 5; i++) {
                const imagePath = `/assets/images/products/${productId}/${i}.webp`;
                slidesHTML += `<div class="swiper-slide">
                    <img class="img-responsive m-auto" src="${imagePath}" alt="${product.alt || product.title}">
                </div>`;
            }
            return slidesHTML;
        }
        
        // Actualizar las imágenes del slider
        const galleryTopWrapper = document.getElementById('quickview-gallery-top');
        const galleryThumbsWrapper = document.getElementById('quickview-gallery-thumbs');
        
        if (galleryTopWrapper) {
            galleryTopWrapper.innerHTML = generateImageSlides(product.id || product.index);
        }
        if (galleryThumbsWrapper) {
            galleryThumbsWrapper.innerHTML = generateImageSlides(product.id || product.index);
        }
        
        // Actualizar el título
        const titleElement = document.getElementById('quickview-title');
        if (titleElement) {
            titleElement.textContent = product.title;
        }
        
        // Actualizar el precio
        const priceElement = document.getElementById('quickview-price');
        if (priceElement) {
            let priceHTML = '';
            if (product.oldPrice) {
                // La clase old-price tiene estilos CSS para tachar el precio
                priceHTML = `<li class="old-price">${product.oldPrice}</li><li class="new-price">${product.price}</li>`;
            } else {
                priceHTML = `<li class="new-price">${product.price}</li>`;
            }
            priceElement.innerHTML = priceHTML;
        }
        
        // Actualizar la descripción
        const descriptionElement = document.getElementById('quickview-description');
        if (descriptionElement) {
            descriptionElement.textContent = product.description || '';
        }
        
        // Actualizar el SKU
        const skuElement = document.getElementById('quickview-sku');
        if (skuElement) {
            skuElement.innerHTML = `<li><a href="#">${product.sku || 'N/A'}</a></li>`;
        }
        
        // Actualizar la disponibilidad
        const availabilityElement = document.getElementById('quickview-availability');
        if (availabilityElement) {
            const availabilityDays = product.availability || 0;
            const availabilityText = availabilityDays > 0 
                ? `${availabilityDays} ${availabilityDays === 1 ? 'día' : 'días'}` 
                : 'No disponible';
            availabilityElement.innerHTML = `<li><a href="#">${availabilityText}</a></li>`;
        }
        
        // Actualizar la categoría
        const categoryElement = document.getElementById('quickview-category');
        if (categoryElement) {
            categoryElement.innerHTML = `<li><a href="#">${product.category || 'N/A'}</a></li>`;
        }
        
        // Actualizar los tags
        const tagsElement = document.getElementById('quickview-tags');
        if (tagsElement && product.tags && product.tags.length > 0) {
            let tagsHTML = '';
            product.tags.forEach((tag, idx) => {
                tagsHTML += `<li><a href="#">${tag}${idx < product.tags.length - 1 ? ', ' : ''}</a></li>`;
            });
            tagsElement.innerHTML = tagsHTML;
        } else if (tagsElement) {
            tagsElement.innerHTML = '<li><a href="#">N/A</a></li>';
        }
        
        // Reinicializar el Swiper después de actualizar el contenido
        setTimeout(function() {
            initQuickviewSlider();
        }, 100);
    }
    
    /**
     * Carga la imagen del producto en los modales Cart, Wishlist y Compare
     * @param {number} productIndex - El índice del producto según el campo 'index' en SiteConfig
     * @param {string} modalType - Tipo de modal: 'cart', 'wishlist' o 'compare'
     */
    function loadProductInModal(idOrIndex, modalType, lookupBy) {
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        const product = lookupBy === 'id'
            ? SiteConfig.products.items.find(p => p.id === idOrIndex)
            : (lookupBy === 'index'
                ? SiteConfig.products.items.find(p => p.index === idOrIndex)
                : SiteConfig.products.items.find(p => p.id === idOrIndex) || SiteConfig.products.items.find(p => p.index === idOrIndex));
        if (!product) {
            console.error(`Producto no encontrado`);
            return;
        }
        
        // Ruta de la imagen: /assets/images/products/{id}/1.webp
        const imagePath = product.image || `/assets/images/products/${product.id || product.index}/1.webp`;
        
        // Actualizar según el tipo de modal
        if (modalType === 'cart') {
            const imageElement = document.getElementById('cart-modal-image');
            const titleElement = document.getElementById('cart-modal-title');
            if (imageElement) {
                imageElement.src = imagePath;
                imageElement.alt = product.title || 'Detalle Orden';
            }
            if (titleElement) {
                const link = titleElement.querySelector('a');
                if (link) {
                    link.textContent = product.title || 'Detalle de la orden';
                    link.href = product.slug ? `/producto/${product.slug}` : '#';
                }
            }
        } else if (modalType === 'wishlist') {
            const imageElement = document.getElementById('wishlist-modal-image');
            const titleElement = document.getElementById('wishlist-modal-title');
            if (imageElement) {
                imageElement.src = imagePath;
                imageElement.alt = product.title || 'Detalle Orden';
            }
            if (titleElement) {
                const link = titleElement.querySelector('a');
                if (link) {
                    link.textContent = product.title || 'Detalle de la orden';
                    link.href = product.slug ? `/producto/${product.slug}` : '#';
                }
            }
        } else if (modalType === 'compare') {
            const imageElement = document.getElementById('compare-modal-image');
            const titleElement = document.getElementById('compare-modal-title');
            if (imageElement) {
                imageElement.src = imagePath;
                imageElement.alt = product.title || 'Detalle';
            }
            if (titleElement) {
                const link = titleElement.querySelector('a');
                if (link) {
                    link.textContent = product.title || 'Detalles';
                    link.href = product.slug ? `/producto/${product.slug}` : '#';
                }
            }
        }
    }
    
    /**
     * Configura los event listeners para los botones de quickview, cart, wishlist y compare
     */
    function setupQuickviewListeners() {
        // Usar event delegation para manejar clicks en botones
        // Esto funciona incluso si los productos se cargan dinámicamente
        document.addEventListener('click', function(e) {
            // Manejar Quick View (soporta data-product-id o data-product-index)
            const quickviewButton = e.target.closest('.action.quickview[data-product-id], .action.quickview[data-product-index]');
            if (quickviewButton) {
                const productId = quickviewButton.getAttribute('data-product-id');
                const productIndex = quickviewButton.getAttribute('data-product-index');
                const idOrIndex = productId ? parseInt(productId, 10) : (productIndex ? parseInt(productIndex, 10) : null);
                if (idOrIndex) {
                    const modal = document.querySelector('#exampleModal');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInQuickview(idOrIndex, productId ? 'id' : 'index');
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
            
            // Manejar Add to Cart (soporta data-product-id o data-product-index)
            const cartButton = e.target.closest('.action.add-to-cart[data-product-id], .action.add-to-cart[data-product-index]');
            if (cartButton) {
                const productId = cartButton.getAttribute('data-product-id');
                const productIndex = cartButton.getAttribute('data-product-index');
                const idOrIndex = productId ? parseInt(productId, 10) : (productIndex ? parseInt(productIndex, 10) : null);
                if (idOrIndex) {
                    const modal = document.querySelector('#exampleModal-Cart');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInModal(idOrIndex, 'cart', productId ? 'id' : 'index');
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
            
            // Manejar Wishlist (soporta data-product-id o data-product-index)
            const wishlistButton = e.target.closest('.action.wishlist[data-product-id], .action.wishlist[data-product-index]');
            if (wishlistButton) {
                const wishlistProductId = wishlistButton.getAttribute('data-product-id');
                const wishlistProductIndex = wishlistButton.getAttribute('data-product-index');
                const wishlistIdOrIndex = wishlistProductId ? parseInt(wishlistProductId, 10) : (wishlistProductIndex ? parseInt(wishlistProductIndex, 10) : null);
                if (wishlistIdOrIndex) {
                    const modal = document.querySelector('#exampleModal-Wishlist');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInModal(wishlistIdOrIndex, 'wishlist', wishlistProductId ? 'id' : 'index');
                            saveToWishlist(wishlistIdOrIndex);
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
            
            // Manejar Compare (soporta data-product-id o data-product-index)
            const compareButton = e.target.closest('.action.compare[data-product-id], .action.compare[data-product-index]');
            if (compareButton) {
                const compareProductId = compareButton.getAttribute('data-product-id');
                const compareProductIndex = compareButton.getAttribute('data-product-index');
                const compareIdOrIndex = compareProductId ? parseInt(compareProductId, 10) : (compareProductIndex ? parseInt(compareProductIndex, 10) : null);
                if (compareIdOrIndex) {
                    const modal = document.querySelector('#exampleModal-Compare');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInModal(compareIdOrIndex, 'compare', compareProductId ? 'id' : 'index');
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
        });
    }
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
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
        
        // Configurar los event listeners para quickview después de insertar el HTML
        setTimeout(function() {
            setupQuickviewListeners();
        }, 100);
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
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">./docker-dev.sh</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

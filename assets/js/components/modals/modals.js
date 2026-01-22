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
    function loadProductInQuickview(productIndex) {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        
        // Buscar el producto por su índice
        const product = SiteConfig.products.items.find(p => p.index === productIndex);
        if (!product) {
            console.error(`Producto con índice ${productIndex} no encontrado`);
            return;
        }
        
        // Generar las imágenes del slider (1.webp a 5.webp)
        // Estructura zoom: assets/images/product-image/zoom-image/{index}/1.webp
        // Estructura small: assets/images/product-image/small-image/{index}/1.webp
        function generateImageSlides(index, type) {
            let slidesHTML = '';
            for (let i = 1; i <= 5; i++) {
                const imagePath = `assets/images/product-image/${type}/${index}/${i}.webp`;
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
            galleryTopWrapper.innerHTML = generateImageSlides(product.index, 'zoom-image');
        }
        if (galleryThumbsWrapper) {
            galleryThumbsWrapper.innerHTML = generateImageSlides(product.index, 'small-image');
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
    function loadProductInModal(productIndex, modalType) {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        
        // Buscar el producto por su índice
        const product = SiteConfig.products.items.find(p => p.index === productIndex);
        if (!product) {
            console.error(`Producto con índice ${productIndex} no encontrado`);
            return;
        }
        
        // Construir la ruta de la imagen: assets/images/product-image/{index}/1.webp
        const imagePath = `assets/images/product-image/${product.index}/1.webp`;
        
        // Actualizar según el tipo de modal
        if (modalType === 'cart') {
            const imageElement = document.getElementById('cart-modal-image');
            const titleElement = document.getElementById('cart-modal-title');
            if (imageElement) {
                imageElement.src = imagePath;
                imageElement.alt = product.title || 'Detalle Orden';
            }
            if (titleElement) {
                titleElement.querySelector('a').textContent = product.title || 'Detalle de la orden';
            }
        } else if (modalType === 'wishlist') {
            const imageElement = document.getElementById('wishlist-modal-image');
            const titleElement = document.getElementById('wishlist-modal-title');
            if (imageElement) {
                imageElement.src = imagePath;
                imageElement.alt = product.title || 'Detalle Orden';
            }
            if (titleElement) {
                titleElement.querySelector('a').textContent = product.title || 'Detalle de la orden';
            }
        } else if (modalType === 'compare') {
            const imageElement = document.getElementById('compare-modal-image');
            const titleElement = document.getElementById('compare-modal-title');
            if (imageElement) {
                imageElement.src = imagePath;
                imageElement.alt = product.title || 'Detalle';
            }
            if (titleElement) {
                titleElement.querySelector('a').textContent = product.title || 'Detalles';
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
            // Manejar Quick View
            const quickviewButton = e.target.closest('.action.quickview[data-product-index]');
            if (quickviewButton) {
                const productIndex = parseInt(quickviewButton.getAttribute('data-product-index'));
                if (productIndex) {
                    // Esperar a que la modal se abra antes de cargar los datos
                    const modal = document.querySelector('#exampleModal');
                    if (modal) {
                        // Escuchar el evento de Bootstrap cuando la modal se muestra
                        const handleModalShow = function() {
                            loadProductInQuickview(productIndex);
                            // Remover el listener después de usarlo para evitar múltiples llamadas
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
            
            // Manejar Add to Cart
            const cartButton = e.target.closest('.action.add-to-cart[data-product-index]');
            if (cartButton) {
                const productIndex = parseInt(cartButton.getAttribute('data-product-index'));
                if (productIndex) {
                    const modal = document.querySelector('#exampleModal-Cart');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInModal(productIndex, 'cart');
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
            
            // Manejar Wishlist
            const wishlistButton = e.target.closest('.action.wishlist[data-product-index]');
            if (wishlistButton) {
                const productIndex = parseInt(wishlistButton.getAttribute('data-product-index'));
                if (productIndex) {
                    const modal = document.querySelector('#exampleModal-Wishlist');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInModal(productIndex, 'wishlist');
                            modal.removeEventListener('shown.bs.modal', handleModalShow);
                        };
                        modal.addEventListener('shown.bs.modal', handleModalShow);
                    }
                }
            }
            
            // Manejar Compare
            const compareButton = e.target.closest('.action.compare[data-product-index]');
            if (compareButton) {
                const productIndex = parseInt(compareButton.getAttribute('data-product-index'));
                if (productIndex) {
                    const modal = document.querySelector('#exampleModal-Compare');
                    if (modal) {
                        const handleModalShow = function() {
                            loadProductInModal(productIndex, 'compare');
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
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">./start-server.sh</code>' +
                            '</div>'
                        );
                    }
                });
        });
}

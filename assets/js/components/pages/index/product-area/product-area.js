/**
 * Index Product Area Component
 * Componente reutilizable para el área de productos con tabs (Novedades y Mas Vendidos) en index.html
 * Carga el HTML desde product-area.html
 */
function loadIndexProductArea() {
    // Ruta al archivo HTML del componente
    const productAreaHTMLPath = 'assets/js/components/pages/index/product-area/product-area.html';
    
    // Función para insertar el HTML
    function insertHTML(html) {
        // Insertar antes del fashion-area
        const fashionArea = document.querySelector('.fashion-area');
        if (fashionArea) {
            fashionArea.insertAdjacentHTML('beforebegin', html);
        } else {
            // Si no hay fashion-area, buscar brand-area como referencia
            const brandArea = document.querySelector('.brand-area');
            if (brandArea) {
                brandArea.insertAdjacentHTML('afterend', html);
            } else {
                // Si no hay brand-area, buscar testimonial-area
                const testimonialArea = document.querySelector('.testimonial-area');
                if (testimonialArea) {
                    testimonialArea.insertAdjacentHTML('afterend', html);
                } else {
                    // Si no hay testimonial-area, buscar banner-area
                    const bannerArea = document.querySelector('.banner-area');
                    if (bannerArea) {
                        bannerArea.insertAdjacentHTML('afterend', html);
                    } else {
                        // Último recurso: insertar al final del main-wrapper
                        const mainWrapper = document.querySelector('.main-wrapper');
                        if (mainWrapper) {
                            mainWrapper.insertAdjacentHTML('beforeend', html);
                        } else {
                            console.error('No se encontró lugar para insertar el product area');
                        }
                    }
                }
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(productAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar product-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
            // Generar productos dinámicamente después de insertar el HTML
            generateProducts();
        })
        .catch(error => {
            console.error('Error al cargar el componente Product Area:', error);
            console.error('Ruta intentada:', productAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/index/product-area/product-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar product-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Product Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar product-area desde ruta alternativa:', fallbackError);
                });
        });
    
    /**
     * Genera el HTML de los productos dinámicamente desde SiteConfig
     */
    function generateProducts() {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        
        const products = SiteConfig.products.items;
        
        // Función para generar el HTML de un producto
        function generateProductHTML(product) {
            // Generar badges HTML
            let badgesHTML = '';
            if (product.badges && product.badges.length > 0) {
                badgesHTML = '<span class="badges">';
                product.badges.forEach(badge => {
                    // Permitir badges como string simple o como objeto con texto personalizado
                    let badgeType = badge;
                    let badgeText = null;
                    
                    if (typeof badge === 'object' && badge.type) {
                        badgeType = badge.type;
                        badgeText = badge.text;
                    }
                    
                    if (badgeType === 'sale') {
                        // Para sale, calcular el porcentaje si hay oldPrice
                        if (product.oldPrice) {
                            const oldPriceNum = parseFloat(product.oldPrice.replace(/[^0-9.]/g, ''));
                            const newPriceNum = parseFloat(product.price.replace(/[^0-9.]/g, ''));
                            const discount = Math.round(((oldPriceNum - newPriceNum) / oldPriceNum) * 100);
                            badgesHTML += `<span class="sale">-${discount}%</span>`;
                        } else {
                            badgesHTML += `<span class="sale">${badgeText || 'Oferta'}</span>`;
                        }
                    } else if (badgeType === 'new') {
                        badgesHTML += `<span class="new">${badgeText || 'Nuevo'}</span>`;
                    }
                });
                badgesHTML += '</span>';
            } else {
                badgesHTML = '<span class="badges"></span>';
            }
            
            // Generar precio HTML
            let priceHTML = '';
            if (product.oldPrice) {
                priceHTML = `<span class="price">
                    <span class="old">${product.oldPrice}</span>
                    <span class="new">${product.price}</span>
                </span>`;
            } else {
                priceHTML = `<span class="price">
                    <span class="new">${product.price}</span>
                </span>`;
            }
            
            // Generar HTML completo del producto
            return `
                <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px">
                    <!-- Single Prodect -->
                    <div class="product">
                        ${badgesHTML}
                        <div class="thumb">
                            <a href="single-product.html?index=${product.index}" class="image">
                                <img src="${product.image}" alt="${product.alt}" />
                                <img class="hover-image" src="${product.image}" alt="${product.alt}" />
                            </a>
                        </div>
                        <div class="content">
                            <span class="category"><a href="single-product.html?index=${product.index}">${product.category}</a></span>
                            <h5 class="title"><a href="single-product.html?index=${product.index}">${product.title}</a></h5>
                            ${priceHTML}
                        </div>
                        <div class="actions">
                            <button class="action wishlist" data-product-index="${product.index}" title="Wishlist" data-bs-toggle="modal" data-bs-target="#exampleModal-Wishlist"><i class="pe-7s-like"></i></button>
                            <button class="action quickview" data-link-action="quickview" data-product-index="${product.index}" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="pe-7s-look"></i></button>
                        </div>
                    </div>
                </div>
            `;
        }
        
        /**
         * Normaliza el orden de productos calculando automáticamente valores faltantes
         * @param {Array} products - Array de productos
         * @param {string} orderField - Campo de ordenamiento ('orderNewArrivals' o 'orderTopRated')
         * @returns {Array} - Array de productos con orden normalizado
         */
        function normalizeProductOrder(products, orderField) {
            // Crear copia de productos para no modificar el original
            const normalizedProducts = products.map((product, index) => {
                // Si el campo de orden no existe, calcularlo automáticamente
                if (product[orderField] === undefined || product[orderField] === null) {
                    let calculatedOrder;
                    
                    if (orderField === 'orderNewArrivals') {
                        // Para novedades: productos con badge "new" primero, luego por id descendente
                        const hasNewBadge = product.badges && product.badges.some(badge => {
                            if (typeof badge === 'string') return badge === 'new';
                            if (typeof badge === 'object' && badge.type) return badge.type === 'new';
                            return false;
                        });
                        
                        if (hasNewBadge) {
                            // Productos con badge "new" van primero (orden 1-999)
                            calculatedOrder = 1000 - product.id; // Id más alto = orden más bajo (aparece primero)
                        } else {
                            // Productos sin badge "new" van después (orden 1000+)
                            calculatedOrder = 2000 - product.id;
                        }
                    } else if (orderField === 'orderTopRated') {
                        // Para más vendidos: ordenar por precio (más caro primero), luego por id
                        const priceValue = parseFloat((product.price || '0').replace(/[^0-9.]/g, '')) || 0;
                        // Invertir el precio para que los más caros tengan orden más bajo
                        calculatedOrder = 10000 - priceValue - (product.id * 0.01);
                    } else {
                        // Fallback: usar id
                        calculatedOrder = product.id || index + 1;
                    }
                    
                    return { ...product, [orderField]: calculatedOrder };
                }
                return product;
            });
            
            return normalizedProducts;
        }
        
        /**
         * Ordena productos por un campo específico
         * @param {Array} products - Array de productos
         * @param {string} orderField - Campo de ordenamiento
         * @returns {Array} - Array de productos ordenado
         */
        function sortProductsByOrder(products, orderField) {
            // Normalizar productos primero (calcular valores faltantes)
            const normalizedProducts = normalizeProductOrder(products, orderField);
            
            // Ordenar por el campo especificado
            return normalizedProducts.sort((a, b) => {
                const orderA = a[orderField] || 9999;
                const orderB = b[orderField] || 9999;
                return orderA - orderB;
            });
        }
        
        // Generar productos para tab "Novedades"
        const newArrivalsProducts = sortProductsByOrder(products, 'orderNewArrivals');
        const newArrivalsContainer = document.getElementById('newarrivals-products');
        if (newArrivalsContainer) {
            newArrivalsContainer.innerHTML = newArrivalsProducts.map(product => generateProductHTML(product)).join('');
        }
        
        // Generar productos para tab "Mas Vendidos"
        const topRatedProducts = sortProductsByOrder(products, 'orderTopRated');
        const topRatedContainer = document.getElementById('toprated-products');
        if (topRatedContainer) {
            topRatedContainer.innerHTML = topRatedProducts.map(product => generateProductHTML(product)).join('');
        }
    }
}

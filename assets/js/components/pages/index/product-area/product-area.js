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
                            <a href="single-product.html" class="image">
                                <img src="${product.image}" alt="${product.alt}" />
                                <img class="hover-image" src="${product.image}" alt="${product.alt}" />
                            </a>
                        </div>
                        <div class="content">
                            <span class="category"><a href="single-product.html">${product.category}</a></span>
                            <h5 class="title"><a href="single-product.html">${product.title}</a></h5>
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
        
        // Función para ordenar productos por un campo específico
        function sortProductsByOrder(products, orderField) {
            return [...products].sort((a, b) => {
                return (a[orderField] || 999) - (b[orderField] || 999);
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

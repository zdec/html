/**
 * Shop Page Component
 * Componente reutilizable para el área de catálogo de productos
 * Carga el HTML desde shop-page.html
 */
function loadShopPage() {
    // Ruta al archivo HTML del componente
    const shopPageHTMLPath = 'assets/js/components/pages/catalog/shop-page/shop-page.html';
    
    // Función para insertar el HTML
    function insertHTML(html) {
        // Insertar después del breadcrumb-area y antes del footer
        const breadcrumbArea = document.querySelector('.breadcrumb-area');
        if (breadcrumbArea) {
            breadcrumbArea.insertAdjacentHTML('afterend', html);
        } else {
            // Si no hay breadcrumb, insertar al inicio del main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
            } else {
                console.error('No se encontró lugar para insertar el shop page');
            }
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(shopPageHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar shop-page.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
            // Generar productos dinámicamente después de insertar el HTML
            generateShopProducts();
            // Inicializar el ordenamiento
            initSorting();
        })
        .catch(error => {
            console.error('Error al cargar el componente Shop Page:', error);
            console.error('Ruta intentada:', shopPageHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/catalog/shop-page/shop-page.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar shop-page.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Shop Page cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar shop-page desde ruta alternativa:', fallbackError);
                });
        });
    
    /**
     * Genera los productos dinámicamente desde SiteConfig
     * Crea productos en formato grid y list
     */
    function generateShopProducts() {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        
        const products = SiteConfig.products.items;
        
        // Renderizar los productos usando la función compartida
        renderProducts(products);
        
        // Actualizar el contador de productos encontrados
        updateProductCount(products.length);
    }
    
    /**
     * Renderiza los productos en los contenedores grid y list
     * @param {Array} products - Array de productos a renderizar
     */
    function renderProducts(products) {
        // Función para generar el HTML de un producto en formato grid
        function generateProductGridHTML(product) {
            // Generar badges HTML
            let badgesHTML = '';
            if (product.badges && product.badges.length > 0) {
                badgesHTML = '<span class="badges">';
                product.badges.forEach(badge => {
                    let badgeType = badge;
                    let badgeText = null;
                    
                    if (typeof badge === 'object' && badge.type) {
                        badgeType = badge.type;
                        badgeText = badge.text;
                    }
                    
                    if (badgeType === 'sale') {
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
            
            // Ruta de la imagen: assets/images/product-image/{index}/1.webp
            const imagePath = `assets/images/product-image/${product.index}/1.webp`;
            
            // Generar HTML completo del producto en formato grid
            return `
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30px">
                    <!-- Single Prodect -->
                    <div class="product">
                        ${badgesHTML}
                        <div class="thumb">
                            <a href="single-product.html?index=${product.index}" class="image">
                                <img src="${imagePath}" alt="${product.alt || product.title}" />
                                <img class="hover-image" src="${imagePath}" alt="${product.alt || product.title}" />
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
        
        // Función para generar el HTML de un producto en formato list
        function generateProductListHTML(product) {
            // Generar badges HTML
            let badgesHTML = '';
            if (product.badges && product.badges.length > 0) {
                badgesHTML = '<span class="badges">';
                product.badges.forEach(badge => {
                    let badgeType = badge;
                    let badgeText = null;
                    
                    if (typeof badge === 'object' && badge.type) {
                        badgeType = badge.type;
                        badgeText = badge.text;
                    }
                    
                    if (badgeType === 'sale') {
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
            
            // Ruta de la imagen: assets/images/product-image/{index}/1.webp
            const imagePath = `assets/images/product-image/${product.index}/1.webp`;
            
            // Descripción corta (primeros 150 caracteres)
            const shortDescription = product.description ? product.description.substring(0, 150) + '...' : 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodol tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veni quis nostrud exercitation ullamco laboris';
            
            // Generar HTML completo del producto en formato list
            return `
                <div class="shop-list-wrapper mb-30px">
                    <div class="row">
                        <div class="col-md-5 col-lg-5 col-xl-4 mb-lm-30px">
                            <div class="product">
                                <div class="thumb">
                                    <a href="single-product.html?index=${product.index}" class="image">
                                        <img src="${imagePath}" alt="${product.alt || product.title}" />
                                        <img class="hover-image" src="${imagePath}" alt="${product.alt || product.title}" />
                                    </a>
                                    ${badgesHTML}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7 col-xl-8">
                            <div class="content-desc-wrap">
                                <div class="content">
                                    <span class="category"><a href="single-product.html?index=${product.index}">${product.category}</a></span>
                                    <h5 class="title"><a href="single-product.html?index=${product.index}">${product.title}</a></h5>
                                    <p>${shortDescription}</p>
                                </div>
                                <div class="box-inner">
                                    ${priceHTML}
                                    <div class="actions">
                                        <button class="action wishlist" data-product-index="${product.index}" title="Wishlist" data-bs-toggle="modal" data-bs-target="#exampleModal-Wishlist"><i class="pe-7s-like"></i></button>
                                        <button class="action quickview" data-link-action="quickview" data-product-index="${product.index}" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="pe-7s-look"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Generar HTML de todos los productos en formato grid
        const gridProductsHTML = products.map(product => generateProductGridHTML(product)).join('');
        
        // Generar HTML de todos los productos en formato list
        const listProductsHTML = products.map(product => generateProductListHTML(product)).join('');
        
        // Insertar los productos en el contenedor grid
        const gridContainer = document.getElementById('shop-grid-products');
        if (gridContainer) {
            gridContainer.innerHTML = gridProductsHTML;
        }
        
        // Insertar los productos en el contenedor list
        const listContainer = document.getElementById('shop-list-products');
        if (listContainer) {
            listContainer.innerHTML = listProductsHTML;
        }
        
        // Actualizar el contador de productos
        updateProductCount(products.length);
    }
    
    /**
     * Actualiza el contador de productos encontrados
     * @param {number} count - Número de productos
     */
    function updateProductCount(count) {
        const productCountSpans = document.querySelectorAll('.compare-product span');
        if (productCountSpans.length > 0 && count > 0) {
            // Actualizar el primer span (productos encontrados)
            productCountSpans[0].textContent = count;
            // Si hay un segundo span (total), también actualizarlo
            if (productCountSpans.length > 1) {
                productCountSpans[1].textContent = count;
            }
        }
    }
    
    /**
     * Inicializa la funcionalidad de ordenamiento
     */
    function initSorting() {
        const sortItems = document.querySelectorAll('.dropdown-item[data-sort]');
        const sortButton = document.getElementById('sort-dropdown-btn');
        
        if (!sortItems.length || !sortButton) {
            return;
        }
        
        // Agregar event listeners a cada opción de ordenamiento
        sortItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                const sortType = this.getAttribute('data-sort');
                const sortText = this.textContent.trim();
                
                // Actualizar el texto del botón
                const buttonText = sortButton.innerHTML.split('<i')[0].trim();
                sortButton.innerHTML = `${sortText} <i class="fa fa-angle-down"></i>`;
                
                // Ordenar y re-renderizar productos
                sortAndRenderProducts(sortType);
            });
        });
    }
    
    /**
     * Ordena los productos según el criterio seleccionado y los re-renderiza
     * @param {string} sortType - Tipo de ordenamiento (default, name-asc, name-desc, price-asc, price-desc)
     */
    function sortAndRenderProducts(sortType) {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        
        let products = [...SiteConfig.products.items]; // Crear copia para no modificar el original
        
        // Aplicar ordenamiento según el tipo
        switch(sortType) {
            case 'name-asc':
                products.sort((a, b) => {
                    const nameA = (a.title || '').toLowerCase();
                    const nameB = (b.title || '').toLowerCase();
                    return nameA.localeCompare(nameB);
                });
                break;
                
            case 'name-desc':
                products.sort((a, b) => {
                    const nameA = (a.title || '').toLowerCase();
                    const nameB = (b.title || '').toLowerCase();
                    return nameB.localeCompare(nameA);
                });
                break;
                
            case 'price-asc':
                products.sort((a, b) => {
                    const priceA = parseFloat((a.price || '0').replace(/[^0-9.]/g, '')) || 0;
                    const priceB = parseFloat((b.price || '0').replace(/[^0-9.]/g, '')) || 0;
                    return priceA - priceB;
                });
                break;
                
            case 'price-desc':
                products.sort((a, b) => {
                    const priceA = parseFloat((a.price || '0').replace(/[^0-9.]/g, '')) || 0;
                    const priceB = parseFloat((b.price || '0').replace(/[^0-9.]/g, '')) || 0;
                    return priceB - priceA;
                });
                break;
                
            case 'default':
            default:
                // Mantener el orden original (por index o como venga en el JSON)
                products = SiteConfig.products.items;
                break;
        }
        
        // Re-renderizar los productos con el nuevo orden
        renderProducts(products);
    }
    
    /**
     * Renderiza los productos en los contenedores grid y list
     * @param {Array} products - Array de productos a renderizar
     */
    function renderProducts(products) {
        // Función para generar el HTML de un producto en formato grid
        function generateProductGridHTML(product) {
            // Generar badges HTML
            let badgesHTML = '';
            if (product.badges && product.badges.length > 0) {
                badgesHTML = '<span class="badges">';
                product.badges.forEach(badge => {
                    let badgeType = badge;
                    let badgeText = null;
                    
                    if (typeof badge === 'object' && badge.type) {
                        badgeType = badge.type;
                        badgeText = badge.text;
                    }
                    
                    if (badgeType === 'sale') {
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
            
            // Ruta de la imagen: assets/images/product-image/{index}/1.webp
            const imagePath = `assets/images/product-image/${product.index}/1.webp`;
            
            // Generar HTML completo del producto en formato grid
            return `
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30px">
                    <!-- Single Prodect -->
                    <div class="product">
                        ${badgesHTML}
                        <div class="thumb">
                            <a href="single-product.html?index=${product.index}" class="image">
                                <img src="${imagePath}" alt="${product.alt || product.title}" />
                                <img class="hover-image" src="${imagePath}" alt="${product.alt || product.title}" />
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
        
        // Función para generar el HTML de un producto en formato list
        function generateProductListHTML(product) {
            // Generar badges HTML
            let badgesHTML = '';
            if (product.badges && product.badges.length > 0) {
                badgesHTML = '<span class="badges">';
                product.badges.forEach(badge => {
                    let badgeType = badge;
                    let badgeText = null;
                    
                    if (typeof badge === 'object' && badge.type) {
                        badgeType = badge.type;
                        badgeText = badge.text;
                    }
                    
                    if (badgeType === 'sale') {
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
            
            // Ruta de la imagen: assets/images/product-image/{index}/1.webp
            const imagePath = `assets/images/product-image/${product.index}/1.webp`;
            
            // Descripción corta (primeros 150 caracteres)
            const shortDescription = product.description ? product.description.substring(0, 150) + '...' : 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodol tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veni quis nostrud exercitation ullamco laboris';
            
            // Generar HTML completo del producto en formato list
            return `
                <div class="shop-list-wrapper mb-30px">
                    <div class="row">
                        <div class="col-md-5 col-lg-5 col-xl-4 mb-lm-30px">
                            <div class="product">
                                <div class="thumb">
                                    <a href="single-product.html?index=${product.index}" class="image">
                                        <img src="${imagePath}" alt="${product.alt || product.title}" />
                                        <img class="hover-image" src="${imagePath}" alt="${product.alt || product.title}" />
                                    </a>
                                    ${badgesHTML}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7 col-xl-8">
                            <div class="content-desc-wrap">
                                <div class="content">
                                    <span class="category"><a href="single-product.html?index=${product.index}">${product.category}</a></span>
                                    <h5 class="title"><a href="single-product.html?index=${product.index}">${product.title}</a></h5>
                                    <p>${shortDescription}</p>
                                </div>
                                <div class="box-inner">
                                    ${priceHTML}
                                    <div class="actions">
                                        <button class="action wishlist" data-product-index="${product.index}" title="Wishlist" data-bs-toggle="modal" data-bs-target="#exampleModal-Wishlist"><i class="pe-7s-like"></i></button>
                                        <button class="action quickview" data-link-action="quickview" data-product-index="${product.index}" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="pe-7s-look"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Generar HTML de todos los productos en formato grid
        const gridProductsHTML = products.map(product => generateProductGridHTML(product)).join('');
        
        // Generar HTML de todos los productos en formato list
        const listProductsHTML = products.map(product => generateProductListHTML(product)).join('');
        
        // Insertar los productos en el contenedor grid
        const gridContainer = document.getElementById('shop-grid-products');
        if (gridContainer) {
            gridContainer.innerHTML = gridProductsHTML;
        }
        
        // Insertar los productos en el contenedor list
        const listContainer = document.getElementById('shop-list-products');
        if (listContainer) {
            listContainer.innerHTML = listProductsHTML;
        }
        
        // Actualizar el contador de productos
        updateProductCount(products.length);
    }
}

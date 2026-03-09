/**
 * Product Area Component
 * Componente reutilizable para el área de productos relacionados
 * Carga el HTML desde product-area.html e inicializa el slider Swiper
 */
function loadProductArea() {
    // Ruta al archivo HTML del componente
    const productAreaHTMLPath = 'assets/js/components/pages/single-product/product-area/product-area.html';
    
    // Función para insertar el HTML e inicializar el slider
    function insertHTML(html) {
        // Insertar antes del footer
        const footer = document.querySelector('.footer-area');
        if (footer) {
            footer.insertAdjacentHTML('beforebegin', html);
        } else {
            // Si no hay footer, insertar al final del main-wrapper
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
            } else {
                console.error('No se encontró lugar para insertar el product area');
            }
        }
        
        // Inicializar el slider de Swiper después de que se inserte el HTML
        // El main.js se encargará de inicializarlo, pero lo hacemos aquí también por si acaso
        if (typeof Swiper !== 'undefined') {
            setTimeout(function() {
                const productSlider = document.querySelector('.new-product-slider.swiper-container');
                if (productSlider && !productSlider.swiper) {
                    new Swiper('.new-product-slider.swiper-container', {
                        slidesPerView: 4,
                        spaceBetween: 30,
                        speed: 1500,
                        loop: true,
                        navigation: {
                            nextEl: ".new-product-slider .swiper-button-next",
                            prevEl: ".new-product-slider .swiper-button-prev",
                        },
                        breakpoints: {
                            0: {
                                slidesPerView: 1,
                            },
                            478: {
                                slidesPerView: 1,
                            },
                            576: {
                                slidesPerView: 2,
                            },
                            768: {
                                slidesPerView: 2,
                            },
                            992: {
                                slidesPerView: 3,
                            },
                            1200: {
                                slidesPerView: 4,
                            },
                        }
                    });
                }
            }, 200);
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
            // Generar productos relacionados dinámicamente después de insertar el HTML
            generateRelatedProducts();
        })
        .catch(error => {
            console.error('Error al cargar el componente Product Area:', error);
            console.error('Ruta intentada:', productAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/single-product/product-area/product-area.html';
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
     * Obtiene el índice del producto actual desde la URL
     * @returns {number|null} El índice del producto o null si no se encuentra
     */
    function getCurrentProductIndex() {
        const urlParams = new URLSearchParams(window.location.search);
        const index = urlParams.get('index');
        return index ? parseInt(index) : null;
    }
    
    /**
     * Genera los productos relacionados dinámicamente desde SiteConfig
     * Excluye el producto actual que se está viendo
     */
    function generateRelatedProducts() {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible');
            return;
        }
        
        // Obtener el índice del producto actual
        const currentProductIndex = getCurrentProductIndex();
        
        // Obtener todos los productos y filtrar el actual
        let products = SiteConfig.products.items;
        if (currentProductIndex) {
            products = products.filter(p => p.index !== currentProductIndex);
        }
        
        // Si no hay productos, no hacer nada
        if (products.length === 0) {
            console.warn('No hay productos relacionados para mostrar');
            return;
        }
        
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
            
            // Ruta de la imagen: assets/images/product-image/{index}/1.webp
            const imagePath = `assets/images/product-image/${product.index}/1.webp`;
            
            // Generar HTML completo del producto
            return `
                <div class="swiper-slide">
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
        
        // Generar HTML de todos los productos relacionados
        const productsHTML = products.map(product => generateProductHTML(product)).join('');
        
        // Insertar los productos en el swiper-wrapper
        const wrapper = document.getElementById('related-products-wrapper');
        if (wrapper) {
            wrapper.innerHTML = productsHTML;
            
            // Reinicializar el Swiper después de cargar los productos
            setTimeout(function() {
                if (typeof Swiper !== 'undefined') {
                    const productSlider = document.querySelector('.new-product-slider.swiper-container');
                    if (productSlider) {
                        // Destruir instancia previa si existe
                        if (productSlider.swiper) {
                            productSlider.swiper.destroy(true, true);
                        }
                        
                        // Inicializar el slider
                        new Swiper('.new-product-slider.swiper-container', {
                            slidesPerView: 4,
                            spaceBetween: 30,
                            speed: 1500,
                            loop: products.length > 4, // Solo loop si hay más de 4 productos
                            navigation: {
                                nextEl: ".new-product-slider .swiper-button-next",
                                prevEl: ".new-product-slider .swiper-button-prev",
                            },
                            breakpoints: {
                                0: {
                                    slidesPerView: 1,
                                },
                                478: {
                                    slidesPerView: 1,
                                },
                                576: {
                                    slidesPerView: 2,
                                },
                                768: {
                                    slidesPerView: 2,
                                },
                                992: {
                                    slidesPerView: 3,
                                },
                                1200: {
                                    slidesPerView: 4,
                                },
                            }
                        });
                    }
                }
            }, 200);
        }
    }
}

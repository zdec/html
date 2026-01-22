/**
 * Product Details Area Component
 * Componente reutilizable para el área de detalles del producto
 * Carga el HTML desde product-details-area.html e inicializa los sliders Swiper
 */
function loadProductDetailsArea() {
    // Ruta al archivo HTML del componente
    const productDetailsAreaHTMLPath = 'assets/js/components/pages/single-product/product-details-area/product-details-area.html';
    
    // Función para insertar el HTML e inicializar los sliders
    function insertHTML(html) {
        // Insertar después del breadcrumb
        const breadcrumb = document.querySelector('.breadcrumb-area');
        if (breadcrumb) {
            breadcrumb.insertAdjacentHTML('afterend', html);
        } else {
            // Si no hay breadcrumb, insertar después del header/offcanvas
            const header = document.querySelector('header');
            if (header) {
                header.insertAdjacentHTML('afterend', html);
            } else {
                const mainWrapper = document.querySelector('.main-wrapper');
                if (mainWrapper) {
                    mainWrapper.insertAdjacentHTML('afterbegin', html);
                } else {
                    console.error('No se encontró lugar para insertar el product details area');
                }
            }
        }
        
        // Inicializar los sliders de Swiper después de que se inserte el HTML
        // El main.js se encargará de inicializarlos, pero lo hacemos aquí también por si acaso
        if (typeof Swiper !== 'undefined') {
            setTimeout(function() {
                // Inicializar zoom-thumbs primero
                const zoomThumbElement = document.querySelector('.zoom-thumbs.swiper-container');
                if (zoomThumbElement && !zoomThumbElement.swiper) {
                    var zoomThumb = new Swiper('.zoom-thumbs', {
                        spaceBetween: 18,
                        slidesPerView: 3,
                        freeMode: true,
                        watchSlidesVisibility: true,
                        watchSlidesProgress: true,
                        navigation: {
                            nextEl: ".zoom-thumbs .swiper-button-next",
                            prevEl: ".zoom-thumbs .swiper-button-prev",
                        },
                    });
                    
                    // Inicializar zoom-top después, con referencia a zoom-thumbs
                    const zoomTopElement = document.querySelector('.zoom-top.swiper-container');
                    if (zoomTopElement && !zoomTopElement.swiper) {
                        var zoomTop = new Swiper('.zoom-top', {
                            spaceBetween: 0,
                            slidesPerView: 1,
                            effect: 'fade',
                            fadeEffect: {
                                crossFade: true,
                            },
                            thumbs: {
                                swiper: zoomThumb
                            }
                        });
                    }
                }
            }, 200);
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(productDetailsAreaHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar product-details-area.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            insertHTML(html);
            // Cargar información del producto dinámicamente después de insertar el HTML
            loadProductDetails();
        })
        .catch(error => {
            console.error('Error al cargar el componente Product Details Area:', error);
            console.error('Ruta intentada:', productDetailsAreaHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/pages/single-product/product-details-area/product-details-area.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar product-details-area.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Product Details Area cargado exitosamente desde ruta alternativa');
                    insertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar product-details-area desde ruta alternativa:', fallbackError);
                });
        });
    
    /**
     * Obtiene el índice del producto desde la URL
     * Busca el parámetro 'index' en la query string
     * @returns {number|null} El índice del producto o null si no se encuentra
     */
    function getProductIndexFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const index = urlParams.get('index');
        return index ? parseInt(index) : null;
    }
    
    /**
     * Carga la información del producto dinámicamente desde SiteConfig
     */
    function loadProductDetails() {
        // Obtener el índice del producto desde la URL
        const productIndex = getProductIndexFromURL();
        
        if (!productIndex) {
            console.warn('No se encontró el parámetro "index" en la URL. Usando producto por defecto (index: 1)');
            // Por defecto, usar el producto con index 1
            loadProductData(1);
            return;
        }
        
        loadProductData(productIndex);
    }
    
    /**
     * Carga los datos del producto y actualiza el HTML
     * @param {number} productIndex - El índice del producto según el campo 'index' en SiteConfig
     */
    function loadProductData(productIndex) {
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
                if (type === 'zoom-image') {
                    // Para zoom-image, incluir el venobox para vista completa
                    slidesHTML += `<div class="swiper-slide">
                        <img class="img-responsive m-auto" src="${imagePath}" alt="${product.alt || product.title}">
                        <a class="venobox full-preview" data-gall="myGallery" href="${imagePath}">
                            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                        </a>
                    </div>`;
                } else {
                    // Para small-image, solo la imagen
                    slidesHTML += `<div class="swiper-slide">
                        <img class="img-responsive m-auto" src="${imagePath}" alt="${product.alt || product.title}">
                    </div>`;
                }
            }
            return slidesHTML;
        }
        
        // Actualizar las imágenes del slider
        const zoomTopWrapper = document.getElementById('product-details-zoom-top');
        const zoomThumbsWrapper = document.getElementById('product-details-zoom-thumbs');
        
        if (zoomTopWrapper) {
            zoomTopWrapper.innerHTML = generateImageSlides(product.index, 'zoom-image');
        }
        if (zoomThumbsWrapper) {
            zoomThumbsWrapper.innerHTML = generateImageSlides(product.index, 'small-image');
        }
        
        // Actualizar el título
        const titleElement = document.getElementById('product-details-title');
        if (titleElement) {
            titleElement.textContent = product.title;
        }
        
        // Actualizar el precio
        const priceElement = document.getElementById('product-details-price');
        if (priceElement) {
            let priceHTML = '';
            if (product.oldPrice) {
                priceHTML = `<li class="old-price">${product.oldPrice}</li><li class="new-price">${product.price}</li>`;
            } else {
                priceHTML = `<li class="new-price">${product.price}</li>`;
            }
            priceElement.innerHTML = priceHTML;
        }
        
        // Actualizar la descripción corta
        const descriptionElement = document.getElementById('product-details-description');
        if (descriptionElement) {
            descriptionElement.textContent = product.description || '';
        }
        
        // Actualizar la descripción completa (en el tab Description)
        const fullDescriptionElement = document.getElementById('product-details-full-description');
        if (fullDescriptionElement) {
            fullDescriptionElement.textContent = product.description || '';
        }
        
        // Actualizar el SKU
        const skuElement = document.getElementById('product-details-sku');
        if (skuElement) {
            skuElement.innerHTML = `<li><a href="#">${product.sku || 'N/A'}</a></li>`;
        }
        
        // Actualizar la disponibilidad
        const availabilityElement = document.getElementById('product-details-availability');
        if (availabilityElement) {
            const availabilityDays = product.availability || 0;
            const availabilityText = availabilityDays > 0 
                ? `${availabilityDays} ${availabilityDays === 1 ? 'día' : 'días'}` 
                : 'No disponible';
            availabilityElement.innerHTML = `<li><a href="#">${availabilityText}</a></li>`;
        }
        
        // Actualizar la categoría
        const categoryElement = document.getElementById('product-details-category');
        if (categoryElement) {
            categoryElement.innerHTML = `<li><a href="#">${product.category || 'N/A'}</a></li>`;
        }
        
        // Actualizar los tags
        const tagsElement = document.getElementById('product-details-tags');
        if (tagsElement && product.tags && product.tags.length > 0) {
            let tagsHTML = '';
            product.tags.forEach((tag, idx) => {
                tagsHTML += `<li><a href="#">${tag}${idx < product.tags.length - 1 ? ', ' : ''}</a></li>`;
            });
            tagsElement.innerHTML = tagsHTML;
        } else if (tagsElement) {
            tagsElement.innerHTML = '<li><a href="#">N/A</a></li>';
        }
        
        // Actualizar la información adicional (Information tab)
        const informationElement = document.getElementById('product-details-information');
        if (informationElement && product.information) {
            let infoHTML = '<ul>';
            if (product.information.weight) {
                infoHTML += `<li><span>Peso</span> ${product.information.weight}</li>`;
            }
            if (product.information.dimensions) {
                infoHTML += `<li><span>Dimensiones</span> ${product.information.dimensions}</li>`;
            }
            if (product.information.materials) {
                infoHTML += `<li><span>Materiales</span> ${product.information.materials}</li>`;
            }
            if (product.information.other_info) {
                infoHTML += `<li><span>Otra Información</span> ${product.information.other_info}</li>`;
            }
            infoHTML += '</ul>';
            informationElement.innerHTML = infoHTML;
        }
        
        // Reinicializar los Swipers después de actualizar el contenido
        setTimeout(function() {
            if (typeof Swiper !== 'undefined') {
                // Destruir instancias previas si existen
                const zoomThumbElement = document.querySelector('.zoom-thumbs.swiper-container');
                const zoomTopElement = document.querySelector('.zoom-top.swiper-container');
                
                if (zoomThumbElement && zoomThumbElement.swiper) {
                    zoomThumbElement.swiper.destroy(true, true);
                }
                if (zoomTopElement && zoomTopElement.swiper) {
                    zoomTopElement.swiper.destroy(true, true);
                }
                
                // Inicializar zoom-thumbs primero
                if (zoomThumbElement) {
                    var zoomThumb = new Swiper('.zoom-thumbs', {
                        spaceBetween: 18,
                        slidesPerView: 3,
                        freeMode: true,
                        watchSlidesVisibility: true,
                        watchSlidesProgress: true,
                        navigation: {
                            nextEl: ".zoom-thumbs .swiper-button-next",
                            prevEl: ".zoom-thumbs .swiper-button-prev",
                        },
                    });
                    
                    // Inicializar zoom-top después, con referencia a zoom-thumbs
                    if (zoomTopElement) {
                        var zoomTop = new Swiper('.zoom-top', {
                            spaceBetween: 0,
                            slidesPerView: 1,
                            effect: 'fade',
                            fadeEffect: {
                                crossFade: true,
                            },
                            thumbs: {
                                swiper: zoomThumb
                            }
                        });
                    }
                }
            }
        }, 200);
    }
}

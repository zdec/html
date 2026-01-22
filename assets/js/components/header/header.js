/**
 * Header Component
 * Componente reutilizable para el header del sitio
 * Carga el HTML desde header.html y reemplaza los placeholders con datos de SiteConfig
 */
function loadHeader() {
    // Verificar que SiteConfig esté disponible
    if (typeof SiteConfig === 'undefined') {
        console.error('SiteConfig no está definido. Asegúrate de cargar site-config.js primero.');
        return;
    }
    
    // Ruta al archivo HTML del componente
    const headerHTMLPath = 'assets/js/components/header/header.html';
    
    // Función para procesar e insertar el HTML
    function processAndInsertHTML(html) {
        // Preparar los datos para reemplazar los placeholders
        // Verificar si se debe mostrar el botón del carrito (por defecto false si no está configurado)
        const showCart = SiteConfig.header && SiteConfig.header.showCart === true;
        
        const data = {
            welcomeMessage: SiteConfig.texts.welcomeMessage || '',
            phone: SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '',
            phoneClean: (SiteConfig.contact.phoneFormatted || SiteConfig.contact.phone || '').replace(/\s/g, ''),
            email: SiteConfig.contact.email || '',
            accountText: SiteConfig.texts.accountText || 'Mi Cuenta',
            logo: SiteConfig.images.logo || 'assets/images/logo/logo.png',
            menuItems: SiteConfig.menu ? SiteConfig.menu.map(item => 
                `<li><a href="${item.href}">${item.text}</a></li>`
            ).join('') : '',
            cartButton: showCart ? 
                `<a href="#" realhref="#offcanvas-cart" class="header-action-btn header-action-btn-cart pr-0">
                    <i class="pe-7s-shopbag"></i>
                    <span class="header-action-num">0</span>
                </a>` : '',
            cartButtonMobile: showCart ? 
                `<a href="#" realhref="#offcanvas-cart" class="header-action-btn header-action-btn-cart offcanvas-toggle pr-0">
                    <i class="pe-7s-shopbag"></i>
                    <span class="header-action-num">01</span>
                </a>` : ''
        };
        
        // Reemplazar los placeholders con los datos
        let processedHTML = html;
        Object.keys(data).forEach(key => {
            const placeholder = new RegExp(`{{${key}}}`, 'g');
            const value = data[key] || ''; // Asegurar que siempre haya un valor (aunque sea vacío)
            processedHTML = processedHTML.replace(placeholder, value);
        });
        
        // Verificar que no queden placeholders sin reemplazar y limpiarlos
        processedHTML = processedHTML.replace(/{{[^}]+}}/g, '');
        
        // Insertar el header al inicio del main-wrapper
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('afterbegin', processedHTML);
        } else {
            console.error('No se encontró .main-wrapper para insertar el header');
        }
    }
    
    // Cargar el HTML del componente usando fetch
    fetch(headerHTMLPath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar header.html: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            processAndInsertHTML(html);
            // Inicializar la funcionalidad de búsqueda después de insertar el header
            initProductSearch();
        })
        .catch(error => {
            console.error('Error al cargar el componente Header:', error);
            console.error('Ruta intentada:', headerHTMLPath);
            
            // Fallback: intentar con ruta absoluta desde la raíz
            const fallbackPath = '/assets/js/components/header/header.html';
            console.log('Intentando con ruta alternativa:', fallbackPath);
            
            fetch(fallbackPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error al cargar header.html (fallback): ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('Header cargado exitosamente desde ruta alternativa');
                    processAndInsertHTML(html);
                })
                .catch(fallbackError => {
                    console.error('Error al cargar header desde ruta alternativa:', fallbackError);
                    
                    // Mostrar mensaje de error claro
                    const mainWrapper = document.querySelector('.main-wrapper');
                    if (mainWrapper) {
                        mainWrapper.insertAdjacentHTML('afterbegin', 
                            '<div style="background: #ff9800; color: white; padding: 20px; text-align: center; margin: 20px;">' +
                            '<strong>⚠️ Error al cargar el componente Header</strong><br>' +
                            'Este sitio necesita ejecutarse en un servidor local.<br>' +
                            'Por favor, ejecuta: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">python -m http.server 8000</code> o <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">npx serve</code><br>' +
                            'Luego accede a: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">http://localhost:8000</code>' +
                            '</div>'
                        );
                    }
                });
        });
    
    /**
     * Inicializa la funcionalidad de búsqueda de productos con autocompletado
     */
    function initProductSearch() {
        // Verificar que SiteConfig esté disponible
        if (typeof SiteConfig === 'undefined' || !SiteConfig.products || !SiteConfig.products.items) {
            console.error('SiteConfig.products no está disponible para la búsqueda');
            return;
        }
        
        // IDs de los inputs de búsqueda
        const searchInputs = [
            { input: 'search-input-desktop', results: 'search-results-desktop', form: 'search-form-desktop' },
            { input: 'search-input-mobile-header', results: 'search-results-mobile-header', form: 'search-form-mobile-header' },
            { input: 'search-input-mobile', results: 'search-results-mobile', form: 'search-form-mobile' }
        ];
        
        // Inicializar búsqueda para cada input
        searchInputs.forEach(({ input, results, form }) => {
            const inputElement = document.getElementById(input);
            const resultsElement = document.getElementById(results);
            const formElement = document.getElementById(form);
            
            if (inputElement && resultsElement && formElement) {
                // Event listener para búsqueda mientras escribe
                inputElement.addEventListener('input', function(e) {
                    const query = e.target.value.trim();
                    if (query.length >= 2) {
                        performSearch(query, resultsElement);
                    } else {
                        hideResults(resultsElement);
                    }
                });
                
                // Event listener para prevenir submit del formulario
                formElement.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const query = inputElement.value.trim();
                    if (query.length >= 2) {
                        const firstResult = resultsElement.querySelector('.search-result-item');
                        if (firstResult) {
                            const productIndex = firstResult.getAttribute('data-product-index');
                            if (productIndex) {
                                window.location.href = `single-product.html?index=${productIndex}`;
                            }
                        }
                    }
                });
                
                // Ocultar resultados al hacer clic fuera
                document.addEventListener('click', function(e) {
                    if (!formElement.contains(e.target)) {
                        hideResults(resultsElement);
                    }
                });
                
                // Manejar navegación con teclado
                inputElement.addEventListener('keydown', function(e) {
                    const visibleResults = resultsElement.querySelectorAll('.search-result-item:not([style*="display: none"])');
                    const selectedItem = resultsElement.querySelector('.search-result-item.selected');
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        navigateResults(visibleResults, selectedItem, 'down', resultsElement);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        navigateResults(visibleResults, selectedItem, 'up', resultsElement);
                    } else if (e.key === 'Enter' && selectedItem) {
                        e.preventDefault();
                        const productIndex = selectedItem.getAttribute('data-product-index');
                        if (productIndex) {
                            window.location.href = `single-product.html?index=${productIndex}`;
                        }
                    } else if (e.key === 'Escape') {
                        hideResults(resultsElement);
                        inputElement.blur();
                    }
                });
            }
        });
    }
    
    /**
     * Realiza la búsqueda de productos
     * @param {string} query - Término de búsqueda
     * @param {HTMLElement} resultsContainer - Contenedor donde mostrar los resultados
     */
    function performSearch(query, resultsContainer) {
        const products = SiteConfig.products.items;
        const searchTerm = query.toLowerCase();
        
        // Buscar en múltiples campos
        const results = products.filter(product => {
            // Buscar en título
            if (product.title && product.title.toLowerCase().includes(searchTerm)) return true;
            
            // Buscar en categoría
            if (product.category && product.category.toLowerCase().includes(searchTerm)) return true;
            
            // Buscar en descripción
            if (product.description && product.description.toLowerCase().includes(searchTerm)) return true;
            
            // Buscar en SKU
            if (product.sku && product.sku.toLowerCase().includes(searchTerm)) return true;
            
            // Buscar en tags
            if (product.tags && Array.isArray(product.tags)) {
                if (product.tags.some(tag => tag.toLowerCase().includes(searchTerm))) return true;
            }
            
            // Buscar en información adicional
            if (product.information) {
                if (product.information.materials && product.information.materials.toLowerCase().includes(searchTerm)) return true;
                if (product.information.other_info && product.information.other_info.toLowerCase().includes(searchTerm)) return true;
            }
            
            return false;
        });
        
        // Limitar resultados a 10
        const limitedResults = results.slice(0, 10);
        
        // Mostrar resultados
        displayResults(limitedResults, query, resultsContainer);
    }
    
    /**
     * Muestra los resultados de búsqueda
     * @param {Array} results - Array de productos encontrados
     * @param {string} query - Término de búsqueda
     * @param {HTMLElement} container - Contenedor donde mostrar los resultados
     */
    function displayResults(results, query, container) {
        if (results.length === 0) {
            container.innerHTML = '<div class="search-result-item search-result-empty">No se encontraron productos</div>';
            container.style.display = 'block';
            return;
        }
        
        let html = '';
        results.forEach(product => {
            // Resaltar el término de búsqueda en el título
            const highlightedTitle = highlightText(product.title, query);
            
            // Imagen del producto
            const imagePath = `assets/images/product-image/${product.index}/1.webp`;
            
            // Precio
            const price = product.oldPrice ? 
                `<span class="search-result-price-old">${product.oldPrice}</span> <span class="search-result-price">${product.price}</span>` :
                `<span class="search-result-price">${product.price}</span>`;
            
            html += `
                <div class="search-result-item" data-product-index="${product.index}">
                    <div class="search-result-image">
                        <img src="${imagePath}" alt="${product.alt || product.title}" />
                    </div>
                    <div class="search-result-content">
                        <div class="search-result-title">${highlightedTitle}</div>
                        <div class="search-result-category">${product.category}</div>
                        <div class="search-result-price-container">${price}</div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
        container.style.display = 'block';
        
        // Agregar event listeners a cada resultado
        const resultItems = container.querySelectorAll('.search-result-item');
        resultItems.forEach(item => {
            item.addEventListener('click', function() {
                const productIndex = this.getAttribute('data-product-index');
                if (productIndex) {
                    window.location.href = `single-product.html?index=${productIndex}`;
                }
            });
            
            item.addEventListener('mouseenter', function() {
                resultItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }
    
    /**
     * Resalta el texto de búsqueda en el resultado
     * @param {string} text - Texto original
     * @param {string} query - Término de búsqueda
     * @returns {string} - Texto con resaltado
     */
    function highlightText(text, query) {
        if (!text || !query) return text;
        
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    /**
     * Oculta los resultados de búsqueda
     * @param {HTMLElement} container - Contenedor de resultados
     */
    function hideResults(container) {
        if (container) {
            container.style.display = 'none';
            container.innerHTML = '';
        }
    }
    
    /**
     * Navega por los resultados con el teclado
     * @param {NodeList} results - Lista de resultados visibles
     * @param {HTMLElement} selectedItem - Item actualmente seleccionado
     * @param {string} direction - Dirección de navegación ('up' o 'down')
     * @param {HTMLElement} container - Contenedor de resultados
     */
    function navigateResults(results, selectedItem, direction, container) {
        if (results.length === 0) return;
        
        let currentIndex = -1;
        if (selectedItem) {
            currentIndex = Array.from(results).indexOf(selectedItem);
        }
        
        let newIndex;
        if (direction === 'down') {
            newIndex = currentIndex < results.length - 1 ? currentIndex + 1 : 0;
        } else {
            newIndex = currentIndex > 0 ? currentIndex - 1 : results.length - 1;
        }
        
        // Remover selección anterior
        results.forEach(item => item.classList.remove('selected'));
        
        // Agregar selección nueva
        if (results[newIndex]) {
            results[newIndex].classList.add('selected');
            // Scroll al item seleccionado si es necesario
            results[newIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }
}

/**
 * Favorites Page Component
 * Muestra la lista de favoritos (productos guardados en localStorage)
 * Usa el mismo layout que el catálogo (shop-page.html)
 */
const WISHLIST_STORAGE_KEY = 'itsecursas-wishlist';

function getWishlistIds() {
    try {
        const stored = localStorage.getItem(WISHLIST_STORAGE_KEY);
        return stored ? JSON.parse(stored) : [];
    } catch (e) { return []; }
}

function loadFavoritesPage() {
    const shopPageHTMLPath = '/assets/js/components/pages/catalog/shop-page/shop-page.html';

    function insertHTML(html) {
        const breadcrumbArea = document.querySelector('.breadcrumb-area');
        if (breadcrumbArea) {
            breadcrumbArea.insertAdjacentHTML('afterend', html);
        } else {
            const mainWrapper = document.querySelector('.main-wrapper');
            if (mainWrapper) {
                mainWrapper.insertAdjacentHTML('beforeend', html);
            }
        }
    }

    function getFilteredProducts() {
        const ids = getWishlistIds();
        if (ids.length === 0) return [];
        const products = (typeof SiteConfig !== 'undefined' && SiteConfig.products?.items) ? SiteConfig.products.items : [];
        return products.filter(p => ids.includes(p.id));
    }

    function updateProductCount(count) {
        const productCountSpans = document.querySelectorAll('.shop-top-bar .compare-product span');
        if (productCountSpans.length > 0) {
            productCountSpans[0].textContent = count;
            if (productCountSpans.length > 1) productCountSpans[1].textContent = count;
        }
    }

    function renderProducts(products) {
        const productUrl = p => p.slug ? '/producto/' + p.slug : '#';
        const imagePath = p => p.image || `/assets/images/products/${p.id}/1.webp`;
        const badgesHTML = p => {
            if (!p.badges?.length) return '<span class="badges"></span>';
            let html = '<span class="badges">';
            p.badges.forEach(badge => {
                const t = typeof badge === 'object' ? badge.type : badge;
                if (t === 'sale' && p.oldPrice) {
                    const oldP = parseFloat(String(p.oldPrice).replace(/[^0-9.]/g, ''));
                    const newP = parseFloat(String(p.price).replace(/[^0-9.]/g, ''));
                    const d = Math.round(((oldP - newP) / oldP) * 100);
                    html += `<span class="sale">-${d}%</span>`;
                } else if (t === 'new') html += '<span class="new">Nuevo</span>';
            });
            return html + '</span>';
        };
        const priceHTML = p => p.oldPrice
            ? `<span class="price"><span class="old">${p.oldPrice}</span><span class="new">${p.price}</span></span>`
            : `<span class="price"><span class="new">${p.price}</span></span>`;

        const gridHTML = products.map(p => `
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30px">
                <div class="product">
                    ${badgesHTML(p)}
                    <div class="thumb">
                        <a href="${productUrl(p)}" class="image">
                            <img src="${imagePath(p)}" alt="${(p.alt || p.title || '').replace(/"/g, '&quot;')}" />
                            <img class="hover-image" src="${imagePath(p)}" alt="${(p.alt || p.title || '').replace(/"/g, '&quot;')}" />
                        </a>
                    </div>
                    <div class="content">
                        <span class="category"><a href="${productUrl(p)}">${(p.category || '').replace(/</g, '&lt;')}</a></span>
                        <h5 class="title"><a href="${productUrl(p)}">${(p.title || '').replace(/</g, '&lt;')}</a></h5>
                        ${priceHTML(p)}
                    </div>
                    <div class="actions">
                        <button class="action wishlist" data-product-id="${p.id}" title="Me gusta" data-bs-toggle="modal" data-bs-target="#exampleModal-Wishlist"><i class="pe-7s-like"></i></button>
                        <button class="action quickview" data-link-action="quickview" data-product-id="${p.id}" title="Vista rápida" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="pe-7s-look"></i></button>
                    </div>
                </div>
            </div>
        `).join('');

        const listHTML = products.map(p => {
            const desc = (p.description || '').substring(0, 150) + (p.description && p.description.length > 150 ? '...' : '');
            return `
                <div class="shop-list-wrapper mb-30px">
                    <div class="row">
                        <div class="col-md-5 col-lg-5 col-xl-4 mb-lm-30px">
                            <div class="product">
                                <div class="thumb">
                                    <a href="${productUrl(p)}" class="image"><img src="${imagePath(p)}" alt="${(p.alt || p.title || '').replace(/"/g, '&quot;')}" /></a>
                                    ${badgesHTML(p)}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7 col-xl-8">
                            <div class="content-desc-wrap">
                                <div class="content">
                                    <span class="category"><a href="${productUrl(p)}">${(p.category || '').replace(/</g, '&lt;')}</a></span>
                                    <h5 class="title"><a href="${productUrl(p)}">${(p.title || '').replace(/</g, '&lt;')}</a></h5>
                                    <p>${desc.replace(/</g, '&lt;')}</p>
                                </div>
                                <div class="box-inner">
                                    ${priceHTML(p)}
                                    <div class="actions">
                                        <button class="action wishlist" data-product-id="${p.id}" title="Me gusta" data-bs-toggle="modal" data-bs-target="#exampleModal-Wishlist"><i class="pe-7s-like"></i></button>
                                        <button class="action quickview" data-link-action="quickview" data-product-id="${p.id}" title="Vista rápida" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="pe-7s-look"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        const emptyMsg = '<div class="col-12 text-center py-5"><p>Aún no tienes productos en favoritos.</p><a href="/catalogo" class="btn btn-dark btn-hover-primary mt-3">Ver catálogo</a></div>';
        const g = document.getElementById('shop-grid-products');
        const l = document.getElementById('shop-list-products');
        if (products.length === 0) {
            if (g) g.innerHTML = emptyMsg;
            if (l) l.innerHTML = '<div class="text-center py-5"><p>Aún no tienes productos en favoritos.</p><a href="/catalogo" class="btn btn-dark btn-hover-primary mt-3">Ver catálogo</a></div>';
        } else {
            if (g) g.innerHTML = gridHTML;
            if (l) l.innerHTML = listHTML;
        }
        updateProductCount(products.length);
    }

    function applySort(sortType) {
        let products = [...getFilteredProducts()];
        switch (sortType) {
            case 'name-asc': products.sort((a, b) => (a.title || '').toLowerCase().localeCompare((b.title || '').toLowerCase())); break;
            case 'name-desc': products.sort((a, b) => (b.title || '').toLowerCase().localeCompare((a.title || '').toLowerCase())); break;
            case 'price-asc': products.sort((a, b) => (parseFloat(String(a.price).replace(/[^0-9.]/g, '')) || 0) - (parseFloat(String(b.price).replace(/[^0-9.]/g, '')) || 0)); break;
            case 'price-desc': products.sort((a, b) => (parseFloat(String(b.price).replace(/[^0-9.]/g, '')) || 0) - (parseFloat(String(a.price).replace(/[^0-9.]/g, '')) || 0)); break;
        }
        renderProducts(products);
    }

    function initSorting() {
        const sortItems = document.querySelectorAll('.dropdown-item[data-sort]');
        const sortBtn = document.getElementById('sort-dropdown-btn');
        sortItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const sortType = this.getAttribute('data-sort');
                const text = this.textContent.trim();
                if (sortBtn) sortBtn.innerHTML = text + ' <i class="fa fa-angle-down"></i>';
                applySort(sortType);
            });
        });
    }

    function run() {
        const products = getFilteredProducts();
        const compareEl = document.querySelector('.shop-top-bar .compare-product');
        if (compareEl) {
            compareEl.innerHTML = `<span>${products.length}</span> Productos favoritos`;
        }
        renderProducts(products);
        initSorting();
        document.addEventListener('wishlist-updated', function() { renderProducts(getFilteredProducts()); });
    }

    fetch(shopPageHTMLPath)
        .then(r => r.ok ? r.text() : Promise.reject(new Error(r.statusText)))
        .then(html => {
            insertHTML(html);
            run();
        })
        .catch(() => {
            fetch('/assets/js/components/pages/catalog/shop-page/shop-page.html')
                .then(r => r.ok ? r.text() : Promise.reject())
                .then(html => { insertHTML(html); run(); })
                .catch(() => console.error('Error al cargar shop-page.html para favoritos'));
        });
}

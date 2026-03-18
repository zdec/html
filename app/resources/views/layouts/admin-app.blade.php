@extends('layouts.app')

@push('styles')
<style>
/* Container del área admin ~20% más ancho en todos los breakpoints */
.account-dashboard .container {
    max-width: 100%;
}
@media (min-width: 576px) {
    .account-dashboard .container { max-width: 648px; }
}
@media (min-width: 768px) {
    .account-dashboard .container { max-width: 864px; }
}
@media (min-width: 992px) {
    .account-dashboard .container { max-width: 1152px; }
}
@media (min-width: 1200px) {
    .account-dashboard .container { max-width: 1368px; }
}
@media (min-width: 1400px) {
    .account-dashboard .container { max-width: 1584px; }
}

/* Botones en área admin: visibles y tamaño coherente con checkout/order-tracking */
.account-dashboard .dashboard_content .btn,
.account-dashboard .dashboard_content .table .btn {
    width: auto;
    height: auto;
    min-height: 44px;
    padding: 0.5rem 1rem;
    background-color: #212529;
    color: #fff !important;
    border: 1px solid #212529;
    border-radius: 4px;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.account-dashboard .dashboard_content .btn:hover,
.account-dashboard .dashboard_content .btn.btn-hover-primary:hover {
    background-color: #266bf9;
    border-color: #266bf9;
    color: #fff !important;
}
.account-dashboard .dashboard_content .btn-danger,
.account-dashboard .dashboard_content .btn-danger.btn-sm,
.account-dashboard .dashboard_content .order-actions-delete,
.account-dashboard .dashboard_content .remove-item.btn-danger {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #fff !important;
}
.account-dashboard .dashboard_content .btn-danger:hover,
.account-dashboard .dashboard_content .btn-danger.btn-sm:hover,
.account-dashboard .dashboard_content .order-actions-delete:hover,
.account-dashboard .dashboard_content .remove-item.btn-danger:hover {
    background-color: #bb2d3b !important;
    border-color: #bb2d3b !important;
    color: #fff !important;
}

/* Inputs type=number: anular padding 80px del tema. width sin !important para que style="width: Xpx" del HTML gane */
.account-dashboard input[type="number"].form-control {
    width: 80px;
    min-height: 31px !important;
    padding: 0.25rem 0.5rem 0.25rem 0.5rem !important;
    line-height: 1.5 !important;
    text-align: center !important;
    -webkit-appearance: textfield !important;
    appearance: textfield !important;
}
.account-dashboard input[type="number"].form-control::-webkit-outer-spin-button,
.account-dashboard input[type="number"].form-control::-webkit-inner-spin-button {
    opacity: 1 !important;
    -webkit-appearance: inner-spin-button !important;
}

/* Precios (text): anular padding grande del tema para que no oculte dígitos */
.account-dashboard .form-control.input-currency {
    padding: 0.25rem 0.5rem !important;
    min-width: 10em;
}
/* Precios cuando eran number: sin flechas */
.account-dashboard input[type="number"].form-control.input-currency {
    padding: 0.25rem 0.5rem 0.25rem 0.5rem !important;
}
.account-dashboard input[type="number"].form-control.input-currency::-webkit-outer-spin-button,
.account-dashboard input[type="number"].form-control.input-currency::-webkit-inner-spin-button {
    display: none !important;
    -webkit-appearance: none !important;
    margin: 0 !important;
}
.account-dashboard input[type="number"].form-control.input-currency {
    -moz-appearance: textfield !important;
}

/* Modal de confirmación: ancho, X dentro, botones en una línea */
#adminConfirmModal .modal-dialog { max-width: 462px; }
#adminConfirmModal .modal-content { border-radius: 8px; border: 1px solid #dee2e6; box-shadow: 0 4px 20px rgba(0,0,0,0.15); overflow: hidden; }
#adminConfirmModal .modal-header {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem 0.75rem 1.25rem;
    background: #fff;
    border-bottom: 1px solid #dee2e6;
}
#adminConfirmModal .modal-title { flex: 1; margin: 0; font-size: 1rem; min-width: 0; }
#adminConfirmModal .modal-header .btn-close {
    position: relative;
    flex-shrink: 0;
    margin: 0 26px 0 auto;
    padding: 0.25rem;
    width: 1.5rem;
    height: 1.5rem;
    background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23232529'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/0.75em auto no-repeat;
    border: 0;
    border-radius: 4px;
    opacity: 0.7;
}
#adminConfirmModal .modal-header .btn-close:hover { opacity: 1; background-color: rgba(0,0,0,0.08); }
#adminConfirmModal .modal-header .btn-close:focus { box-shadow: none; outline: none; }
#adminConfirmModal .modal-body { padding: 0.75rem 1rem; font-size: 0.95rem; }
#adminConfirmModal .modal-footer {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}
#adminConfirmModal .modal-footer .btn {
    min-height: 0;
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 4px;
    white-space: nowrap;
}
#adminConfirmModal .modal-footer .btn-outline-dark {
    background: #fff;
    color: #212529 !important;
    border: 1px solid #212529;
}
#adminConfirmModal .modal-footer .btn-outline-dark:hover {
    background: #212529;
    color: #fff !important;
}
#adminConfirmModal .modal-footer .btn-danger {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff !important;
}
#adminConfirmModal .modal-footer .btn-danger:hover {
    background: #bb2d3b;
    border-color: #bb2d3b;
    color: #fff !important;
}

/* Header lista Órdenes (siempre en layout para que aplique en carga AJAX) */
.admin-orders-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}
@media (min-width: 768px) {
    .admin-orders-header { flex-wrap: nowrap; }
    .admin-orders-header .header-right { flex-wrap: nowrap; flex-shrink: 0; }
}
.admin-orders-header h4 {
    margin: 0;
    font-size: 1.25rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    flex-shrink: 0;
}
.admin-orders-header .header-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}
@media (min-width: 768px) {
    .admin-orders-header .header-right { flex-wrap: nowrap; }
}
.admin-orders-header .filter-select {
    min-width: 140px;
    height: 44px;
    padding: 0.35rem 0.75rem;
    font-size: 0.95rem;
    border: 1px solid #212529;
    border-radius: 4px;
    background-color: #fff;
    color: #212529;
    appearance: auto;
}
.admin-orders-header .filter-select:focus {
    outline: none;
    border-color: #266bf9;
}

/* Buscador en cabecera (Clientes / Usuarios): misma línea que el título, ancho contenido */
.admin-header-with-search { flex-wrap: nowrap !important; }
.admin-header-with-search .header-right { flex-wrap: nowrap !important; flex: 1 1 auto; min-width: 0; justify-content: flex-end; }
.admin-header-with-search .admin-search-form { flex: 0 0 auto; width: 220px; }
.admin-header-with-search .admin-header-search-input { width: 100%; height: 44px; box-sizing: border-box; }
@media (max-width: 767px) {
    .admin-header-with-search { flex-wrap: wrap !important; }
    .admin-header-with-search .admin-search-form { width: 100%; }
}

/* Evitar que el tema aplique capitalize a emails y nombres (tablas e inputs) */
.account-dashboard .dashboard_content .table_page tbody td,
.account-dashboard .dashboard_content .table tbody td,
.account-dashboard .dashboard_content input[type="text"],
.account-dashboard .dashboard_content input[type="email"] {
    text-transform: none;
}
</style>
@endpush

@section('content')
{{-- Breadcrumb (estilo my-account). Sin components-loading para evitar que se oculte y reaparezca al cambiar de menú. --}}
<div class="breadcrumb-area">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title" id="admin-breadcrumb-title">@yield('admin_breadcrumb_title', 'Admin')</h2>
                <ul class="breadcrumb-list" id="admin-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    @yield('admin_breadcrumb')
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Área my-account: sidebar + contenido --}}
<div class="account-dashboard pt-100px pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="dashboard_tab_button" data-aos="fade-up" data-aos-delay="0">
                    <ul role="tablist" class="nav flex-column dashboard-list">
                        <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Órdenes</a></li>
                        @if(auth()->user()->is_admin ?? false)
                        <li><a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Productos</a></li>
                        <li><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Usuarios</a></li>
                        <li><a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">Clientes</a></li>
                        @endif
                        @if(!(auth()->user()->is_admin ?? false))
                        <li><a href="{{ route('admin.billing.index') }}" class="nav-link {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">Facturación</a></li>
                        @endif
                        <li><a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">Perfil</a></li>
                        <li>
                            <a href="#" class="nav-link" id="admin-logout-link"><i class="fa fa-sign-out me-2" aria-hidden="true"></i> Cerrar sesión</a>
                            <form id="admin-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-9">
                <div class="tab-content dashboard_content" data-aos="fade-up" data-aos-delay="200">
                    <div id="dashboard-content">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @yield('admin_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación (estilo plantilla) --}}
<div class="modal fade" id="adminConfirmModal" tabindex="-1" aria-labelledby="adminConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminConfirmModalLabel">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p id="adminConfirmModalMessage" class="mb-0">¿Continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="adminConfirmModalConfirm">Eliminar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ver órdenes del cliente --}}
<div class="modal fade" id="modalViewCustomerOrders" tabindex="-1" aria-labelledby="modalViewCustomerOrdersLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewCustomerOrdersLabel">Órdenes del cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalViewCustomerOrdersBody">
                <div class="text-center text-muted py-4">Cargando…</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var link = document.getElementById('admin-logout-link');
    var form = document.getElementById('admin-logout-form');
    if (link && form) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            form.submit();
        });
    }

    // Navegación sin recarga: cargar contenido por AJAX al hacer clic en el menú
    var contentEl = document.getElementById('dashboard-content');
    var breadcrumbTitleEl = document.getElementById('admin-breadcrumb-title');
    var breadcrumbListEl = document.getElementById('admin-breadcrumb');
    var menuContainer = document.querySelector('.dashboard_tab_button');

    function getPath(href) {
        try { return new URL(href, window.location.origin).pathname; } catch (e) { return href || ''; }
    }

    function setActiveLink(pathname) {
        if (!menuContainer) return;
        var links = menuContainer.querySelectorAll('a.nav-link');
        links.forEach(function(a) {
            if (a.id === 'admin-logout-link') return;
            if (getPath(a.getAttribute('href')) === pathname) a.classList.add('active');
            else a.classList.remove('active');
        });
    }

    function cleanupModalBackdrop() {
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
        document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
    }

    function loadAdminPage(url, pushState) {
        if (!contentEl) return;
        var fullUrl = url.indexOf('http') === 0 ? url : (window.location.origin + (url.indexOf('/') === 0 ? url : '/' + url));
        var pathname = getPath(fullUrl);
        fetch(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
            .then(function(r) { if (!r.ok) throw new Error(); return r.text(); })
            .then(function(html) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');
                var newContent = doc.getElementById('dashboard-content');
                var newTitle = doc.getElementById('admin-breadcrumb-title');
                var newBreadcrumb = doc.getElementById('admin-breadcrumb');
                if (newContent && contentEl) {
                    cleanupModalBackdrop();
                    contentEl.innerHTML = newContent.innerHTML;
                    dismissAlertsAfter(contentEl, 5000);
                }
                if (newTitle && breadcrumbTitleEl) breadcrumbTitleEl.textContent = newTitle.textContent;
                if (newBreadcrumb && breadcrumbListEl) breadcrumbListEl.innerHTML = newBreadcrumb.innerHTML;
                setActiveLink(pathname);
                if (pushState !== false) history.pushState({ adminUrl: fullUrl }, '', fullUrl);
                // Re-ejecutar scripts inline del contenido cargado (ej. órdenes)
                var scripts = contentEl.querySelectorAll('script');
                scripts.forEach(function(oldScript) {
                    var s = document.createElement('script');
                    if (oldScript.src) { s.src = oldScript.src; } else { s.textContent = oldScript.textContent; }
                    contentEl.appendChild(s);
                });
            })
            .catch(function() { window.location.href = fullUrl; });
    }

    // Delegación: un solo listener en el contenedor del menú para que siempre capture los clics
    if (menuContainer) {
        menuContainer.addEventListener('click', function(e) {
            var a = e.target && e.target.closest ? e.target.closest('a.nav-link') : null;
            if (!a) return;
            if (a.id === 'admin-logout-link') return;
            var href = a.getAttribute('href');
            if (!href || href === '#' || href.indexOf('/admin/') === -1) return;
            e.preventDefault();
            e.stopPropagation();
            loadAdminPage(href);
            return false;
        });
    }

    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.adminUrl) loadAdminPage(e.state.adminUrl, false);
    });

    // Buscador Clientes/Usuarios: al escribir, actualizar tabla por AJAX sin recargar
    var adminSearchDebounce = null;
    if (contentEl) {
        contentEl.addEventListener('input', function(e) {
            if (!e.target || !e.target.classList || !e.target.classList.contains('admin-header-search-input')) return;
            var wrapper = contentEl.querySelector('.admin-search-table-wrapper');
            if (!wrapper) return;
            var q = (e.target.value || '').trim();
            clearTimeout(adminSearchDebounce);
            adminSearchDebounce = setTimeout(function() {
                var url = window.location.pathname + (q ? '?search=' + encodeURIComponent(q) : '');
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                    .then(function(r) { return r.text(); })
                    .then(function(html) {
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        var newWrapper = doc.querySelector('.admin-search-table-wrapper');
                        if (newWrapper && wrapper) {
                            wrapper.innerHTML = newWrapper.innerHTML;
                            if (window.history && window.history.replaceState) {
                                window.history.replaceState({ adminUrl: url }, '', url);
                            }
                        }
                    })
                    .catch(function() {});
            }, 300);
        });
    }

    // Editar producto / usuario / cliente: abrir modal desde el layout para que funcione también tras carga AJAX (los @@push scripts no se re-ejecutan)
    document.addEventListener('click', function(e) {
        var btn = e.target && e.target.closest ? e.target.closest('.product-edit-trigger') : null;
        if (btn) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var url = btn.getAttribute('data-edit-form-url') || '';
            var modal = document.getElementById('modalEditProduct');
            if (modal && url) {
                modal.setAttribute('data-current-edit-url', url);
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
            return;
        }
        btn = e.target && e.target.closest ? e.target.closest('.user-edit-trigger') : null;
        if (btn) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var url = btn.getAttribute('data-edit-form-url') || '';
            var modal = document.getElementById('modalEditUser');
            if (modal && url) {
                modal.setAttribute('data-current-edit-url', url);
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
            return;
        }
        btn = e.target && e.target.closest ? e.target.closest('.customer-edit-trigger') : null;
        if (btn) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var url = btn.getAttribute('data-edit-form-url') || '';
            var modal = document.getElementById('modalEditCustomer');
            if (modal && url) {
                modal.setAttribute('data-current-edit-url', url);
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
            return;
        }
        btn = e.target && e.target.closest ? e.target.closest('.customer-view-orders') : null;
        if (btn) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var url = btn.getAttribute('data-customer-orders-url') || '';
            var modal = document.getElementById('modalViewCustomerOrders');
            if (modal && url) {
                modal.setAttribute('data-current-orders-url', url);
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
        }
    }, true);

    document.addEventListener('show.bs.modal', function(e) {
        if (!e.target) return;
        if (e.target.id === 'modalEditProduct') {
            var modal = e.target;
            var url = modal.getAttribute('data-current-edit-url') || '';
            var body = document.getElementById('modalEditProductBody');
            if (!body || !url) {
                if (body) body.innerHTML = '<p class="text-danger">No se pudo cargar el formulario.</p>';
                return;
            }
            body.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                .then(function(r) { return r.text(); })
                .then(function(html) {
                    body.innerHTML = html;
                    if (typeof initCurrencyInputs === 'function') initCurrencyInputs(body);
                    var form = body.querySelector('#form-edit-product');
                    var cancel = body.querySelector('.product-edit-form-cancel');
                    if (cancel) cancel.addEventListener('click', function(ev) { ev.preventDefault(); var m = bootstrap.Modal.getInstance(modal); if (m) m.hide(); });
                    if (form) {
                        form.addEventListener('submit', function(ev) {
                            ev.preventDefault();
                            if (typeof unformatCurrencyForSubmit === 'function') unformatCurrencyForSubmit(form);
                            var submitBtn = form.querySelector('button[type="submit"]');
                            var errorsEl = body.querySelector('#form-edit-product-errors');
                            var listEl = errorsEl ? errorsEl.querySelector('.form-edit-product-errors-list') : null;
                            if (submitBtn) submitBtn.disabled = true;
                            fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                                .then(function(res) {
                                    if (submitBtn) submitBtn.disabled = false;
                                    if (res.status === 422) {
                                        return res.json().then(function(d) {
                                            var messages = (d.errors && typeof d.errors === 'object') ? Object.values(d.errors).flat() : (d.message ? [d.message] : []);
                                            if (errorsEl && listEl) { listEl.innerHTML = messages.map(function(msg) { return '<li>' + String(msg).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</li>'; }).join(''); errorsEl.style.display = 'block'; }
                                            if (typeof showAdminFlash === 'function' && messages.length) showAdminFlash(messages.join(' '), 'error');
                                        });
                                    }
                                    if (!res.ok) return;
                                    return res.json().then(function(data) {
                                        if (data.success && data.redirect) {
                                            var m = bootstrap.Modal.getInstance(modal); if (m) m.hide();
                                            if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                                            if (typeof showAdminFlash === 'function' && data.message) showAdminFlash(data.message, 'success');
                                        }
                                    });
                                })
                                .catch(function() { if (submitBtn) submitBtn.disabled = false; });
                        });
                    }
                })
                .catch(function() { body.innerHTML = '<p class="text-danger">Error al cargar el formulario.</p>'; });
            return;
        }
        if (e.target.id === 'modalEditUser') {
            var modal = e.target;
            var url = modal.getAttribute('data-current-edit-url') || '';
            var body = document.getElementById('modalEditUserBody');
            if (!body || !url) {
                if (body) body.innerHTML = '<p class="text-danger">No se pudo cargar el formulario.</p>';
                return;
            }
            body.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                .then(function(r) { return r.text(); })
                .then(function(html) {
                    body.innerHTML = html;
                    var form = body.querySelector('#form-edit-user');
                    var cancel = body.querySelector('.user-edit-form-cancel');
                    if (cancel) cancel.addEventListener('click', function(ev) { ev.preventDefault(); var m = bootstrap.Modal.getInstance(modal); if (m) m.hide(); });
                    if (form) {
                        form.addEventListener('submit', function(ev) {
                            ev.preventDefault();
                            var submitBtn = form.querySelector('button[type="submit"]');
                            var errorsEl = body.querySelector('#form-edit-user-errors');
                            var listEl = errorsEl ? errorsEl.querySelector('.form-edit-user-errors-list') : null;
                            if (submitBtn) submitBtn.disabled = true;
                            fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                                .then(function(res) {
                                    if (submitBtn) submitBtn.disabled = false;
                                    if (res.status === 422) {
                                        return res.json().then(function(d) {
                                            var messages = (d.errors && typeof d.errors === 'object') ? Object.values(d.errors).flat() : (d.message ? [d.message] : []);
                                            if (errorsEl && listEl) { listEl.innerHTML = messages.map(function(msg) { return '<li>' + String(msg).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</li>'; }).join(''); errorsEl.style.display = 'block'; }
                                            if (typeof showAdminFlash === 'function' && messages.length) showAdminFlash(messages.join(' '), 'error');
                                        });
                                    }
                                    if (!res.ok) return;
                                    return res.json().then(function(data) {
                                        if (data.success && data.redirect) {
                                            var m = bootstrap.Modal.getInstance(modal); if (m) m.hide();
                                            if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                                            if (typeof showAdminFlash === 'function' && data.message) showAdminFlash(data.message, 'success');
                                        }
                                    });
                                })
                                .catch(function() { if (submitBtn) submitBtn.disabled = false; });
                        });
                    }
                })
                .catch(function() { body.innerHTML = '<p class="text-danger">Error al cargar el formulario.</p>'; });
            return;
        }
        if (e.target.id === 'modalEditCustomer') {
            var modal = e.target;
            var url = modal.getAttribute('data-current-edit-url') || '';
            var body = document.getElementById('modalEditCustomerBody');
            if (!body || !url) {
                if (body) body.innerHTML = '<p class="text-danger">No se pudo cargar el formulario.</p>';
                return;
            }
            body.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                .then(function(r) { return r.text(); })
                .then(function(html) {
                    body.innerHTML = html;
                    var form = body.querySelector('#form-edit-customer');
                    var cancel = body.querySelector('.customer-edit-form-cancel');
                    if (cancel) cancel.addEventListener('click', function(ev) { ev.preventDefault(); var m = bootstrap.Modal.getInstance(modal); if (m) m.hide(); });
                    if (form) {
                        form.addEventListener('submit', function(ev) {
                            ev.preventDefault();
                            var submitBtn = form.querySelector('button[type="submit"]');
                            var errorsEl = body.querySelector('#form-edit-customer-errors');
                            var listEl = errorsEl ? errorsEl.querySelector('.form-edit-customer-errors-list') : null;
                            if (submitBtn) submitBtn.disabled = true;
                            fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                                .then(function(res) {
                                    if (submitBtn) submitBtn.disabled = false;
                                    if (res.status === 422) {
                                        return res.json().then(function(d) {
                                            var messages = (d.errors && typeof d.errors === 'object') ? Object.values(d.errors).flat() : (d.message ? [d.message] : []);
                                            if (errorsEl && listEl) { listEl.innerHTML = messages.map(function(msg) { return '<li>' + String(msg).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</li>'; }).join(''); errorsEl.style.display = 'block'; }
                                            if (typeof showAdminFlash === 'function' && messages.length) showAdminFlash(messages.join(' '), 'error');
                                        });
                                    }
                                    if (!res.ok) return;
                                    return res.json().then(function(data) {
                                        if (data.success && data.redirect) {
                                            var m = bootstrap.Modal.getInstance(modal); if (m) m.hide();
                                            if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                                            if (typeof showAdminFlash === 'function' && data.message) showAdminFlash(data.message, 'success');
                                        }
                                    });
                                })
                                .catch(function() { if (submitBtn) submitBtn.disabled = false; });
                        });
                    }
                })
                .catch(function() { body.innerHTML = '<p class="text-danger">Error al cargar el formulario.</p>'; });
            return;
        }
        if (e.target.id === 'modalViewCustomerOrders') {
            var modal = e.target;
            var url = modal.getAttribute('data-current-orders-url') || '';
            var body = document.getElementById('modalViewCustomerOrdersBody');
            if (!body || !url) {
                if (body) body.innerHTML = '<p class="text-danger">No se pudo cargar las órdenes.</p>';
                return;
            }
            body.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                .then(function(r) { return r.text(); })
                .then(function(html) { body.innerHTML = html; })
                .catch(function() { body.innerHTML = '<p class="text-danger">Error al cargar las órdenes.</p>'; });
        }
    });

    // Detalle orden: recalcular subtotales y total al cambiar cantidad (delegación para que funcione tras carga AJAX)
    function formatOrderPrice(n) {
        return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
    function reindexOrderDetailRows() {
        if (!contentEl) return;
        var table = contentEl.querySelector('.table-order-detail');
        if (!table) return;
        var rows = table.querySelectorAll('tr.order-detail-row');
        rows.forEach(function(tr, idx) {
            var idInput = tr.querySelector('input[name*="[id]"]');
            var qtyInput = tr.querySelector('.order-detail-qty');
            if (idInput) idInput.name = 'items[' + idx + '][id]';
            if (qtyInput) qtyInput.name = 'items[' + idx + '][qty]';
        });
    }
    function updateOrderDetailTotals() {
        if (!contentEl) return;
        var table = contentEl.querySelector('.table-order-detail');
        if (!table) return;
        var rows = Array.prototype.slice.call(table.querySelectorAll('tr.order-detail-row'));
        var grandTotalEl = contentEl.querySelector('#order-detail-grand-total');
        if (!grandTotalEl) return;
        var toRemove = [];
        rows.forEach(function(tr) {
            var qtyInput = tr.querySelector('.order-detail-qty');
            if (!qtyInput) return;
            var qty = parseInt(qtyInput.value, 10) || 0;
            if (qty <= 0) toRemove.push(tr);
        });
        toRemove.forEach(function(tr) { tr.remove(); });
        if (toRemove.length) reindexOrderDetailRows();
        rows = table.querySelectorAll('tr.order-detail-row');
        var sum = 0;
        rows.forEach(function(tr) {
            var price = parseFloat(tr.getAttribute('data-unit-price')) || 0;
            var qtyInput = tr.querySelector('.order-detail-qty');
            var subtotalEl = tr.querySelector('.order-detail-subtotal');
            if (!qtyInput || !subtotalEl) return;
            var qty = parseInt(qtyInput.value, 10) || 0;
            if (qty < 1) return;
            var subtotal = price * qty;
            sum += subtotal;
            subtotalEl.textContent = '$' + formatOrderPrice(subtotal);
        });
        grandTotalEl.textContent = '$' + formatOrderPrice(sum);
    }
    if (contentEl) {
        contentEl.addEventListener('input', function(e) {
            if (e.target && e.target.classList && e.target.classList.contains('order-detail-qty')) {
                updateOrderDetailTotals();
            }
        });
        contentEl.addEventListener('change', function(e) {
            if (e.target && e.target.classList && e.target.classList.contains('order-detail-qty')) {
                updateOrderDetailTotals();
            }
        });
    }

    // Delegación del filtro de estado (órdenes): aplica en cada cambio tras carga AJAX
    if (contentEl) {
        contentEl.addEventListener('change', function(e) {
            if (e.target.id !== 'filter-status') return;
            var value = e.target.value;
            var url = new URL(window.location.href);
            var params = new URLSearchParams(url.search);
            for (var k of Array.from(params.keys())) {
                if (k === 'status' || k.indexOf('status') === 0) params.delete(k);
            }
            if (value) params.set('status', value);
            url.search = params.toString();
            if (window.adminLoadPage) window.adminLoadPage(url.toString());
            else window.location.href = url.toString();
        });
    }

    // Exponer para que filtros y otros enlaces del contenido puedan navegar sin recarga
    window.adminLoadPage = loadAdminPage;

    // Evitar que quede modal-open o backdrop al cerrar (varios momentos por transiciones/timeouts de Bootstrap)
    document.addEventListener('hidden.bs.modal', function() {
        cleanupModalBackdrop();
        setTimeout(cleanupModalBackdrop, 0);
        setTimeout(cleanupModalBackdrop, 150);
    });

    dismissAlertsAfter(contentEl, 5000);

    // Ocultar alerts del contenido después de 5 segundos
    function dismissAlertsAfter(container, ms) {
        ms = ms || 5000;
        if (!container) return;
        container.querySelectorAll('.alert.alert-dismissible').forEach(function(alert) {
            setTimeout(function() {
                if (alert.parentNode) {
                    var b = bootstrap.Alert.getOrCreateInstance(alert);
                    if (b) b.close();
                }
            }, ms);
        });
    }

    // Mensaje flash en el área de contenido (sin recargar)
    function showAdminFlash(message, type) {
        type = type || 'success';
        if (!contentEl) return;
        var alert = document.createElement('div');
        alert.className = 'alert alert-' + (type === 'error' ? 'danger' : type) + ' alert-dismissible fade show';
        alert.setAttribute('role', 'alert');
        alert.innerHTML = message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>';
        contentEl.insertBefore(alert, contentEl.firstChild);
        setTimeout(function() {
            if (alert.parentNode) {
                var b = bootstrap.Alert.getOrCreateInstance(alert);
                if (b) b.close();
            }
        }, 5000);
    }

    // Enlaces dentro del contenido admin: cargar por AJAX para no recargar página
    if (contentEl) {
        contentEl.addEventListener('click', function(e) {
            var a = e.target && e.target.closest ? e.target.closest('a') : null;
            if (!a || !a.href || a.getAttribute('target') === '_blank' || a.hasAttribute('download')) return;
            var path = getPath(a.href);
            if (path.indexOf('/admin/') !== 0) return;
            if (window.location.origin !== a.origin) return;
            e.preventDefault();
            loadAdminPage(a.href);
        });
    }

    // Formularios .js-ajax-form: enviar por AJAX y actualizar contenido sin recargar (delegación para que funcione tras carga AJAX)
    document.addEventListener('submit', function(e) {
        var form = e.target && e.target.tagName === 'FORM' ? e.target : null;
        if (!form || !form.classList.contains('js-ajax-form')) return;
        e.preventDefault();
        e.stopPropagation();
        var formId = form.id;
        var btn = form.querySelector('button[type="submit"]') || (formId ? document.querySelector('button[type="submit"][form="' + formId + '"]') : null);
        if (btn) btn.disabled = true;
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        }).then(function(r) {
            if (btn) btn.disabled = false;
            if (r.status === 422) {
                return r.json().then(function(d) {
                    if (d.message && typeof showAdminFlash === 'function') showAdminFlash(d.message, 'error');
                    if (d.errors && typeof showAdminFlash === 'function') showAdminFlash(Object.values(d.errors).flat().join(' '), 'error');
                });
            }
            if (!r.ok) return;
            return r.json().then(function(data) {
                if (data.success && data.redirect) {
                    if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                    if (data.message && typeof showAdminFlash === 'function') showAdminFlash(data.message, 'success');
                }
            });
        }).catch(function() { if (btn) btn.disabled = false; });
    }, true);

    // Confirmación con modal (sustituye confirm nativo)
    var confirmModal = document.getElementById('adminConfirmModal');
    var confirmMessageEl = document.getElementById('adminConfirmModalMessage');
    var confirmBtn = document.getElementById('adminConfirmModalConfirm');
    var pendingConfirmForm = null;

    if (confirmModal && confirmMessageEl && confirmBtn) {
        document.addEventListener('submit', function(e) {
            var form = e.target && e.target.tagName === 'FORM' ? e.target : null;
            if (!form) return;
            var msg = form.getAttribute('data-confirm');
            if (!msg) return;
            e.preventDefault();
            e.stopPropagation();
            pendingConfirmForm = form;
            confirmMessageEl.textContent = msg;
            var modalInstance = new bootstrap.Modal(confirmModal);
            modalInstance.show();
        }, true);

        confirmBtn.addEventListener('click', function() {
            var form = pendingConfirmForm;
            var modalInstance = bootstrap.Modal.getInstance(confirmModal);
            if (modalInstance) modalInstance.hide();
            if (!form) return;
            var isAjaxDelete = form.hasAttribute('data-ajax-delete');
            var row = form.closest('tr');
            pendingConfirmForm = null;

            if (isAjaxDelete) {
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                }).then(function(r) {
                    return r.json().then(function(data) {
                        if (r.ok && data.success && data.redirect) {
                            if (row && row.parentNode) {
                                row.remove();
                                if (typeof showAdminFlash === 'function') showAdminFlash(data.message || 'Orden eliminada.', 'success');
                            } else if (window.adminLoadPage) {
                                window.adminLoadPage(data.redirect);
                                if (typeof showAdminFlash === 'function') showAdminFlash(data.message || 'Orden eliminada.', 'success');
                            } else {
                                window.location.href = data.redirect;
                            }
                        } else if (!r.ok && data.message && typeof showAdminFlash === 'function') {
                            showAdminFlash(data.message, 'error');
                        }
                    });
                }).catch(function() {});
            } else {
                form.removeAttribute('data-confirm');
                form.submit();
            }
        });
    }
});

(function() {
    function parseCurrency(val) {
        if (val === '' || val == null) return '';
        var s = String(val).replace(/\s/g, '');
        var lastDot = s.lastIndexOf('.');
        if (lastDot >= 0) {
            var after = s.substring(lastDot + 1);
            if (after.length === 2 && /^\d{2}$/.test(after)) {
                s = s.substring(0, lastDot).replace(/\./g, '') + '.' + after;
            } else {
                s = s.replace(/\./g, '');
            }
        }
        s = s.replace(',', '.');
        var n = parseFloat(s);
        return isNaN(n) ? '' : n;
    }
    function formatCurrency(num) {
        if (num === '' || num == null || isNaN(num)) return '';
        var n = Number(num);
        var parts = n.toFixed(2).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        var out = parts.join(',');
        if (parts[1] === '00') out = parts[0];
        return out;
    }
    function initCurrencyInputs(container) {
        if (!container) return;
        container.querySelectorAll('.input-currency[data-currency]').forEach(function(inp) {
            var val = inp.value;
            if (val !== '' && !isNaN(parseCurrency(val))) inp.value = formatCurrency(parseCurrency(val));
            inp.removeEventListener('blur', inp._currencyBlur);
            inp.removeEventListener('input', inp._currencyInput);
            inp._currencyBlur = function() {
                var n = parseCurrency(this.value);
                this.value = n === '' ? '' : formatCurrency(n);
            };
            inp._currencyInput = function() {
                var n = parseCurrency(this.value);
                var start = this.selectionStart;
                this.value = n === '' ? '' : formatCurrency(n);
                this.setSelectionRange(this.value.length, this.value.length);
            };
            inp.addEventListener('blur', inp._currencyBlur);
            inp.addEventListener('input', inp._currencyInput);
        });
    }
    function unformatCurrencyForSubmit(form) {
        if (!form) return;
        form.querySelectorAll('.input-currency[data-currency]').forEach(function(inp) {
            var n = parseCurrency(inp.value);
            inp.value = n === '' ? '' : String(n);
        });
    }
    window.initCurrencyInputs = initCurrencyInputs;
    window.unformatCurrencyForSubmit = unformatCurrencyForSubmit;

    document.addEventListener('DOMContentLoaded', function() {
        initCurrencyInputs(document);
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (form && form.querySelectorAll('.input-currency[data-currency]').length) unformatCurrencyForSubmit(form);
        }, true);
    });
})();
</script>
@endpush
@endsection

@extends('layouts.app')

@push('styles')
<style>
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
.account-dashboard .dashboard_content .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}
.account-dashboard .dashboard_content .btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #bb2d3b;
    color: #fff !important;
}
</style>
@endpush

@section('content')
{{-- Breadcrumb (estilo my-account). Sin components-loading para evitar que se oculte y reaparezca al cambiar de menú. --}}
<div class="breadcrumb-area">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title">@yield('admin_breadcrumb_title', 'Admin')</h2>
                <ul class="breadcrumb-list">
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
                        <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">Órdenes</a></li>
                        @if(auth()->user()->is_admin ?? false)
                        <li><a href="{{ route('admin.orders.create') }}" class="nav-link {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">Nueva orden</a></li>
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
});
</script>
@endpush
@endsection

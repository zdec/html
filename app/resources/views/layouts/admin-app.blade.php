@extends('layouts.app')

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
                        <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show') ? 'active' : '' }}">Órdenes</a></li>
                        <li><a href="{{ route('admin.orders.create') }}" class="nav-link {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">Nueva orden</a></li>
                        <li><a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">Productos</a></li>
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

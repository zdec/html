@extends('layouts.app')

@section('title', 'Iniciar sesión - IT Secur')

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb-area">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title">Iniciar sesión</h2>
                <ul class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Login</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Login area (estilo plantilla) --}}
<div class="login-register-area pt-100px pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <a class="active" data-bs-toggle="tab" href="#lg1">
                            <h4>Login</h4>
                        </a>
                    </div>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    @if (session('status'))
                                        <div class="alert alert-success mb-3">{{ session('status') }}</div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger mb-3">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
                                        <input type="password" name="password" placeholder="Contraseña" required />
                                        <div class="button-box">
                                            <div class="login-toggle-btn">
                                                <input type="checkbox" name="remember" id="remember" />
                                                <label class="flote-none" for="remember">Recordarme</label>
                                            </div>
                                            <button type="submit"><span>Entrar</span></button>
                                        </div>
                                    </form>
                                    <p class="mt-3 mb-0">
                                        <a href="{{ route('home') }}">Volver al inicio</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Iniciar sesión - IT Secur')

@section('content')
<style>
/* Mismo aspecto que el resto de inputs del formulario (border, margen) */
.password-input-wrapper {
    display: flex;
    align-items: center;
    border: 1px solid #ebebeb;
    margin-bottom: 30px;
    background-color: transparent;
    min-height: 44px;
}
.password-input-wrapper:focus-within { border-color: #266bf9; }
.password-input-wrapper input {
    flex: 1;
    min-width: 0;
    border: none !important;
    margin: 0 !important;
    padding: 0 15px;
    background: none !important;
    font-size: 14px;
    color: #3a3a3a;
    outline: none;
}
.password-toggle-btn {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    border-left: 1px solid #ebebeb;
    cursor: pointer;
    color: #666;
    font-size: 1.1rem;
}
.password-toggle-btn:hover { color: #333; }
.password-toggle-btn i { pointer-events: none; }
/* Botón Entrar a todo el ancho del formulario */
.login-register-form .button-box button[type="submit"] {
    width: 100%;
    display: block;
}
/* Enlace "Volver al inicio" centrado bajo el botón */
.login-register-form .login-back-link {
    text-align: center;
    margin-top: 1rem;
    margin-bottom: 0;
}
</style>
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
                                        <div class="password-input-wrapper">
                                            <input type="password" name="password" id="login-password" placeholder="Contraseña" required />
                                            <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Mostrar contraseña" title="Mostrar contraseña">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div class="button-box">
                                            <div class="login-toggle-btn">
                                                <input type="hidden" name="remember" value="0">
                                                <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
                                                <label class="flote-none" for="remember">Recordarme</label>
                                            </div>
                                            <button type="submit"><span>Entrar</span></button>
                                        </div>
                                    </form>
                                    <p class="login-back-link">
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
@push('scripts')
<script>
(function() {
    var btn = document.getElementById('toggle-password');
    var input = document.getElementById('login-password');
    if (!btn || !input) return;
    btn.addEventListener('click', function() {
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            btn.setAttribute('aria-label', 'Ocultar contraseña');
            btn.setAttribute('title', 'Ocultar contraseña');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            btn.setAttribute('aria-label', 'Mostrar contraseña');
            btn.setAttribute('title', 'Mostrar contraseña');
        }
    });
})();
</script>
@endpush
@endsection

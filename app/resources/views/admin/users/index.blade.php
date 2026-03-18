@extends('layouts.admin-app')

@section('title', 'Usuarios - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Usuarios')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('admin_content')
<div class="admin-orders-header">
    <h4>Usuarios</h4>
    <div class="header-right">
        <button type="button" class="btn btn-dark btn-hover-primary" data-bs-toggle="modal" data-bs-target="#modalCreateUser">Nuevo usuario</button>
    </div>
</div>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th class="text-end"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="fw-normal">{{ $user->name }}</td>
                    <td class="fw-normal">{{ $user->email }}</td>
                    <td class="fw-normal">{{ $user->is_admin ? 'Administrador' : 'Usuario' }}</td>
                    <td class="fw-normal">
                        <span class="d-inline-flex align-items-center gap-2">
                            <button type="button" class="btn btn-dark btn-hover-primary btn-sm user-edit-trigger" data-bs-toggle="modal" data-bs-target="#modalEditUser" data-edit-form-url="{{ route('admin.users.edit-form', $user) }}">Editar</button>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" data-confirm="¿Eliminar al usuario {{ e($user->name) }}? No se puede deshacer." data-ajax-delete="1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm order-actions-delete">Eliminar</button>
                                </form>
                            @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end mt-3">
    {{ $users->withQueryString()->links() }}
</div>

{{-- Modal Nuevo usuario --}}
<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-labelledby="modalCreateUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateUserLabel">Nuevo usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @include('admin.users.partials.form-create')
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar usuario --}}
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditUserLabel">Editar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalEditUserBody">
                <div class="text-center text-muted py-4">Cargando…</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalCreateUser = document.getElementById('modalCreateUser');
    var formCreate = document.getElementById('form-create-user');
    if (modalCreateUser && formCreate && modalCreateUser.contains(formCreate)) {
        var cancelLink = formCreate.querySelector('.user-form-cancel');
        if (cancelLink) {
            cancelLink.addEventListener('click', function(e) {
                e.preventDefault();
                var m = bootstrap.Modal.getOrCreateInstance(modalCreateUser);
                if (m) m.hide();
            });
        }
        formCreate.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = formCreate.querySelector('button[type="submit"]');
            var errorsEl = document.getElementById('form-create-user-errors');
            var listEl = errorsEl ? errorsEl.querySelector('.form-create-user-errors-list') : null;
            if (btn) btn.disabled = true;
            var formData = new FormData(formCreate);
            fetch(formCreate.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            }).then(function(r) {
                if (btn) btn.disabled = false;
                if (r.status === 422) {
                    return r.json().then(function(d) {
                        var messages = (d.errors && typeof d.errors === 'object') ? Object.values(d.errors).flat() : (d.message ? [d.message] : []);
                        if (errorsEl && listEl) {
                            listEl.innerHTML = messages.map(function(msg) { return '<li>' + String(msg).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</li>'; }).join('');
                            errorsEl.style.display = 'block';
                        }
                        if (typeof showAdminFlash === 'function' && messages.length) showAdminFlash(messages.join(' '), 'error');
                    });
                }
                if (!r.ok) return;
                return r.json().then(function(data) {
                    if (data.success && data.redirect) {
                        var m = bootstrap.Modal.getInstance(modalCreateUser);
                        if (m) m.hide();
                        if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                        if (typeof showAdminFlash === 'function' && data.message) showAdminFlash(data.message, 'success');
                    }
                });
            }).catch(function() { if (btn) btn.disabled = false; });
        });
    }

    var modalEditUser = document.getElementById('modalEditUser');
    var modalEditUserBody = document.getElementById('modalEditUserBody');
    if (modalEditUser && modalEditUserBody) {
        modalEditUser.addEventListener('show.bs.modal', function(e) {
            var triggerBtn = e.relatedTarget;
            var url = triggerBtn && triggerBtn.getAttribute('data-edit-form-url');
            if (!url) return;
            modalEditUserBody.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                .then(function(r) { return r.text(); })
                .then(function(html) {
                    modalEditUserBody.innerHTML = html;
                    var form = modalEditUserBody.querySelector('#form-edit-user');
                    var cancelLink = modalEditUserBody.querySelector('.user-edit-form-cancel');
                    if (cancelLink) {
                        cancelLink.addEventListener('click', function(ev) {
                            ev.preventDefault();
                            var m = bootstrap.Modal.getInstance(modalEditUser);
                            if (m) m.hide();
                        });
                    }
                    if (form) {
                        form.addEventListener('submit', function(ev) {
                            ev.preventDefault();
                            var submitBtn = form.querySelector('button[type="submit"]');
                            var errorsEl = modalEditUserBody.querySelector('#form-edit-user-errors');
                            var listEl = errorsEl ? errorsEl.querySelector('.form-edit-user-errors-list') : null;
                            if (submitBtn) submitBtn.disabled = true;
                            var formData = new FormData(form);
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                            }).then(function(res) {
                                if (submitBtn) submitBtn.disabled = false;
                                if (res.status === 422) {
                                    return res.json().then(function(d) {
                                        var messages = (d.errors && typeof d.errors === 'object') ? Object.values(d.errors).flat() : (d.message ? [d.message] : []);
                                        if (errorsEl && listEl) {
                                            listEl.innerHTML = messages.map(function(msg) { return '<li>' + String(msg).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</li>'; }).join('');
                                            errorsEl.style.display = 'block';
                                        }
                                        if (typeof showAdminFlash === 'function' && messages.length) showAdminFlash(messages.join(' '), 'error');
                                    });
                                }
                                if (!res.ok) return;
                                return res.json().then(function(data) {
                                    if (data.success && data.redirect) {
                                        var m = bootstrap.Modal.getInstance(modalEditUser);
                                        if (m) m.hide();
                                        if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                                        if (typeof showAdminFlash === 'function' && data.message) showAdminFlash(data.message, 'success');
                                    }
                                });
                            }).catch(function() { if (submitBtn) submitBtn.disabled = false; });
                        });
                    }
                })
                .catch(function() {
                    modalEditUserBody.innerHTML = '<p class="text-danger">Error al cargar el formulario.</p>';
                });
        });
    }

});
</script>
@endpush
@endsection

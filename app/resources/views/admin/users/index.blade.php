@extends('layouts.admin-app')

@section('title', 'Usuarios - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Usuarios')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('admin_content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="mb-0">Usuarios</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-dark btn-hover-primary">Nuevo usuario</a>
</div>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin ? 'Administrador' : 'Usuario' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="view me-2">Editar</a>
                        @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 border-0" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-id="{{ $user->id }}" data-user-name="{{ e($user->name) }}">Eliminar</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $users->links() }}

{{-- Modal confirmar eliminar --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Eliminar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar al usuario <strong id="deleteUserName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteUserForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('deleteUserModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            var id = btn.getAttribute('data-user-id');
            var name = btn.getAttribute('data-user-name');
            modal.querySelector('#deleteUserName').textContent = name;
            var form = document.getElementById('deleteUserForm');
            form.action = '{{ url("admin/users") }}/' + id;
        });
    }
});
</script>
@endpush
@endsection

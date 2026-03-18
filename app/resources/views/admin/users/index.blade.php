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
                        <span class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="view">Editar</a>
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

{{ $users->links() }}
@endsection

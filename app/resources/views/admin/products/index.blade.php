@extends('layouts.admin-app')

@section('title', 'Productos - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('admin_content')
<h4>Productos (stock editable)</h4>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>${{ number_format($product->price, 0, ',', ',') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.products.update-stock', $product) }}" class="d-inline-flex align-items-center gap-2">
                            @csrf
                            <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="form-control form-control-sm default-form-box" style="width: 80px;">
                            <button type="submit" class="btn btn-dark btn-hover-primary btn-sm">Actualizar</button>
                        </form>
                    </td>
                    <td><a href="{{ route('product.show', $product->slug) }}" class="view" target="_blank">Ver</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $products->links() }}
@endsection

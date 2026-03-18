{{-- Formulario crear orden (usado en create y en modal del index) --}}
<form method="POST" action="{{ route('admin.orders.store') }}" id="form-create-order">
    @csrf
    <div id="form-create-order-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="{{ $errors->any() ? '' : 'display: none;' }}">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-create-order-errors-list">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    <div class="row mb-2">
        <div class="col-lg-6 col-md-6 mb-2 mb-md-0">
            <div class="default-form-box">
                <input type="text" class="form-control bg-light" value="—" readonly placeholder="Consecutivo">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-2 mb-md-0">
            <div class="default-form-box">
                <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
            </div>
            @error('order_date')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-6 col-md-6 mb-2 mb-md-0">
            <div class="default-form-box">
                <input type="email" name="customer_email" id="order-customer-email" class="form-control" value="{{ old('customer_email') }}" placeholder="Email" required>
            </div>
            @error('customer_email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-lg-6 col-md-6 mb-2 mb-md-0">
            <div class="default-form-box">
                <input type="text" name="customer_phone" id="order-customer-phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="Teléfono">
            </div>
            @error('customer_phone')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="default-form-box">
                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Notas del pedido">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Productos</h5>
    <div class="search-element max-width-100 order-product-search-wrap mb-3 position-relative w-100">
        <input type="text" id="order-product-search" class="form-control" placeholder="Buscar producto para agregar..." autocomplete="off" style="width: 100%; box-sizing: border-box;" />
        <div id="order-search-results" class="search-results" style="display: none;"></div>
    </div>
    <div class="table-content table-responsive">
        <table class="table table-bordered" id="order-items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Producto</th>
                    <th style="width: 100px;" class="text-center">Cantidad</th>
                    <th style="width: 100px;" class="text-center">Total</th>
                    <th style="width: 80px;"></th>
                </tr>
            </thead>
            <tbody id="items-container">
                @php $oldItems = old('items', []); @endphp
                @foreach ($oldItems as $i => $item)
                @php $pid = $item['product_id'] ?? null; $p = $products->firstWhere('id', $pid); $qty = (int)($item['qty'] ?? 1); $subtotal = $qty * $p->price; @endphp
                @if ($p)
                <tr class="item-row" data-product-id="{{ $p->id }}" data-unit-price="{{ $p->price }}">
                    <td class="item-num text-center">{{ $loop->iteration }}</td>
                    <td>
                        @if ($p->images->isNotEmpty())
                            <img src="{{ asset($p->images->first()->path) }}" alt="" class="img-fluid d-inline-block me-2" style="max-width: 50px; height: auto; vertical-align: middle;">
                        @else
                            <img src="/assets/images/products/1/1.webp" alt="" class="img-fluid d-inline-block me-2" style="max-width: 50px; height: auto; vertical-align: middle;">
                        @endif
                        <span class="d-inline-block align-middle">{{ $p->name }} - ${{ number_format($p->price, 0, ',', ',') }}</span>
                        <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $p->id }}">
                    </td>
                    <td class="text-center">
                        <input type="number" name="items[{{ $i }}][qty]" class="form-control form-control-sm item-qty mx-auto" style="width: 70px; text-align: center;" min="1" value="{{ $qty }}" required>
                    </td>
                    <td class="text-center item-total">${{ number_format($subtotal, 0, ',', ',') }}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Quitar</button></td>
                </tr>
                @endif
                @endforeach
            </tbody>
            <tfoot>
                @php $orderTotal = 0; foreach ($oldItems as $item) { $p = $products->firstWhere('id', $item['product_id'] ?? null); if ($p) $orderTotal += ((int)($item['qty'] ?? 1)) * $p->price; } @endphp
                <tr class="table-light fw-bold">
                    <td colspan="3" class="text-end">Total orden</td>
                    <td id="order-grand-total" class="text-center">${{ number_format($orderTotal, 0, ',', ',') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @error('items')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear orden</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark order-form-cancel">Cancelar</a>
    </div>
</form>

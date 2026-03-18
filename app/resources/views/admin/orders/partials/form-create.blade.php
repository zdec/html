{{-- Formulario crear orden (usado en create y en modal del index) --}}
<style>
.order-email-autocomplete-wrap .order-email-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #e0e0e0; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-height: 280px; overflow-y: auto; z-index: 1050; margin-top: 4px; }
.order-email-autocomplete-wrap .order-email-results .search-result-item { display: block; padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f5f5f5; transition: background-color 0.2s ease; font-size: 0.95rem; }
.order-email-autocomplete-wrap .order-email-results .search-result-item:last-child { border-bottom: none; }
.order-email-autocomplete-wrap .order-email-results .search-result-item:hover { background-color: #f8f9fa; }
.order-email-autocomplete-wrap .order-email-results .search-result-item.search-result-empty { color: #999; font-style: italic; cursor: default; }
</style>
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
    @php
    $customerOptions = ($customers ?? collect())->map(fn($c) => [
        'email' => $c->email,
        'name' => $c->name ?? '',
        'phone' => $c->phone ?? '',
    ])->values()->all();
@endphp
    <div class="row mb-2">
        <div class="col-lg-6 col-md-6 mb-2 mb-md-0">
            <div class="default-form-box position-relative order-email-autocomplete-wrap">
                <input type="text" name="customer_email" id="order-customer-email" class="form-control" value="{{ old('customer_email') }}" placeholder="Email" required autocomplete="off" inputmode="email">
                <div id="order-email-results" class="search-results order-email-results" style="display: none;"></div>
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

<script type="application/json" id="order-customer-options-data">{!! json_encode($customerOptions, JSON_HEX_TAG) !!}</script>
<script>
(function() {
    var form = document.getElementById('form-create-order');
    if (!form) {
        console.log('[order-email-autocomplete] No form #form-create-order found');
        return;
    }
    var dataEl = form.querySelector('#order-customer-options-data') || document.getElementById('order-customer-options-data');
    var customerOptions = [];
    if (dataEl && dataEl.textContent) {
        try {
            customerOptions = JSON.parse(dataEl.textContent.trim());
        } catch (e) {
            console.warn('[order-email-autocomplete] JSON parse error:', e);
        }
    } else {
        console.warn('[order-email-autocomplete] No data element or empty content. dataEl:', !!dataEl, 'contentLength:', dataEl ? dataEl.textContent.length : 0);
    }
    console.log('[order-email-autocomplete] Init: clientes cargados =', customerOptions.length, customerOptions.length ? 'ejemplos: ' + customerOptions.slice(0, 3).map(function(c) { return c.email; }).join(', ') : '(ninguno)');

    var emailInput = form.querySelector('#order-customer-email');
    var phoneInput = form.querySelector('#order-customer-phone');
    var resultsEl = form.querySelector('#order-email-results');
    if (!emailInput || !resultsEl) {
        console.log('[order-email-autocomplete] Missing emailInput or resultsEl');
        return;
    }

    function escapeHtml(str) {
        if (str == null) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    emailInput.addEventListener('input', function() {
        var q = (this.value || '').trim().toLowerCase();
        if (q.length < 1) {
            resultsEl.style.display = 'none';
            resultsEl.innerHTML = '';
            return;
        }
        var isEmailSearch = q.indexOf('@') !== -1;
        var list = customerOptions.filter(function(c) {
            var email = (c.email || '').trim().toLowerCase();
            if (!email) return false;
            if (email.indexOf(q) !== -1) return true;
            if (!isEmailSearch && (c.name || '').toLowerCase().indexOf(q) !== -1) return true;
            return false;
        }).slice(0, 10);
        console.log('[order-email-autocomplete] Busqueda q="' + q + '" isEmailSearch=' + isEmailSearch + ' resultados=' + list.length + ' (total opciones=' + customerOptions.length + ')');
        if (list.length === 0 && customerOptions.length > 0) {
            console.log('[order-email-autocomplete] Sin coincidencias. Primeros 3 emails en lista:', customerOptions.slice(0, 3).map(function(c) { return '"' + (c.email || '') + '"'; }).join(', '));
        }
        if (list.length === 0) {
            resultsEl.innerHTML = '<div class="search-result-item search-result-empty">Sin resultados</div>';
        } else {
            resultsEl.innerHTML = list.map(function(c) {
                var label = c.name ? (escapeHtml(c.name) + ' &lt;' + escapeHtml(c.email) + '&gt;') : escapeHtml(c.email);
                return '<div class="search-result-item order-email-result-item" data-email="' + escapeHtml(c.email) + '" data-phone="' + escapeHtml(c.phone || '') + '">' + label + '</div>';
            }).join('');
        }
        resultsEl.style.display = 'block';
    });
    emailInput.addEventListener('focus', function() {
        if (resultsEl.innerHTML) resultsEl.style.display = 'block';
    });
    document.addEventListener('click', function(e) {
        if (!form.contains(e.target) || resultsEl.contains(e.target) || e.target === emailInput) return;
        resultsEl.style.display = 'none';
    });
    resultsEl.addEventListener('click', function(e) {
        var item = e.target.closest('.order-email-result-item');
        if (!item) return;
        emailInput.value = item.getAttribute('data-email') || '';
        var phone = (item.getAttribute('data-phone') || '').trim();
        if (phoneInput && phone) phoneInput.value = phone;
        resultsEl.style.display = 'none';
        resultsEl.innerHTML = '';
    });
})();
</script>
</form>

{{-- Formulario crear orden (usado en create y en modal del index) --}}
<form method="POST" action="{{ route('admin.orders.store') }}" id="form-create-order">
    @csrf
    <h5 class="mb-3">Datos del pedido</h5>
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="default-form-box">
                <label for="email_guest">Email (invitado)</label>
                <input type="email" name="email_guest" id="email_guest" class="form-control" value="{{ old('email_guest') }}" placeholder="Email del cliente si es invitado">
            </div>
            @error('email_guest')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="default-form-box">
                <label for="notes">Notas</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}" placeholder="Notas del pedido">
            </div>
        </div>
    </div>

    <h5 class="mb-3">Items de la orden</h5>
    <div class="table-content table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="width: 120px;">Cantidad</th>
                    <th style="width: 100px;">Acción</th>
                </tr>
            </thead>
            <tbody id="items-container">
                @php $oldItems = old('items', [['product_id' => '', 'qty' => 1]]); if (empty($oldItems)) { $oldItems = [['product_id' => '', 'qty' => 1]]; } @endphp
                @foreach ($oldItems as $i => $item)
                <tr class="item-row">
                    <td>
                        <select name="items[{{ $i }}][product_id]" class="form-select form-select-sm" required>
                            <option value="">Seleccionar producto</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" data-price="{{ $p->price }}" {{ (string)$p->id === (string)($item['product_id'] ?? '') ? 'selected' : '' }}>{{ $p->name }} - ${{ number_format($p->price, 0, ',', ',') }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[{{ $i }}][qty]" class="form-control form-control-sm" min="1" value="{{ $item['qty'] ?? 1 }}" required>
                    </td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm remove-item">Quitar</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="mb-2"><button type="button" class="btn btn-outline-dark btn-sm" id="add-item">+ Añadir ítem</button></p>

    @error('items')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

    <div class="save_button mt-4 d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear orden</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Cancelar</a>
    </div>
</form>

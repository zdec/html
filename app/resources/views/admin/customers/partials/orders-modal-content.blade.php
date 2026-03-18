<p class="text-muted mb-3">Órdenes de <strong>{{ $customer->name ?? $customer->email }}</strong></p>
<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Total</th>
                <th class="text-end"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="fw-normal">{{ $order->id }}</td>
                    <td class="fw-normal">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-normal">
                        @if ($order->status === 'venta')<span class="success">Venta</span>
                        @elseif ($order->status === 'remision')<span class="success">Remisión</span>
                        @elseif ($order->status === 'pedido')<span>Pedido</span>
                        @elseif ($order->status === 'cancelled')<span class="danger">Cancelado</span>
                        @else<span>Borrador</span>
                        @endif
                    </td>
                    <td class="fw-normal">${{ number_format($order->total, 0, ',', ',') }}</td>
                    <td class="fw-normal text-end">
                        <a href="{{ route('admin.orders.show', $order) }}" class="view">Ver detalle</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No hay órdenes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

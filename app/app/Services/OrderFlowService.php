<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\SalesDocument;
use Illuminate\Support\Facades\DB;

class OrderFlowService
{
    /**
     * Generar remisión: descuenta stock si stock > 0 y crea inventory_movements.
     */
    public function generateRemision(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product->stock > 0) {
                    $qtyToDeduct = min($item->qty, $product->stock);
                    $product->decrement('stock', $qtyToDeduct);

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'quantity' => -$qtyToDeduct,
                        'type' => InventoryMovement::TYPE_REMISION_OUT,
                        'reference' => 'Remisión orden ' . ($order->document_number ?? $order->id),
                    ]);
                }
            }

            $order->update(['status' => Order::STATUS_REMISION]);
        });
    }

    /**
     * Registrar venta: crea sales_documents (obligatorio).
     * Solo descuenta stock y crea movimientos de inventario si la orden viene de pedido/borrador.
     * Si la orden ya está en remisión, el inventario ya se movió en generateRemision; no descontar de nuevo.
     */
    public function registerVenta(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $alreadyRemision = $order->status === Order::STATUS_REMISION;

            if (! $alreadyRemision) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product->stock > 0) {
                        $qtyToDeduct = min($item->qty, $product->stock);
                        $product->decrement('stock', $qtyToDeduct);

                        InventoryMovement::create([
                            'product_id' => $product->id,
                            'order_id' => $order->id,
                            'quantity' => -$qtyToDeduct,
                            'type' => InventoryMovement::TYPE_VENTA_OUT,
                            'reference' => 'Venta orden ' . ($order->document_number ?? $order->id),
                        ]);
                    }
                }
            }

            SalesDocument::create([
                'order_id' => $order->id,
                'type' => SalesDocument::TYPE_VENTA,
                'amount' => $order->total,
                'date' => now()->toDateString(),
                'reference' => 'Venta orden ' . ($order->document_number ?? $order->id),
            ]);

            $order->update(['status' => Order::STATUS_VENTA]);
        });
    }
}

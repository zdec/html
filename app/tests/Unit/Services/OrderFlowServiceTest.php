<?php

namespace Tests\Unit\Services;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\SalesDocument;
use App\Services\OrderFlowService;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class OrderFlowServiceTest extends TestCase
{
    use CreatesAdminData;

    public function test_generate_remision_deducts_stock_and_creates_movement(): void
    {
        $service = app(OrderFlowService::class);
        $customer = $this->createCustomerFor();
        $product = $this->createProduct(['stock' => 7]);
        $order = $this->createOrder($customer, [['product' => $product, 'qty' => 3]], [
            'status' => Order::STATUS_PEDIDO,
        ]);

        $service->generateRemision($order->fresh('items.product'));

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::STATUS_REMISION]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 4]);
        $this->assertDatabaseHas('inventory_movements', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'type' => InventoryMovement::TYPE_REMISION_OUT,
            'quantity' => -3,
        ]);
    }

    public function test_register_venta_from_pedido_creates_sale_doc_and_stock_movement(): void
    {
        $service = app(OrderFlowService::class);
        $customer = $this->createCustomerFor();
        $product = $this->createProduct(['stock' => 5]);
        $order = $this->createOrder($customer, [['product' => $product, 'qty' => 2]], [
            'status' => Order::STATUS_PEDIDO,
        ]);

        $service->registerVenta($order->fresh('items.product'));

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::STATUS_VENTA]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 3]);
        $this->assertDatabaseHas('inventory_movements', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'type' => InventoryMovement::TYPE_VENTA_OUT,
            'quantity' => -2,
        ]);
        $this->assertDatabaseHas('sales_documents', [
            'order_id' => $order->id,
            'type' => SalesDocument::TYPE_VENTA,
        ]);
    }
}

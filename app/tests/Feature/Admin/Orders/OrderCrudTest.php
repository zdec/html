<?php

namespace Tests\Feature\Admin\Orders;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\SalesDocument;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class OrderCrudTest extends TestCase
{
    use CreatesAdminData;

    public function test_non_admin_only_sees_own_orders_in_index(): void
    {
        $user = $this->createUser(false);
        $customer = $this->createCustomerFor($user);
        $otherCustomer = $this->createCustomerFor(null, ['email' => 'other@test.local']);

        $p1 = $this->createProduct();
        $p2 = $this->createProduct();
        $myOrder = $this->createOrder($customer, [['product' => $p1, 'qty' => 1]]);
        $this->createOrder($otherCustomer, [['product' => $p2, 'qty' => 2]]);

        $response = $this->actingAs($user)->get(route('admin.orders.index'));

        $response->assertOk();
        $orders = $response->viewData('orders');
        $orderIds = $orders->pluck('id')->all();
        $this->assertContains($myOrder->id, $orderIds);
        $this->assertNotContains(Order::where('customer_id', $otherCustomer->id)->first()->id, $orderIds);
    }

    public function test_admin_can_create_order_and_total_is_calculated(): void
    {
        $admin = $this->createUser(true);
        $product = $this->createProduct(['price' => 50000, 'stock' => 10]);

        $response = $this->actingAs($admin)->post(route('admin.orders.store'), [
            'order_date' => now()->toDateString(),
            'customer_email' => 'newcustomer@test.local',
            'customer_phone' => '3001112233',
            'items' => [
                ['product_id' => $product->id, 'qty' => 2],
            ],
            'notes' => 'Orden de prueba',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $order = Order::latest('id')->first();
        $this->assertEquals(Order::STATUS_PEDIDO, $order->status);
        $this->assertEquals(100000, (int) $order->total);
    }

    public function test_generate_remision_fails_from_invalid_status(): void
    {
        $admin = $this->createUser(true);
        $customer = $this->createCustomerFor();
        $product = $this->createProduct(['stock' => 5]);
        $order = $this->createOrder($customer, [['product' => $product, 'qty' => 1]], [
            'status' => Order::STATUS_VENTA,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.generate-remision', $order), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422)->assertJson(['success' => false]);
    }

    public function test_generate_remision_moves_inventory_and_updates_status(): void
    {
        $admin = $this->createUser(true);
        $customer = $this->createCustomerFor();
        $product = $this->createProduct(['stock' => 5]);
        $order = $this->createOrder($customer, [['product' => $product, 'qty' => 2]], [
            'status' => Order::STATUS_PEDIDO,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.generate-remision', $order), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::STATUS_REMISION]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 3]);
        $this->assertDatabaseHas('inventory_movements', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => -2,
            'type' => InventoryMovement::TYPE_REMISION_OUT,
        ]);
    }

    public function test_register_venta_from_remision_creates_sales_document_without_double_stock_movement(): void
    {
        $admin = $this->createUser(true);
        $customer = $this->createCustomerFor();
        $product = $this->createProduct(['stock' => 8]);
        $order = $this->createOrder($customer, [['product' => $product, 'qty' => 2]], [
            'status' => Order::STATUS_REMISION,
        ]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'order_id' => $order->id,
            'quantity' => -2,
            'type' => InventoryMovement::TYPE_REMISION_OUT,
            'reference' => 'seed remision',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.register-venta', $order), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::STATUS_VENTA]);
        $this->assertSame(1, InventoryMovement::where('order_id', $order->id)->count());
        $this->assertDatabaseHas('sales_documents', [
            'order_id' => $order->id,
            'type' => SalesDocument::TYPE_VENTA,
        ]);
    }
}

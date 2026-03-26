<?php

namespace Tests\Support;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait CreatesAdminData
{
    protected function createUser(bool $isAdmin = false, array $attrs = []): User
    {
        $defaults = [
            'name' => $isAdmin ? 'Admin User' : 'Cliente User',
            'email' => ($isAdmin ? 'admin' : 'user').uniqid().'@test.local',
            'password' => Hash::make('password123'),
            'is_admin' => $isAdmin,
        ];

        return User::create(array_merge($defaults, $attrs));
    }

    protected function createCustomerFor(?User $user = null, array $attrs = []): Customer
    {
        $defaults = [
            'user_id' => $user?->id,
            'email' => $attrs['email'] ?? (($user?->email) ?? ('customer'.uniqid().'@test.local')),
            'name' => $attrs['name'] ?? 'Cliente Test',
            'phone' => $attrs['phone'] ?? '3000000000',
            'address' => $attrs['address'] ?? 'Calle 123',
            'city' => $attrs['city'] ?? 'Bogota',
        ];

        return Customer::create(array_merge($defaults, $attrs));
    }

    protected function createCategory(array $attrs = []): Category
    {
        $name = $attrs['name'] ?? ('Categoria '.uniqid());

        return Category::create(array_merge([
            'name' => $name,
            'slug' => Str::slug($name).'-'.uniqid(),
        ], $attrs));
    }

    protected function createProduct(array $attrs = [], int $images = 0): Product
    {
        $category = $attrs['category_id'] ?? $this->createCategory()->id;

        $product = Product::create(array_merge([
            'category_id' => $category,
            'sku' => $attrs['sku'] ?? ('SKU-'.uniqid()),
            'slug' => $attrs['slug'] ?? ('producto-'.uniqid()),
            'name' => $attrs['name'] ?? ('Producto '.uniqid()),
            'description' => $attrs['description'] ?? 'Descripcion de prueba',
            'price' => $attrs['price'] ?? 100000,
            'old_price' => $attrs['old_price'] ?? null,
            'stock' => $attrs['stock'] ?? 10,
            'active' => $attrs['active'] ?? true,
        ], $attrs));

        for ($i = 0; $i < $images; $i++) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => "storage/products/{$product->id}/img_{$i}.jpg",
                'order' => $i,
            ]);
        }

        return $product;
    }

    protected function createOrder(Customer $customer, array $items = [], array $attrs = []): Order
    {
        $order = Order::create(array_merge([
            'customer_id' => $customer->id,
            'status' => Order::STATUS_PEDIDO,
            'total' => 0,
            'source' => 'admin',
        ], $attrs));

        $total = 0;
        foreach ($items as $item) {
            $product = $item['product'] ?? $this->createProduct();
            $qty = $item['qty'] ?? 1;
            $unitPrice = $item['unit_price'] ?? $product->price;
            $subtotal = $unitPrice * $qty;
            $total += $subtotal;
            $order->items()->create([
                'product_id' => $product->id,
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total' => $total]);

        return $order->fresh();
    }

    protected function fakeProductImage(string $name = 'image.jpg'): UploadedFile
    {
        return UploadedFile::fake()->image($name, 800, 800)->size(500);
    }
}

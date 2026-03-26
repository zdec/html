<?php

namespace Tests\Feature\Admin\Products;

use App\Models\Product;
use App\Models\ProductSearchDocument;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class ProductCrudTest extends TestCase
{
    use CreatesAdminData;

    public function test_admin_can_create_product_with_required_images(): void
    {
        Storage::fake('public');
        $admin = $this->createUser(true);
        $category = $this->createCategory();

        $payload = [
            'name' => 'Camara PTZ',
            'category_id' => $category->id,
            'sku' => 'SKU-PTZ-01',
            'description' => 'Descripcion test',
            'price' => 150000,
            'old_price' => 170000,
            'stock' => 8,
            'active' => 1,
            'image_main' => $this->fakeProductImage('main.jpg'),
            'image_gallery' => [
                $this->fakeProductImage('g1.jpg'),
                $this->fakeProductImage('g2.jpg'),
                $this->fakeProductImage('g3.jpg'),
                $this->fakeProductImage('g4.jpg'),
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.products.store'), $payload, [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $product = Product::where('sku', 'SKU-PTZ-01')->first();
        $this->assertNotNull($product);
        $this->assertEquals(5, $product->images()->count());
    }

    public function test_product_create_validates_minimum_gallery_images(): void
    {
        Storage::fake('public');
        $admin = $this->createUser(true);
        $category = $this->createCategory();

        $payload = [
            'name' => 'Switch 8 Puertos',
            'category_id' => $category->id,
            'price' => 100000,
            'stock' => 2,
            'image_main' => $this->fakeProductImage('main.jpg'),
            'image_gallery' => [
                $this->fakeProductImage('g1.jpg'),
                $this->fakeProductImage('g2.jpg'),
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.products.store'), $payload);
        $response->assertSessionHasErrors('image_gallery');
    }

    public function test_admin_can_delete_product_via_ajax(): void
    {
        $admin = $this->createUser(true);
        $product = $this->createProduct(['stock' => 0], 5);

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', ['producto' => $product]), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_admin_can_update_stock(): void
    {
        $admin = $this->createUser(true);
        $product = $this->createProduct(['stock' => 1], 5);

        $response = $this->actingAs($admin)->post(route('admin.products.update-stock', $product), [
            'stock' => 25,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 25]);
    }

    public function test_update_fails_if_images_would_be_less_than_five(): void
    {
        Storage::fake('public');
        $admin = $this->createUser(true);
        $product = $this->createProduct(['stock' => 0], 5);
        $imageIds = $product->images()->pluck('id')->all();

        $response = $this->actingAs($admin)->put(
            route('admin.products.update', ['producto' => $product]),
            [
                'name' => $product->name,
                'category_id' => $product->category_id,
                'sku' => $product->sku,
                'description' => $product->description,
                'price' => $product->price,
                'old_price' => $product->old_price,
                'stock' => $product->stock,
                'active' => 1,
                'remove_image_ids' => $imageIds,
            ]
        );

        $response->assertSessionHasErrors('image_main');
    }

    public function test_product_is_indexed_and_removed_from_knowledge_index(): void
    {
        $product = $this->createProduct([
            'name' => 'Telefono Rugerizado',
            'description' => 'Equipo resistente para campo',
            'active' => true,
        ]);

        $this->assertDatabaseHas('product_search_documents', [
            'product_id' => $product->id,
            'slug' => $product->slug,
            'active' => true,
        ]);

        $product->delete();

        $this->assertDatabaseMissing('product_search_documents', [
            'product_id' => $product->id,
        ]);
        $this->assertDatabaseMissing('product_embeddings', [
            'product_id' => $product->id,
        ]);
        $this->assertNull(ProductSearchDocument::where('product_id', $product->id)->first());
    }
}

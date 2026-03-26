<?php

namespace Tests\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait BootstrapsDatabase
{
    protected function bootstrapTestDatabase(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            throw new \RuntimeException('Los tests deben ejecutarse en SQLite en memoria.');
        }

        if (! Schema::hasTable('users')) {
            $this->createSchema();
        }

        $this->truncateAllTables();
    }

    protected function createSchema(): void
    {
        Schema::dropAllTables();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('must_change_password')->default(false);
            $table->timestamp('password_temp_created_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('sku')->nullable();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('old_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('materials')->nullable();
            $table->text('other_info')->nullable();
            $table->json('badges')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_tag', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email')->index();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('email_guest')->nullable();
            $table->string('phone_guest')->nullable();
            $table->string('status')->default('draft');
            $table->string('document_number')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('qty');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('inventory_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->integer('quantity');
            $table->string('type');
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        Schema::create('wishlist_items', function (Blueprint $table): void {
            $table->id();
            $table->string('session_id')->nullable()->index();
            $table->string('guest_token')->nullable()->index();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('chat_sessions', function (Blueprint $table): void {
            $table->id();
            $table->string('session_id')->index();
            $table->string('guest_token')->nullable()->index();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('channel')->default('web');
            $table->string('status')->default('idle');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('chat_session_id')->constrained('chat_sessions')->cascadeOnDelete();
            $table->string('role');
            $table->text('content');
            $table->string('ip_address', 45)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('product_search_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->unique();
            $table->string('slug');
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('tags')->nullable();
            $table->text('summary')->nullable();
            $table->text('searchable_text');
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->boolean('active')->default(true)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('product_embeddings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('model', 100);
            $table->json('embedding')->nullable();
            $table->timestamp('updated_at_source')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'model']);
        });
    }

    protected function truncateAllTables(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'sales_documents',
            'inventory_movements',
            'order_items',
            'orders',
            'chat_messages',
            'chat_sessions',
            'product_embeddings',
            'product_search_documents',
            'wishlist_items',
            'customers',
            'product_tag',
            'product_images',
            'products',
            'tags',
            'categories',
            'users',
        ] as $table) {
            DB::table($table)->delete();
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement("DELETE FROM sqlite_sequence WHERE name != 'migrations'");
        }

        Schema::enableForeignKeyConstraints();
    }
}

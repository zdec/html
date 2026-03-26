<?php

namespace App\Services;

class ChatContextSanitizer
{
    /**
     * @param  array<int, array<string, mixed>>  $products
     * @return array<int, array<string, mixed>>
     */
    public function sanitizeProducts(array $products): array
    {
        return array_map(function (array $product): array {
            return [
                'id' => $product['id'] ?? null,
                'name' => $product['name'] ?? null,
                'slug' => $product['slug'] ?? null,
                'category' => $product['category'] ?? null,
                'summary' => $product['summary'] ?? null,
                'price' => $product['price'] ?? null,
                'stock' => $product['stock'] ?? null,
                'active' => $product['active'] ?? null,
            ];
        }, $products);
    }

    /**
     * @param  array<string, mixed>  $orderSummary
     * @return array<string, mixed>
     */
    public function sanitizeOrderSummary(array $orderSummary): array
    {
        return [
            'total_orders' => $orderSummary['total_orders'] ?? 0,
            'last_order_id' => $orderSummary['last_order_id'] ?? null,
            'last_order_status' => $orderSummary['last_order_status'] ?? null,
            'last_order_total' => $orderSummary['last_order_total'] ?? null,
            'open_orders' => $orderSummary['open_orders'] ?? 0,
        ];
    }
}

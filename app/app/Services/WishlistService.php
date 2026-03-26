<?php

namespace App\Services;

use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WishlistService
{
    public function __construct(
        private ChatContextService $chatContextService
    ) {}

    public function toggle(Request $request, int $productId): array
    {
        $context = $this->chatContextService->resolve($request);
        $query = $this->queryByContext($context)->where('product_id', $productId);

        $existing = $query->first();
        if ($existing) {
            $existing->delete();
            Log::info('chatbot.wishlist.toggled', [
                'product_id' => $productId,
                'liked' => false,
                'customer_id' => $context['customer_id'],
                'session_id' => $context['session_id'],
            ]);
            return ['liked' => false, 'context' => $context];
        }

        WishlistItem::create([
            'session_id' => $context['session_id'],
            'guest_token' => $context['guest_token'],
            'customer_id' => $context['customer_id'],
            'product_id' => $productId,
        ]);
        Log::info('chatbot.wishlist.toggled', [
            'product_id' => $productId,
            'liked' => true,
            'customer_id' => $context['customer_id'],
            'session_id' => $context['session_id'],
        ]);

        return ['liked' => true, 'context' => $context];
    }

    public function list(Request $request): array
    {
        $context = $this->chatContextService->resolve($request);
        $query = $this->queryByContext($context)->with('product.images')->orderByDesc('id');

        $items = $query->get();

        return [
            'items' => $items->map(function (WishlistItem $item) {
            $coverImage = $item->product?->images?->first()?->path;
            return [
                'id' => $item->product_id,
                'title' => $item->product?->name,
                'price' => $item->product ? '$' . number_format((float) $item->product->price, 0, ',', ',') : null,
                'slug' => $item->product?->slug,
                'image' => $coverImage,
            ];
        })->filter(fn ($row) => ! empty($row['id']))->values()->all(),
            'context' => $context,
        ];
    }

    public function sync(Request $request, array $productIds): array
    {
        $context = $this->chatContextService->resolve($request);
        $ids = collect($productIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $query = $this->queryByContext($context);
        $existingIds = $query->pluck('product_id')->all();

        $toDelete = array_diff($existingIds, $ids);
        $toInsert = array_diff($ids, $existingIds);

        if ($toDelete !== []) {
            $this->queryByContext($context)->whereIn('product_id', $toDelete)->delete();
        }

        foreach ($toInsert as $productId) {
            WishlistItem::create([
                'session_id' => $context['session_id'],
                'guest_token' => $context['guest_token'],
                'customer_id' => $context['customer_id'],
                'product_id' => $productId,
            ]);
        }

        return ['context' => $context];
    }

    private function queryByContext(array $context)
    {
        $query = WishlistItem::query();
        if (! empty($context['customer_id'])) {
            return $query->where('customer_id', $context['customer_id']);
        }

        return $query->where('guest_token', $context['guest_token']);
    }
}

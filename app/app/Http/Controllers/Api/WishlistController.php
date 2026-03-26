<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatContextService;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(
        private WishlistService $wishlistService,
        private ChatContextService $chatContextService
    ) {}

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $result = $this->wishlistService->toggle($request, (int) $validated['product_id']);
        $listData = $this->wishlistService->list($request);

        $response = response()->json([
            'success' => true,
            'liked' => $result['liked'],
            'count' => count($listData['items']),
            'items' => $listData['items'],
        ]);

        return $this->chatContextService->withGuestCookie($response, $result['context'] ?? $listData['context']);
    }

    public function current(Request $request)
    {
        $listData = $this->wishlistService->list($request);

        $response = response()->json([
            'success' => true,
            'count' => count($listData['items']),
            'items' => $listData['items'],
        ]);

        return $this->chatContextService->withGuestCookie($response, $listData['context']);
    }

    public function sync(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ]);

        $sync = $this->wishlistService->sync($request, $validated['product_ids']);
        $listData = $this->wishlistService->list($request);

        $response = response()->json([
            'success' => true,
            'count' => count($listData['items']),
            'items' => $listData['items'],
        ]);

        return $this->chatContextService->withGuestCookie($response, $sync['context'] ?? $listData['context']);
    }
}

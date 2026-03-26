<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\ChatbotOrderService;
use App\Services\ChatAssistantService;
use App\Services\ChatContextService;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(
        private ChatContextService $chatContextService,
        private WishlistService $wishlistService,
        private ChatbotOrderService $chatbotOrderService,
        private ChatAssistantService $chatAssistantService
    ) {}

    public function startOrResume(Request $request)
    {
        $context = $this->chatContextService->resolve($request);
        $sessionId = $context['session_id'];
        $customerId = $context['customer_id'];
        $guestToken = $context['guest_token'];

        $sessionLookup = ['channel' => 'web'];
        if ($customerId) {
            $sessionLookup['customer_id'] = $customerId;
        } else {
            $sessionLookup['guest_token'] = $guestToken;
        }

        $chatSession = ChatSession::firstOrCreate(
            $sessionLookup,
            [
                'session_id' => $sessionId,
                'guest_token' => $guestToken,
                'customer_id' => $customerId,
                'ip_address' => $request->ip(),
                'status' => 'collectingWishlistContext',
                'metadata' => [],
            ]
        );

        if ($customerId && $guestToken) {
            ChatSession::where('guest_token', $guestToken)
                ->whereNull('customer_id')
                ->update(['customer_id' => $customerId]);
        }

        if (($customerId && ! $chatSession->customer_id) || ($guestToken && ! $chatSession->guest_token) || ! $chatSession->ip_address) {
            $chatSession->update([
                'customer_id' => $customerId ?: $chatSession->customer_id,
                'guest_token' => $guestToken ?: $chatSession->guest_token,
                'ip_address' => $chatSession->ip_address ?: $request->ip(),
            ]);
        }

        $wishlistData = $this->wishlistService->list($request);
        $wishlist = $wishlistData['items'];
        $history = ChatMessage::where('chat_session_id', $chatSession->id)
            ->orderBy('id')
            ->limit(80)
            ->get()
            ->map(fn (ChatMessage $message) => [
                'role' => $message->role,
                'content' => $message->content,
            ])
            ->values()
            ->all();
        $message = count($wishlist) > 0
            ? 'Hola, vi que te interesan algunos productos. Si quieres, te ayudo a crear la orden de compra.'
            : 'Hola, estoy disponible para ayudarte con productos y órdenes.';

        if (count($history) === 0) {
            ChatMessage::create([
                'chat_session_id' => $chatSession->id,
                'role' => 'bot',
                'content' => $message,
                'ip_address' => $request->ip(),
                'payload' => ['wishlist_count' => count($wishlist)],
            ]);
            Log::info('chatbot.session.prompted', [
                'chat_session_id' => $chatSession->id,
                'wishlist_count' => count($wishlist),
                'customer_id' => $customerId,
                'session_id' => $sessionId,
            ]);
            $history[] = [
                'role' => 'bot',
                'content' => $message,
            ];
        }

        $response = response()->json([
            'success' => true,
            'session_id' => $chatSession->id,
            'message' => $message,
            'wishlist' => $wishlist,
            'history' => $history,
        ]);

        return $this->chatContextService->withGuestCookie($response, $context);
    }

    public function message(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer', 'exists:chat_sessions,id'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $chatSession = ChatSession::findOrFail($validated['session_id']);
        $content = trim($validated['message']);
        $context = $this->chatContextService->resolve($request);

        $chatSession->update([
            'customer_id' => $chatSession->customer_id ?: ($context['customer_id'] ?? null),
            'guest_token' => $chatSession->guest_token ?: ($context['guest_token'] ?? null),
            'ip_address' => $chatSession->ip_address ?: $request->ip(),
        ]);

        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'user',
            'content' => $content,
            'ip_address' => $request->ip(),
            'payload' => [],
        ]);

        $assistantResult = $this->chatAssistantService->reply($chatSession, $content);
        $botReply = $assistantResult['reply'];

        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'bot',
            'content' => $botReply,
            'ip_address' => $request->ip(),
            'payload' => [
                'intent' => $assistantResult['intent'],
                'products' => $assistantResult['products'],
                'fallback_used' => $assistantResult['fallback_used'],
                'llm_used' => $assistantResult['llm_used'],
            ],
        ]);

        $response = response()->json([
            'success' => true,
            'reply' => $botReply,
            'intent' => $assistantResult['intent'],
            'products' => $assistantResult['products'],
            'fallback_used' => $assistantResult['fallback_used'],
        ]);

        return $this->chatContextService->withGuestCookie($response, $context);
    }

    public function confirmOrder(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer', 'exists:chat_sessions,id'],
            'email' => ['nullable', 'email'],
        ]);

        $chatSession = ChatSession::findOrFail($validated['session_id']);
        $context = $this->chatContextService->resolve($request);
        $chatSession->update([
            'customer_id' => $chatSession->customer_id ?: ($context['customer_id'] ?? null),
            'guest_token' => $chatSession->guest_token ?: ($context['guest_token'] ?? null),
            'ip_address' => $chatSession->ip_address ?: $request->ip(),
        ]);
        $order = $this->chatbotOrderService->createFromWishlist($request, $validated['email'] ?? null);
        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos en me gusta o falta email para crear la orden.',
            ], 422);
        }

        $chatSession->update(['status' => 'orderConfirmed']);
        $followupMessage = 'Listo, tu orden #' . $order->id . ' fue creada y te enviamos un correo para seguimiento. Para ver el estado y trazabilidad, entra por Mi cuenta: /login';
        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'bot',
            'content' => $followupMessage,
            'ip_address' => $request->ip(),
            'payload' => ['order_id' => $order->id],
        ]);
        Log::info('chatbot.order.confirmed', [
            'chat_session_id' => $chatSession->id,
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
        ]);

        $response = response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => $followupMessage,
        ]);

        return $this->chatContextService->withGuestCookie($response, $context);
    }
}

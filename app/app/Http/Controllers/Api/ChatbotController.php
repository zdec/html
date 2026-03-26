<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\ChatbotOrderService;
use App\Services\ChatContextService;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(
        private ChatContextService $chatContextService,
        private WishlistService $wishlistService,
        private ChatbotOrderService $chatbotOrderService
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
            ['session_id' => $sessionId, 'guest_token' => $guestToken, 'customer_id' => $customerId, 'status' => 'collectingWishlistContext', 'metadata' => []]
        );

        if (($customerId && ! $chatSession->customer_id) || ($guestToken && ! $chatSession->guest_token)) {
            $chatSession->update([
                'customer_id' => $customerId ?: $chatSession->customer_id,
                'guest_token' => $guestToken ?: $chatSession->guest_token,
            ]);
        }

        $wishlistData = $this->wishlistService->list($request);
        $wishlist = $wishlistData['items'];
        $message = count($wishlist) > 0
            ? 'Hola, vi que te interesan algunos productos. Si quieres, te ayudo a crear la orden de compra.'
            : 'Hola, estoy disponible para ayudarte con productos y órdenes.';

        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'bot',
            'content' => $message,
            'payload' => ['wishlist_count' => count($wishlist)],
        ]);
        Log::info('chatbot.session.prompted', [
            'chat_session_id' => $chatSession->id,
            'wishlist_count' => count($wishlist),
            'customer_id' => $customerId,
            'session_id' => $sessionId,
        ]);

        $response = response()->json([
            'success' => true,
            'session_id' => $chatSession->id,
            'message' => $message,
            'wishlist' => $wishlist,
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

        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'user',
            'content' => $content,
            'payload' => [],
        ]);

        $normalized = mb_strtolower($content);
        if (str_contains($normalized, 'tiempo de entrega') || str_contains($normalized, 'entrega') || str_contains($normalized, 'envio')) {
            $botReply = 'Cuando crees la orden, un asesor se comunicara contigo para darte las indicaciones de tiempos de entrega y despacho.';
        } elseif (str_contains($normalized, 'orden') || str_contains($normalized, 'comprar')) {
            $botReply = 'Perfecto. Para crear la orden, usa el boton de confirmar orden y yo me encargo del proceso.';
        } elseif (str_contains($normalized, 'producto') || str_contains($normalized, 'detalle') || str_contains($normalized, 'informacion')) {
            $botReply = 'Puedo ayudarte con informacion de los productos que marcaste en me gusta. Si quieres, te ayudo a crear la orden directamente.';
        } else {
            $botReply = 'Entendido. Te acompano en la compra: puedo resolver dudas de producto o crear la orden cuando me confirmes.';
        }

        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'bot',
            'content' => $botReply,
            'payload' => [],
        ]);

        $response = response()->json([
            'success' => true,
            'reply' => $botReply,
        ]);

        $context = $this->chatContextService->resolve($request);

        return $this->chatContextService->withGuestCookie($response, $context);
    }

    public function confirmOrder(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer', 'exists:chat_sessions,id'],
            'email' => ['nullable', 'email'],
        ]);

        $chatSession = ChatSession::findOrFail($validated['session_id']);
        $order = $this->chatbotOrderService->createFromWishlist($request, $validated['email'] ?? null);
        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos en me gusta o falta email para crear la orden.',
            ], 422);
        }

        $chatSession->update(['status' => 'orderConfirmed']);
        ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'role' => 'bot',
            'content' => 'Listo, tu orden #' . $order->id . ' fue creada y te enviamos un correo para seguimiento.',
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
            'message' => 'Orden creada correctamente desde el chatbot.',
        ]);

        $context = $this->chatContextService->resolve($request);

        return $this->chatContextService->withGuestCookie($response, $context);
    }
}

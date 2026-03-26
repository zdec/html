<?php

namespace App\Services;

use App\Models\ChatSession;
use App\Models\Order;
use App\Models\ProductSearchDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatAssistantService
{
    public function __construct(
        private AnthropicClientService $anthropicClient,
        private ChatContextSanitizer $sanitizer,
        private ProductKnowledgeIndexerService $indexer
    ) {}

    /**
     * @return array{reply:string,intent:string,products:array<int,array<string,mixed>>,fallback_used:bool,llm_used:bool}
     */
    public function reply(ChatSession $chatSession, string $userMessage): array
    {
        $startedAt = microtime(true);
        $intent = $this->detectIntent($userMessage);
        $products = $this->retrieveCatalogContext($userMessage);
        $isOutOfScope = $this->isOutOfScopeMessage($userMessage, $intent, count($products));
        $orderSummary = $this->buildOrderSummary($chatSession);
        $policyContext = $this->policyContext();

        $safeProducts = $this->sanitizer->sanitizeProducts($products);
        $safeOrderSummary = $this->sanitizer->sanitizeOrderSummary($orderSummary);

        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt(
            $userMessage,
            $intent,
            $safeProducts,
            $policyContext,
            $safeOrderSummary
        );

        $llmReply = null;
        if (! $isOutOfScope) {
            $llmReply = $this->anthropicClient->generate($systemPrompt, $userPrompt);
        }
        $llmUsed = $llmReply !== null;
        $fallbackUsed = ! $llmUsed;

        $reply = $llmReply ?: $this->fallbackReply($intent, $safeProducts, $policyContext, $isOutOfScope);
        $reply = $isOutOfScope ? $reply : $this->postProcess($reply, $safeProducts);

        Log::info('chatbot.assistant.interaction', [
            'chat_session_id' => $chatSession->id,
            'intent' => $intent,
            'retrieved_documents' => array_column($safeProducts, 'id'),
            'llm_used' => $llmUsed,
            'fallback_used' => $fallbackUsed,
            'out_of_scope' => $isOutOfScope,
            'latency_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ]);

        return [
            'reply' => $reply,
            'intent' => $intent,
            'products' => $safeProducts,
            'fallback_used' => $fallbackUsed,
            'llm_used' => $llmUsed,
        ];
    }

    private function detectIntent(string $message): string
    {
        $normalized = Str::lower($message);

        $isOrder = str_contains($normalized, 'orden') || str_contains($normalized, 'comprar') || str_contains($normalized, 'pedido');
        $isPolicy = str_contains($normalized, 'entrega') || str_contains($normalized, 'envio') || str_contains($normalized, 'pago') || str_contains($normalized, 'garantia');
        $isProduct = str_contains($normalized, 'producto') || str_contains($normalized, 'telefono') || str_contains($normalized, 'celular')
            || str_contains($normalized, 'precio') || str_contains($normalized, 'catalogo') || str_contains($normalized, 'stock');

        if (($isOrder && $isPolicy) || ($isOrder && $isProduct) || ($isPolicy && $isProduct)) {
            return 'mixta';
        }
        if ($isOrder) {
            return 'orden';
        }
        if ($isPolicy) {
            return 'politica';
        }
        if ($isProduct) {
            return 'producto';
        }

        return 'producto';
    }

    private function isOutOfScopeMessage(string $message, string $intent, int $matchedProducts): bool
    {
        if ($matchedProducts > 0) {
            return false;
        }

        $normalized = $this->normalizeText($message);
        $tokens = collect(preg_split('/\s+/u', $normalized) ?: [])
            ->map(fn ($token) => trim((string) $token))
            ->filter(fn ($token) => mb_strlen($token) >= 2)
            ->values();

        if ($tokens->isEmpty()) {
            return true;
        }

        $domainWords = collect([
            'producto', 'productos', 'telefono', 'telefonos', 'celular', 'celulares', 'radio', 'radios',
            'antena', 'antenas', 'gps', 'escaner', 'router', 'enrutador', 'satelital',
            'precio', 'stock', 'catalogo', 'orden', 'pedido', 'comprar', 'entrega', 'envio', 'garantia',
            'comunicacion', 'comunicaciones', 'senal', 'seguridad', 'rastreo', 'cobertura',
        ]);

        $hasDomainWord = $tokens->contains(fn (string $token): bool => $domainWords->contains($token));
        if ($hasDomainWord) {
            return false;
        }

        if ($intent !== 'producto' && $intent !== 'mixta') {
            return false;
        }

        // Mensajes muy cortos sin vocabulario de dominio suelen ser ruido/off-topic.
        if ($tokens->count() <= 2) {
            return true;
        }

        return false;
    }

    /**
     * @return array<int, array<string,mixed>>
     */
    private function retrieveCatalogContext(string $message): array
    {
        $this->ensureKnowledgeIndexIsReady();

        $normalizedMessage = $this->normalizeText($message);
        $tokens = collect(preg_split('/\s+/u', $normalizedMessage) ?: [])
            ->map(fn ($token) => trim((string) $token))
            ->filter(fn ($token) => mb_strlen($token) >= 2)
            ->flatMap(function (string $token): array {
                $variants = [$token];
                if (str_ends_with($token, 'es') && mb_strlen($token) > 4) {
                    $variants[] = mb_substr($token, 0, -2);
                }
                if (str_ends_with($token, 's') && mb_strlen($token) > 3) {
                    $variants[] = mb_substr($token, 0, -1);
                }

                return array_values(array_unique(array_filter($variants)));
            })
            ->unique()
            ->values();

        $query = ProductSearchDocument::query()->where('active', true);
        if ($tokens->isNotEmpty()) {
            $query->where(function ($subQuery) use ($tokens): void {
                foreach ($tokens as $token) {
                    $subQuery->orWhere('searchable_text', 'like', '%' . $token . '%');
                }
            });
        }

        $documents = $query->orderBy('stock', 'desc')->limit(5)->get();

        // Rescate por raíz semántica simple (ej: "comunicacion" -> "comunic...")
        if ($documents->isEmpty() && $tokens->isNotEmpty()) {
            $roots = $tokens
                ->filter(fn (string $token): bool => mb_strlen($token) >= 6)
                ->map(fn (string $token): string => mb_substr($token, 0, 6))
                ->unique()
                ->values();

            $rescueQuery = ProductSearchDocument::query()->where('active', true);
            $rescueQuery->where(function ($subQuery) use ($roots): void {
                foreach ($roots as $root) {
                    $subQuery->orWhere('searchable_text', 'like', '%' . $root . '%');
                }
            });
            $documents = $rescueQuery->orderBy('stock', 'desc')->limit(5)->get();
        }

        if ($documents->isEmpty()) {
            $documents = ProductSearchDocument::where('active', true)->orderBy('updated_at', 'desc')->limit(3)->get();
        }

        return $documents->map(function (ProductSearchDocument $doc): array {
            return [
                'id' => $doc->product_id,
                'name' => $doc->title,
                'slug' => $doc->slug,
                'category' => $doc->category,
                'summary' => $doc->summary,
                'price' => $doc->price,
                'stock' => $doc->stock,
                'active' => $doc->active,
            ];
        })->all();
    }

    private function ensureKnowledgeIndexIsReady(): void
    {
        $documentsCount = ProductSearchDocument::count();
        if ($documentsCount > 0) {
            return;
        }

        $productsCount = \App\Models\Product::count();
        if ($productsCount === 0) {
            return;
        }

        Log::info('chatbot.rag.autobootstrap_started', [
            'products_total' => $productsCount,
        ]);

        $this->indexer->rebuildAll();

        Log::info('chatbot.rag.autobootstrap_finished', [
            'documents_total' => ProductSearchDocument::count(),
        ]);
    }

    private function normalizeText(string $value): string
    {
        return Str::of($value)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9\s]/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->value();
    }

    /**
     * @return array<string,mixed>
     */
    private function buildOrderSummary(ChatSession $chatSession): array
    {
        $customerId = $chatSession->customer_id;
        if (! $customerId) {
            return [
                'total_orders' => 0,
                'last_order_id' => null,
                'last_order_status' => null,
                'last_order_total' => null,
                'open_orders' => 0,
            ];
        }

        $orders = Order::where('customer_id', $customerId)->orderByDesc('id');
        $lastOrder = (clone $orders)->first();

        return [
            'total_orders' => (clone $orders)->count(),
            'last_order_id' => $lastOrder?->id,
            'last_order_status' => $lastOrder?->status,
            'last_order_total' => $lastOrder?->total,
            'open_orders' => (clone $orders)->whereIn('status', [Order::STATUS_DRAFT, Order::STATUS_PEDIDO, Order::STATUS_REMISION])->count(),
        ];
    }

    /**
     * @return array<string,string>
     */
    private function policyContext(): array
    {
        return [
            'delivery' => 'Al crear la orden, un asesor confirma disponibilidad y tiempos de entrega.',
            'payment' => 'El método de pago y facturación se confirma durante el proceso comercial.',
            'support' => 'Si necesitas más detalle, puedes continuar por este chat o WhatsApp.',
        ];
    }

    private function buildSystemPrompt(): string
    {
        return implode("\n", [
            'Eres un asistente comercial técnico de ecommerce en español.',
            'Objetivo: ayudar con productos, políticas y órdenes sin exponer datos sensibles.',
            'Nunca inventes datos no disponibles.',
            'Si no hay coincidencia clara, pide aclaración de categoría, uso o presupuesto.',
            'Si hay productos relevantes, responde con recomendaciones concretas y accionables.',
            'Responde breve: máximo 5 líneas por bloque y evita listados largos.',
            'Dosifica la conversación: guía al cliente paso a paso con una sola siguiente acción.',
            'No revelar contraseñas, tokens, ni datos internos.',
        ]);
    }

    /**
     * @param  array<int,array<string,mixed>>  $products
     * @param  array<string,string>  $policies
     * @param  array<string,mixed>  $orderSummary
     */
    private function buildUserPrompt(
        string $message,
        string $intent,
        array $products,
        array $policies,
        array $orderSummary
    ): string {
        return json_encode([
            'intent' => $intent,
            'user_message' => $message,
            'catalog_context' => $products,
            'policy_context' => $policies,
            'order_summary' => $orderSummary,
            'response_format' => 'Respuesta corta en español (máx. 450 caracteres), clara y útil. Incluye 1 siguiente acción.',
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?: $message;
    }

    /**
     * @param  array<int,array<string,mixed>>  $products
     * @param  array<string,string>  $policies
     */
    private function fallbackReply(string $intent, array $products, array $policies, bool $isOutOfScope): string
    {
        if ($isOutOfScope) {
            return 'No tengo informacion al respecto en este momento. Puedo ayudarte con productos, precios, stock, entregas o creacion de ordenes.';
        }

        if ($intent === 'politica') {
            return $policies['delivery'] . ' ' . $policies['payment'];
        }

        if ($intent === 'orden') {
            return 'Te puedo ayudar a crear la orden ahora mismo. Si quieres, confirma y genero la orden desde tus favoritos.';
        }

        if (count($products) > 0) {
            $top = array_slice($products, 0, 3);
            $lines = array_map(function (array $product): string {
                return '- ' . ($product['name'] ?? 'Producto') . ' ($' . number_format((float) ($product['price'] ?? 0), 0, ',', '.') . ')';
            }, $top);

            return "Te recomiendo estas opciones:\n" . implode("\n", $lines) . "\nSi quieres, te ayudo a crear la orden desde el chat.";
        }

        return 'Te ayudo encantado. Cuéntame categoría, uso o presupuesto y te recomiendo productos concretos.';
    }

    /**
     * @param  array<int,array<string,mixed>>  $products
     */
    private function postProcess(string $reply, array $products): string
    {
        $reply = $this->compactReply($reply);
        $links = [];
        foreach (array_slice($products, 0, 3) as $product) {
            if (! empty($product['slug'])) {
                $links[] = '- ' . ($product['name'] ?? 'Producto') . ': /producto/' . $product['slug'];
            }
        }

        if (count($links) === 0) {
            return trim($reply);
        }

        return trim($reply) . "\n\nPuedes verlos aquí:\n" . implode("\n", $links);
    }

    private function compactReply(string $reply): string
    {
        $reply = trim($reply);
        if (mb_strlen($reply) > 520) {
            $reply = mb_substr($reply, 0, 520) . '...';
        }

        $lines = preg_split('/\R/u', $reply) ?: [];
        if (count($lines) > 8) {
            $reply = implode("\n", array_slice($lines, 0, 8));
        }

        return $reply;
    }
}

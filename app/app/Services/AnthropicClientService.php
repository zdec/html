<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnthropicClientService
{
    public function generate(string $systemPrompt, string $userPrompt): ?string
    {
        $apiKey = (string) config('services.anthropic.api_key');
        if ($apiKey === '') {
            return null;
        }

        try {
            $configuredModel = (string) config('services.anthropic.model');
            $response = $this->callAnthropic($apiKey, $configuredModel, $systemPrompt, $userPrompt);

            // Si el modelo configurado no existe en la cuenta/proyecto, intentamos con uno estable.
            if ($response->status() === 404 && data_get($response->json(), 'error.type') === 'not_found_error') {
                $fallbackModel = 'claude-sonnet-4-20250514';
                if ($configuredModel !== $fallbackModel) {
                    Log::warning('chatbot.anthropic.model_fallback', [
                        'configured_model' => $configuredModel,
                        'fallback_model' => $fallbackModel,
                    ]);
                    $response = $this->callAnthropic($apiKey, $fallbackModel, $systemPrompt, $userPrompt);
                }
            }

            if (! $response->successful()) {
                Log::warning('chatbot.anthropic.request_failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            }

            $payload = $response->json();
            $text = data_get($payload, 'content.0.text');

            return is_string($text) && $text !== '' ? trim($text) : null;
        } catch (Throwable $exception) {
            Log::warning('chatbot.anthropic.exception', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function callAnthropic(string $apiKey, string $model, string $systemPrompt, string $userPrompt): \Illuminate\Http\Client\Response
    {
        return Http::timeout(20)
            ->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => (string) config('services.anthropic.version'),
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => (int) config('services.anthropic.max_tokens'),
                'system' => $systemPrompt,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ],
                ],
            ]);
    }
}

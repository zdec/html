<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ChatContextService
{
    public const GUEST_COOKIE = 'itsecursas_guest_token';
    public const GUEST_SESSION_KEY = 'itsecursas_guest_token';
    public const GUEST_REQUEST_ATTR = 'itsecursas_guest_token';

    public function resolve(Request $request): array
    {
        $request->session()->start();

        $customerId = $request->user()?->customer?->id;
        $guestToken = null;
        $mustSetGuestCookie = false;

        if (! $customerId) {
            // Prioridad: request actual -> cookie -> session.
            $guestToken = $request->attributes->get(self::GUEST_REQUEST_ATTR)
                ?: $request->cookie(self::GUEST_COOKIE)
                ?: $request->session()->get(self::GUEST_SESSION_KEY);

            if (! $guestToken) {
                $guestToken = (string) Str::uuid();
                $mustSetGuestCookie = true;
            }

            // Persistir en request + sesión para mantener coherencia entre llamadas internas.
            $request->attributes->set(self::GUEST_REQUEST_ATTR, $guestToken);
            $request->session()->put(self::GUEST_SESSION_KEY, $guestToken);
        }

        return [
            'session_id' => $request->session()->getId(),
            'customer_id' => $customerId,
            'guest_token' => $guestToken,
            'set_guest_cookie' => $mustSetGuestCookie,
        ];
    }

    public function withGuestCookie(\Illuminate\Http\JsonResponse $response, array $context): \Illuminate\Http\JsonResponse
    {
        if (! empty($context['set_guest_cookie']) && ! empty($context['guest_token'])) {
            return $response->cookie(self::GUEST_COOKIE, $context['guest_token'], 60 * 24 * 30);
        }

        return $response;
    }
}

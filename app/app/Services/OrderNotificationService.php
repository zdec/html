<?php

namespace App\Services;

use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotificationService
{
    public function notifyOrderCreated(Order $order): bool
    {
        return $this->sendForStatus($order, Order::STATUS_PEDIDO);
    }

    public function notifyStatusChanged(Order $order, string $status): bool
    {
        return $this->sendForStatus($order, $status);
    }

    public function resendCurrentStatus(Order $order): bool
    {
        return $this->sendForStatus($order, $order->status);
    }

    protected function sendForStatus(Order $order, string $status): bool
    {
        $order->loadMissing(['customer', 'items.product']);

        $recipient = $order->customer?->email ?: $order->email_guest;
        if (! $recipient) {
            return false;
        }

        [$title, $message] = $this->textForStatus($status, $order);
        $to = strtolower($recipient);
        $mailable = new OrderStatusMail($order, $status, $title, $message);

        Log::info('order_mail.dispatch', [
            'order_id' => $order->id,
            'status' => $status,
            'to' => $to,
            'env' => app()->environment(),
        ]);

        try {
            if (app()->environment('local')) {
                Mail::to($to)->send($mailable);
                Log::info('order_mail.sent', [
                    'order_id' => $order->id,
                    'status' => $status,
                    'to' => $to,
                ]);
            } else {
                Mail::to($to)->queue($mailable);
            }
        } catch (\Throwable $e) {
            Log::error('order_mail.failed', [
                'order_id' => $order->id,
                'status' => $status,
                'to' => $to,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        }

        return true;
    }

    protected function textForStatus(string $status, Order $order): array
    {
        return match ($status) {
            Order::STATUS_PEDIDO => ['Pedido creado', 'Tu orden fue creada correctamente y se encuentra en estado pedido.'],
            Order::STATUS_REMISION => ['Remisión generada', 'Tu orden pasó a remisión y está en proceso logístico.'],
            Order::STATUS_VENTA => ['Venta confirmada', 'Tu orden fue facturada y registrada como venta.'],
            Order::STATUS_CANCELLED => ['Orden cancelada', 'Tu orden fue cancelada. Si tienes dudas, contáctanos.'],
            default => ['Actualización de orden', 'Tu orden tuvo una actualización en su estado: ' . $status . '.'],
        };
    }
}

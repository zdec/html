<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Quién puede ver el listado de órdenes (el filtrado por cliente se hace en el controlador).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Ver una orden: admin siempre; cliente solo si la orden es suya.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->is_admin) {
            return true;
        }
        $customer = $user->customer;

        return $customer && $order->customer_id === $customer->id;
    }

    /**
     * Crear orden (vista nueva orden / store): solo admin.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Gestionar flujo de orden (remisión, venta) o editar ítems: solo admin.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    /**
     * Eliminar orden: solo admin y solo si es borrador o pedido (sin movimiento).
     */
    public function delete(User $user, Order $order): bool
    {
        if (! $user->is_admin) {
            return false;
        }

        return in_array($order->status, [Order::STATUS_DRAFT, Order::STATUS_PEDIDO], true);
    }
}

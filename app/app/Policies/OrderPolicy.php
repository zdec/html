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
     * Gestionar flujo de orden (remisión, venta): solo admin.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->is_admin;
    }
}

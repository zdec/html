<?php

namespace App\Services;

use App\Mail\CustomerTemporaryPasswordMail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerAccessProvisioningService
{
    /**
     * Crea y vincula credenciales para cliente si no existen.
     * Retorna true si se generó una contraseña temporal nueva.
     */
    public function ensureCustomerCanLogin(Customer $customer): bool
    {
        if ($customer->user_id) {
            return false;
        }

        $temporaryPassword = Str::password(12, true, true, true, false);

        $user = User::create([
            'name' => $customer->name ?: 'Cliente ' . $customer->id,
            'email' => strtolower($customer->email),
            'password' => Hash::make($temporaryPassword),
            'is_admin' => false,
            'must_change_password' => true,
            'password_temp_created_at' => now(),
        ]);

        $customer->update(['user_id' => $user->id]);

        $mailable = new CustomerTemporaryPasswordMail($customer, $temporaryPassword);

        Log::info('customer_access.mail.dispatch', [
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'to' => strtolower($user->email),
            'env' => app()->environment(),
            'temporary_password' => app()->environment('local') ? $temporaryPassword : '[redacted]',
        ]);

        try {
            if (app()->environment('local')) {
                Mail::to($user->email)->send($mailable);
                Log::info('customer_access.mail.sent', [
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'to' => strtolower($user->email),
                ]);
            } else {
                Mail::to($user->email)->queue($mailable);
            }
        } catch (\Throwable $e) {
            Log::error('customer_access.mail.failed', [
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'to' => strtolower($user->email),
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        }

        return true;
    }
}

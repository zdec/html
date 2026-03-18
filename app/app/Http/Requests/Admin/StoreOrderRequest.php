<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'order_date' => ['required', 'date', 'before_or_equal:today'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_date.required' => 'La fecha es obligatoria.',
            'order_date.date' => 'La fecha no es válida.',
            'order_date.before_or_equal' => 'La fecha no puede ser futura.',
            'customer_email.required' => 'El correo electrónico es obligatorio.',
            'customer_email.email' => 'El correo electrónico no es válido.',
            'customer_phone.max' => 'El teléfono no puede superar 50 caracteres.',
            'items.required' => 'Debe agregar al menos un producto.',
            'items.min' => 'Debe agregar al menos un producto.',
            'items.*.product_id.required' => 'El producto es obligatorio.',
            'items.*.product_id.exists' => 'El producto seleccionado no es válido.',
            'items.*.qty.required' => 'La cantidad es obligatoria.',
            'items.*.qty.integer' => 'La cantidad debe ser un número entero.',
            'items.*.qty.min' => 'La cantidad debe ser al menos 1.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new ValidationException($validator);
        }
        throw new ValidationException($validator, redirect()->route('admin.orders.index')->withErrors($validator)->withInput());
    }
}

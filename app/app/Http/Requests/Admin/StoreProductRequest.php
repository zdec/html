<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sku' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'active' => ['boolean'],
            'image_main' => ['required', 'image', 'max:5120'],
            'image_gallery' => ['required', 'array', 'min:4'],
            'image_gallery.*' => ['required', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'image_gallery.required' => 'Debe subir al menos 4 imágenes para la galería.',
            'image_gallery.min' => 'La galería debe tener al menos 4 imágenes.',
        ];
    }
}

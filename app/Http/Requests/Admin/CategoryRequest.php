<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'type' => 'required|in:' . implode(',', [Category::TYPE_POST, Category::TYPE_EVENT, Category::TYPE_GENERAL]),
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Kategori',
            'description' => 'Deskripsi',
            'icon' => 'Icon',
            'color' => 'Warna',
            'type' => 'Tipe Kategori',
            'parent_id' => 'Parent Kategori',
            'order' => 'Urutan',
            'is_active' => 'Status Aktif',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
            'icon.max' => 'Icon maksimal 100 karakter.',
            'color.max' => 'Warna maksimal 20 karakter.',
            'type.required' => 'Tipe kategori wajib dipilih.',
            'type.in' => 'Tipe kategori tidak valid.',
            'parent_id.exists' => 'Parent kategori tidak valid.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox to boolean
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);

        // Set order to 0 if empty
        if (empty($this->order)) {
            $this->merge(['order' => 0]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Convert empty strings to null for nullable fields
        $nullableFields = ['description', 'icon', 'color', 'parent_id'];
        $validated = $this->validated();

        foreach ($nullableFields as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $this->replace($validated);
    }
}

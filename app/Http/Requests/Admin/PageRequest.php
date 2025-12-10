<?php
// app/Http/Requests/Admin/PageRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'template' => ['required', 'string', 'max:50'],
            'sections' => ['nullable', 'json'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:pages,id'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published'],
            'show_in_menu' => ['boolean'],
            'is_homepage' => ['boolean'],
            'custom_css' => ['nullable', 'json'],
            'custom_js' => ['nullable', 'json'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul halaman wajib diisi.',
            'template.required' => 'Template wajib dipilih.',
            'parent_id.exists' => 'Parent page tidak valid.',
        ];
    }
}
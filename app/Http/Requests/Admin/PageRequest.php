<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin middleware already handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pageId = $this->route('page') ? $this->route('page')->id : null;

        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $pageId,
            'content' => 'nullable|string',
            'template' => 'required|string|in:default,landing,contact,faq,gallery',
            'parent_id' => 'nullable|exists:pages,id',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published',
            'show_in_menu' => 'nullable|boolean',
            'is_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required.',
            'title.max' => 'Page title must not exceed 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'template.required' => 'Please select a page template.',
            'template.in' => 'Invalid template selected.',
            'parent_id.exists' => 'Selected parent page does not exist.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
            'meta_title.max' => 'Meta title must not exceed 60 characters.',
            'meta_description.max' => 'Meta description must not exceed 160 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkboxes to boolean
        if (!$this->has('show_in_menu')) {
            $this->merge(['show_in_menu' => false]);
        }

        if (!$this->has('is_homepage')) {
            $this->merge(['is_homepage' => false]);
        }
    }
}

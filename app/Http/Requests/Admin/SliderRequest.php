<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Slider;

class SliderRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:255',
            'button_style' => 'nullable|in:' . Slider::BUTTON_PRIMARY . ',' . Slider::BUTTON_SECONDARY . ',' . Slider::BUTTON_OUTLINE,
            'text_position' => 'nullable|in:' . Slider::POSITION_LEFT . ',' . Slider::POSITION_CENTER . ',' . Slider::POSITION_RIGHT,
            'overlay_color' => 'nullable|string|max:7',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'animation_effect' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'active_from' => 'nullable|date',
            'active_until' => 'nullable|date|after:active_from',
            'placement' => 'required|in:' . Slider::PLACEMENT_HOMEPAGE . ',' . Slider::PLACEMENT_EVENTS . ',' . Slider::PLACEMENT_ABOUT . ',' . Slider::PLACEMENT_ALL,
        ];

        // Image validation for create
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['image_mobile'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        } else {
            // Image validation for update (optional)
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['image_mobile'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Slider title is required.',
            'image.required' => 'Desktop image is required.',
            'image.image' => 'Desktop image must be an image file.',
            'image.mimes' => 'Desktop image must be: jpeg, png, jpg, gif, or webp.',
            'image.max' => 'Desktop image size must not exceed 2MB.',
            'image_mobile.image' => 'Mobile image must be an image file.',
            'image_mobile.mimes' => 'Mobile image must be: jpeg, png, jpg, gif, or webp.',
            'image_mobile.max' => 'Mobile image size must not exceed 2MB.',
            'button_url.url' => 'Button URL must be a valid URL.',
            'active_until.after' => 'Active until date must be after active from date.',
            'placement.required' => 'Placement is required.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox to boolean
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => false]);
        }
    }
}

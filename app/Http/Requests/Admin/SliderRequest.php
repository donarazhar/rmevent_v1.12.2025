<?php
// app/Http/Requests/Admin/SliderRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('slider') !== null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => [$isUpdate ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'image_mobile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'button_style' => ['nullable', 'in:primary,secondary,outline'],
            'text_position' => ['required', 'in:left,center,right'],
            'overlay_color' => ['nullable', 'string', 'max:7'],
            'overlay_opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'animation_effect' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'active_from' => ['nullable', 'date'],
            'active_until' => ['nullable', 'date', 'after:active_from'],
            'placement' => ['required', 'in:homepage,events,about,all'],
        ];
    }
}
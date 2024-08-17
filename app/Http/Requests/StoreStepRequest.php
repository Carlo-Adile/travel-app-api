<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStepRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'travel_id' => 'required|exists:travels,id',
            'day' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i',
            'cost' => 'nullable|numeric|between:0,999999.1999',
            'checked' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'google_maps_link' => 'nullable|url|max:255',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:55',
            'start_date' => 'sometimes|required|date_format:Y-m-d',
            'end_date' => 'sometimes|required|date_format:Y-m-d|after_or_equal:start_date',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,jfif|max:4096',
        ];
    }
    /**
     * Get the custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'La data di fine deve essere uguale o successiva alla data di inizio.',
        ];
    }
}

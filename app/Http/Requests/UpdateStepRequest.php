<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStepRequest extends FormRequest
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
        $travel = $this->route('travel');
        $travelStartDate = $travel ? $travel->start_date : now();
        $travelEndDate = $travel ? $travel->end_date : now();

        return [
            'day' => [
                'sometimes|required',
                'date',
                'after_or_equal:' . $travelStartDate,
                'before_or_equal:' . $travelEndDate,
            ],
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'time' => 'sometimes|required|date_format:H:i',
            'cost' => 'sometimes|nullable|numeric|between:0,999999.1999',
            'checked' => 'sometimes|boolean',
            'images.*' => 'sometimes|nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'google_maps_link' => 'sometimes|nullable|url|max:255',
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
            'day.after_or_equal' => 'Il giorno della tappa deve essere uguale o successivo alla data di inizio del viaggio.',
            'day.before_or_equal' => 'Il giorno della tappa deve essere uguale o precedente alla data di fine del viaggio.',
        ];
    }
}

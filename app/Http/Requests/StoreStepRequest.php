<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
        $travel = $this->route('travel');
        $travelStartDate = $travel ? $travel->start_date : now()->toDateString();
        $travelEndDate = $travel ? $travel->end_date : now()->toDateString();

        // Forzare la timezone alla data ricevuta dal frontend
        $requestDay = Carbon::createFromFormat('Y-m-d', $this->day, 'Europe/Berlin')->startOfDay();

        return [
            'travel_id' => 'required|exists:travels,id',
            'day' => [
                'required',
                'date',
                'after_or_equal:' . $travelStartDate,
                'before_or_equal:' . $travelEndDate,
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i',
            'tag' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
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
            'time.date_format' => 'Il formato dell\'orario deve essere H:i.',
            'cost.between' => 'Il costo deve essere compreso tra 0 e 999999.19.',
        ];
    }
}

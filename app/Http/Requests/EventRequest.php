<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'category' => 'required|in:Networking,Conference,Workshop,Social,Mentoring',
            'price' => 'required|numeric|min:0|max:9999.99',
            'capacity' => 'required|integer|min:1|max:10000',
            'image_url' => 'nullable|url|max:255',
            'organizer' => 'required|string|max:255',
            'featured' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingScheduleRequest extends FormRequest
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
            "milestone_name" => 'required',
            "description" => 'nullable',
            "duration" => 'required',
            "duration_unit_id" => 'required',
            "university_id" => 'required',
            "requires_submission" => 'required',
            "milestone_profile_id" => 'required',
        ];

    }
}

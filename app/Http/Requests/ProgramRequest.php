<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
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
            "name" => 'required',
            "university_id" => 'nullable|integer',
            "program_code" => "nullable",
            "description" => "required",
            "program_level_id" => "required|integer",
            "program_track_id" => "required|integer",
            "duration" => "required|integer",
            "duration_unit_id" => "required|integer",
            "milestone_profile_id" => [
                "required",
                "integer",
                Rule::exists('milestone_profiles', 'id')->where(function ($query) {
                    $query->where('university_id', request()->user()->university_id);
                }),
            ],

        ];
    }
}

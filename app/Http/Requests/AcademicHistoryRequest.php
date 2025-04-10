<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicHistoryRequest extends FormRequest
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
            "degree" => 'required',
            "institution" => 'required',
            "field_of_study" => 'required',
            "start_date" => 'required',
            "end_date" => 'required',
            "gpa" => 'nullable',
            "thesis_title" => 'nullable',
            "honors" => 'nullable',
            "user_id" => 'nullable',
        ];
    }
}

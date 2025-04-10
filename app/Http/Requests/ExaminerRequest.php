<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExaminerRequest extends FormRequest
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
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email',
            'telephone'        => 'nullable|string|max:15',
            'password'         => 'required|string|min:6',
            'university_id'    => 'required|exists:universities,id',
            "expertise"        => "nullable|string",
            "academic_credentials" => "nullable|string ",
            'examiner_code'       => "nullable|string",
            'job_title'       => "nullable|string ",
            'years_of_experience'=> "nullable|integer "
            
        ];
    }
}

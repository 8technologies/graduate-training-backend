<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string',
            'password' => 'required|string|min:6',
            'university_id' => 'nullable|exists:universities,id',
            'program_id' => 'required|exists:programs,id',
            'student_in_take_id' => 'required|exists:student_in_takes,id',
            "student_number" => 'nullable',
            'Date_of_birth' => 'nullable',
            'address' => 'nullable|string',
            'gender' => 'nullable|string',
            'sponsorship_type_id' => 'required|integer',
            'training_status' => 'required|string',
        ];
    }
}

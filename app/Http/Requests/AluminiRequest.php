<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AluminiRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password'=> 'required',
            'telephone' => 'required',
            'program_details' => 'required',
            'academic_history'=>'required',
            // 'program_id' => 'required',
            'achievements' => 'required',
        ];
    }
}

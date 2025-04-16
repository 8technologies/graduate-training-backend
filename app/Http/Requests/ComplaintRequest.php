<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'=> 'required|exists:universities,id',
            'registrar_id'=>'nullable||exists:universities,id' ,
            'supervisor_id'=> 'required|exists:universities,id',
            'subject'=> 'required|string',
            'description' => 'required|string',
            'response' => 'nullable|string',
            'status' => '',
            
        ];
    }
}

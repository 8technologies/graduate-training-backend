<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseUnitRequest extends FormRequest
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
            "university_id" => 'required',
            "program_id" => "required",
            "course_code"=> "required",
            "description" => "required",
            "training_schedules" => "required|array",
            "training_schedules.*.module_name" => "required",
            "training_schedules.*.description" => "required",
            "training_schedules.*.duration_weeks" => "required|",
            "training_schedules.*.activities" => "required|",
            "training_schedules.*.university_id" => "required|",
       
        ];
    }
}

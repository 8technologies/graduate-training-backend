<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentMilestoneSubmissionRequest extends FormRequest
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
            "milestone_id" => 'required',
            "description" => 'nullable',
            // "student_id" => 'required',
            "documents" => "required|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:20480",
            "status" => "nullable|in:pending, approved, rejected"
        ];
    }
}

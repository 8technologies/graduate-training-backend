<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Http\Requests\StudentRequest;
use App\Events\GlobalEvent;
use App\Models\Supervisor;

class StudentController extends Controller
{
    use HttpApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::where(['university_id' => $request->user()->university_id])->with('program', 'user', 'studentIntake', 'sponsorshipType', 'supervisor')->get();
        return $this->responseSuccess($students, 'Successfully retrieved all Students');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['university_id'] = $request->user()->university_id;
            $user = User::create($validated);

            $user->student()->Create(
                $validated
            );

            $user->sendEmailVerificationNotification();
            event(new GlobalEvent(
                $validated['email'],
                "Your default password is: {$validated['student_number']}. You are advised to change it.",
                'Welcome'
            ));
            return $this->responseSuccess($user, "Student created successfully", JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $student = User::findOrFail($id);
            $formattedStudent = [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'telephone' => $student->telephone,
                'student_number' => $student->student->student_number ?? 'N/A',
                'program_id' => $student->student->program->id ?? null,
                'program_name' => $student->student->program->name ?? 'N/A',
                'role_id' => $student->role_id,
                'university_name' => $student->university->name ?? 'N/A',
                'student_level_name' => $student->student->studentIntake->name ?? 'N/A', // ✅ Ensure it fetches the name
                'academic_history' => $student->student->academic_history ?? 'N/A',
                'program_details' => $student->student->program_details ?? 'N/A',
                'Date_of_birth' => $student->student->DOB ?? 'N/A',
                'address' => $student->student->address ?? 'N/A',
                'gender' => $student->student->gender ?? 'N/A',
                'scholarship_status' => $student->student->scholarship_status ?? 'N/A',
                'training_status' => $student->student->training_status ?? 'N/A',
                'sponsorship_details' => $student->student->sponsorship_details ?? 'N/A',
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ];

            return $this->responseSuccess($formattedStudent, 'Successfully retrieved student');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * (Typically not used in API controllers; provided here for completeness.)
     */
    public function edit(string $id)
    {
        try {
            $student = User::findOrFail($id);
            $formattedStudent = [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'telephone' => $student->telephone,
                'student_number' => $student->student->student_number ?? 'N/A',
                'program_id' => $student->student->program->id ?? null,
                'program_name' => $student->student->program->name ?? 'N/A',
                'role_id' => $student->role_id,
                'university_name' => $student->university->name ?? 'N/A',
                'student_level_name' => $student->student->studentIntake->name ?? 'N/A', // ✅ Ensure it fetches the name
                'academic_history' => $student->student->academic_history ?? 'N/A',
                'Date_of_birth' => $student->student->DOB ?? 'N/A',
                'address' => $student->student->address ?? 'N/A',
                'gender' => $student->student->gender ?? 'N/A',
                'scholarship_status' => $student->student->scholarship_status ?? 'N/A',
                'training_status' => $student->student->training_status ?? 'N/A',
                'sponsorship_details' => $student->student->sponsorship_details ?? 'N/A',
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ];

            return $this->responseSuccess($formattedStudent, 'Student retrieved for editing');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate request; using 'sometimes' to allow partial updates.
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'telephone' => 'nullable|string|max:15',
                'password' => 'required|string|min:6',
                'university_id' => 'required|exists:universities,id',
                'program_id' => 'required|exists:programs,id',
                'role_id' => 'required|exists:roles,id',
                'student_in_take_id' => 'required|exists:student_in_takes,id',
                'academic_history' => 'required|string', // ✅ Ensure this is validated
                "student_number" => 'required',
                'Date_of_birth' => 'nullable',
                'address' => 'nullable|string|max:15',
                'gender' => 'nullable|string|max:15',
                'scholarship_status' => 'required|integer|max:15',
                'training_status' => 'required|string|max:15',
                'sponsorship_details' => 'nullable|string|max:15',
            ]);
            $student = User::findOrFail($id);
            $student->update([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'telephone' => $validatedData['telephone'] ?? null,
                'password' => Hash::make($validatedData['password']),
                'university_id' => $validatedData['university_id'],
                'role_id' => $validatedData['role_id'],
                // 'student_level_id' => $validatedData['student_level_id'], // ✅ Ensure this is stored correctly
            ]);

            Log::info($validatedData['student_in_take_id']);
            $student->student->update(
                [
                    'user_id' => $student->id,
                    "student_in_take_id" => $validatedData['student_in_take_id'],
                    "academic_history" => $validatedData['academic_history'],
                    'DOB' => $validatedData['Date_of_birth'],
                    'address' => $validatedData['address'],
                    'gender' => $validatedData['gender'],
                    'scholarship_status' => $validatedData['scholarship_status'],
                    'training_status' => $validatedData['training_status'],
                    'sponsorship_details' => $validatedData['sponsorship_details'],
                ],
            );

            // Format the updated student for response
            $formattedStudent = [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'telephone' => $student->telephone,
                'student_number' => $student->student->student_number ?? 'N/A',
                'program_id' => $student->student->program->id ?? null,
                'program_name' => $student->student->program->name ?? 'N/A',
                'role_id' => $student->role_id,
                'university_name' => $student->university->name ?? 'N/A',
                'student_level_name' => $student->student->studentIntake->name ?? 'N/A', // ✅ Ensure it fetches the name
                'academic_history' => $student->student->academic_history ?? 'N/A',
                'Date_of_birth' => $student->student->DOB ?? 'N/A',
                'address' => $student->student->address ?? 'N/A',
                'gender' => $student->student->gender ?? 'N/A',
                'scholarship_status' => $student->student->scholarship_status ?? 'N/A',
                'training_status' => $student->student->training_status ?? 'N/A',
                'sponsorship_details' => $student->student->sponsorship_details ?? 'N/A',
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ];

            return $this->responseSuccess($formattedStudent, 'Student updated successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->user->delete();
            $student->delete();
            return $this->responseSuccess(null, 'Student deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
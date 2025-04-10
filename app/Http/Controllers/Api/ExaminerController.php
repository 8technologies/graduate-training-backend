<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExaminerRequest;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Models\AssignModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ExaminerController extends Controller
{
    use HttpApiResponseTrait;
    // public function assigned_students($supervisorId)
    // {
    //      // Check if supervisor exists
    //     $supervisorExists = AssignModel::where('supervisor_id', $supervisorId)->exists();
    //     if (!$supervisorExists) {
    //         return $this->responseError("Supervisor not found", 404);
    //     }

    //     $students = AssignModel::where(['supervisor_id' => $supervisorId])->with('student')->get();

    //     return $this->responseSuccess($students, 'Successfully retrieved all students');
    // }


    public function index()
    {
        $examiners = User::with(['university', 'program'])->where('role_id', 3)->get();

        $formattedExaminers = $examiners->map(function ($examiner) {
            return [
                'id'               => $examiner->id,
                'first_name'       => $examiner->first_name,
                'last_name'        => $examiner->last_name,
                'email'            => $examiner->email,
                'telephone'        => $examiner->telephone,
                'role_id'          => $examiner->role_id,
                'university_name'  => $examiner->university->name ?? 'N/A',
                "expertise"        => $examiner->examiner->expertise ?? 'N/A',
                "academic_credentials" => $examiner->examiner->academic_credentials ?? 'N/A',
                'examiner_code' => $examiner->examiner->examiner_code ?? 'N/A',
                'job_title'=> $examiner->examiner->job_title ?? 'N/A',
                'years_of_experience'=> $examiner->examiner->years_of_experience ?? 'N/A',
            ];
        });

        return $this->responseSuccess($formattedExaminers, 'Successfully retrieved all examiners');
    }

    /**
     * ✅ POST: Store a New Supervisor
     */
    public function store(ExaminerRequest $request) //: JsonResponse
    {
        try {
            $validatedData = $request->validated();

            // Create new supervisor
            $examiner = User::create([
                'first_name'       => $validatedData['first_name'],
                'last_name'        => $validatedData['last_name'],
                'email'            => $validatedData['email'],
                'telephone'        => $validatedData['telephone'] ?? null,
                'password'         => Hash::make($validatedData['password']),
                'university_id'    => $validatedData['university_id'],
                'role_id'          => 3, // ✅ Examiner Role
            ]);

            $examiner->examiner()->updateOrCreate(
                ['user_id' => $examiner->id,
                "expertise" => $validatedData['expertise'],
                "academic_credentials"=> $validatedData['academic_credentials'],
                "examiner_code" => $validatedData['examiner_code'], 
                'job_title'  => $validatedData['job_title'],
                'years_of_experience'  => $validatedData['years_of_experience'],
                ], 
            );

            // Load relationships
            $examiner->load(['university', 'program', 'examiner']);

            // Format response
            $formattedExaminers = [
                'id'               => $examiner->id,
                'first_name'       => $examiner->first_name,
                'last_name'        => $examiner->last_name,
                'email'            => $examiner->email,
                'telephone'        => $examiner->telephone,
                'role_id'          => $examiner->role_id,
                'university_name'  => $examiner->university->name ?? 'N/A',
                "expertise"        => $examiner->examiner->expertise ?? 'N/A',
                "academic_credentials" => $examiner->examiner->academic_credentials ?? 'N/A',
                'examiner_code' => $examiner->examiner->examiner_code ?? 'N/A',
                'job_title'=> $examiner->examiner->job_title ?? 'N/A',
                'years_of_experience'=> $examiner->examiner->years_of_experience ?? 'N/A',
            ];

            return response()->json([
                'message' => 'Examiner created successfully',
                'data'    => $formattedExaminers
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ GET: Retrieve a Specific Supervisor
     */
    public function show(string $id)
    {
        try {
            $examiner = User::with(['university', 'program', 'examiner'])->where('role_id', 3)->findOrFail($id);

            $formattedExaminers = [
                'id'               => $examiner->id,
                'first_name'       => $examiner->first_name,
                'last_name'        => $examiner->last_name,
                'email'            => $examiner->email,
                'telephone'        => $examiner->telephone,
                'role_id'          => $examiner->role_id,
                'university_name'  => $examiner->university->name ?? 'N/A',
                "expertise"        => $examiner->examiner->expertise ?? 'N/A',
                "academic_credentials" => $examiner->examiner->academic_credentials ?? 'N/A',
                'examiner_code' => $examiner->examiner->examiner_code ?? 'N/A',
                'job_title'=> $examiner->examiner->job_title ?? 'N/A',
                'years_of_experience'=> $examiner->examiner->years_of_experience ?? 'N/A',
            ];

            return $this->responseSuccess($formattedExaminers, 'Successfully retrieved supervisor');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ PUT: Update an Existing Supervisor
     */
    public function update(ExaminerRequest $request, string $id)
    {
        try {
            $examiner = User::where('role_id', 3)->findOrFail($id);

            $validatedData = $request->validated();

            $examiner->update([
                'first_name'       => $validatedData['first_name'],
                'last_name'        => $validatedData['last_name'],
                'email'            => $validatedData['email'],
                'telephone'        => $validatedData['telephone'] ?? null,
                'password'         => Hash::make($validatedData['password']),
                'university_id'    => $validatedData['university_id'],
                'role_id'          => 3, // ✅ Examiner Role
            ]);

            $examiner->examiner()->updateOrCreate(
                ['user_id' => $examiner->id,
                "expertise" => $validatedData['expertise'],
                "academic_credentials"=> $validatedData['academic_credentials'],
                "examiner_code" => $validatedData['examiner_code'], 
                'job_title'  => $validatedData['job_title'],
                'years_of_experience'  => $validatedData['years_of_experience'],
                ], 
            );


            // Load relationships
            $examiner->load(['university', 'program', 'examiner']);

            // Format the updated supervisor for response
            $formattedExaminers = [
                'id'               => $examiner->id,
                'first_name'       => $examiner->first_name,
                'last_name'        => $examiner->last_name,
                'email'            => $examiner->email,
                'telephone'        => $examiner->telephone,
                'role_id'          => $examiner->role_id,
                'university_name'  => $examiner->university->name ?? 'N/A',
                "expertise"        => $examiner->examiner->expertise ?? 'N/A',
                "academic_credentials" => $examiner->examiner->academic_credentials ?? 'N/A',
                'examiner_code' => $examiner->examiner->examiner_code ?? 'N/A',
                'job_title'=> $examiner->examiner->job_title ?? 'N/A',
                'years_of_experience'=> $examiner->examiner->years_of_experience ?? 'N/A',
            ];

            return $this->responseSuccess($formattedExaminers, 'Supervisor updated successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ DELETE: Remove a Supervisor
     */
    public function destroy(string $id)
    {
        try {
            $supervisor = User::where('role_id', 3)->findOrFail($id);
            $supervisor->delete();
            return $this->responseSuccess(null, 'Supervisor deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

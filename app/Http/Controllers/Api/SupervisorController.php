<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Models\AssignModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    use HttpApiResponseTrait;
    public function assigned_students($supervisorId)
    {
         // Check if supervisor exists
        $supervisorExists = AssignModel::where('supervisor_id', $supervisorId)->exists();
        if (!$supervisorExists) {
            return $this->responseError("Supervisor not found", 404);
        }

        $students = AssignModel::where(['supervisor_id' => $supervisorId])->with('student')->get();

        return $this->responseSuccess($students, 'Successfully retrieved all students');
    }


    // public function getAllSupervisors(){
    //     $supervisors = User::where('role_id','=',2)->get();

    //     return $this->responseSuccess($supervisors,"Succesfully retrieved all supervisors");
    // }

    public function index()
    {
        $supervisors = User::with(['university', 'program'])->where('role_id', 2)->get();

        $formattedSupervisors = $supervisors->map(function ($supervisor) {
            return [
                'id'               => $supervisor->id,
                'first_name'       => $supervisor->first_name,
                'last_name'        => $supervisor->last_name,
                'email'            => $supervisor->email,
                'telephone'        => $supervisor->telephone,
                'created_at'       => $supervisor->created_at,
                'updated_at'       => $supervisor->updated_at,
                'role_id'          => $supervisor->role_id,
                'university_name'  => $supervisor->university->name ?? 'N/A',
                "expertise"        => $supervisor->supervisor->expertise ?? 'N/A',
                "academic_credentials" => $supervisor->supervisor->academic_credentials ?? 'N/A',
                'supervisor_code' => $supervisor->supervisor->supervisor_code ?? 'N/A',
                'designation'=> $supervisor->supervisor->designation ?? 'N/A',
                'department'=> $supervisor->supervisor->department ?? 'N/A',
                'years_of_experience'=> $supervisor->supervisor->years_of_experience ?? 'N/A',
                'gender'=> $supervisor->supervisor->gender ?? 'N/A'
            ];
        });

        return $this->responseSuccess($formattedSupervisors, 'Successfully retrieved all supervisors');
    }

    /**
     * ✅ POST: Store a New Supervisor
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'first_name'       => 'required|string|max:255',
                'last_name'        => 'required|string|max:255',
                'email'            => 'required|email|unique:users,email',
                'telephone'        => 'nullable|string|max:15',
                'password'         => 'required|string|min:6',
                'university_id'    => 'required|exists:universities,id',
                "expertise"        => "nullable|string",
                "academic_credentials" => "nullable|string ",
                'supervisor_code' => "nullable|string",
                'designation'=> "nullable|string ",
                'department'=> "nullable|string ",
                'years_of_experience'=> "nullable|integer ",
                'gender'=> "nullable|string "

                // 'course_id'        => 'exists:programs,id',

            ]);

            // Create new supervisor
            $supervisor = User::create([
                'first_name'       => $validatedData['first_name'],
                'last_name'        => $validatedData['last_name'],
                'email'            => $validatedData['email'],
                'telephone'        => $validatedData['telephone'] ?? null,
                'password'         => Hash::make($validatedData['password']),
                'university_id'    => $validatedData['university_id'],

                // 'course_id'        => $validatedData['course_id'],

                'role_id'          => 2, // ✅ Supervisor Role
            ]);

            $supervisor->supervisor()->updateOrCreate(
                ['user_id' => $supervisor->id,
                "expertise" => $validatedData['expertise'],
                "academic_credentials"=> $validatedData['academic_credentials'],
                "supervisor_code" => $validatedData['supervisor_code'], 
                'designation'  => $validatedData['designation'],
                'department'  => $validatedData['department'],
                'gender'  => $validatedData['gender'],
                'years_of_experience'  => $validatedData['years_of_experience'],
                ], 
            );

            // Load relationships
            $supervisor->load(['university', 'program', 'supervisor']);

            // Format response
            $formattedSupervisor = [
                'id'               => $supervisor->id,
                'first_name'       => $supervisor->first_name,
                'last_name'        => $supervisor->last_name,
                'email'            => $supervisor->email,
                'telephone'        => $supervisor->telephone,
                'created_at'       => $supervisor->created_at,
                'updated_at'       => $supervisor->updated_at,
                'role_id'          => $supervisor->role_id,
                'university_name'  => $supervisor->university->name ?? 'N/A',
                "expertise"        => $supervisor->supervisor->expertise ?? 'N/A',
                "academic_credentials" => $supervisor->supervisor->academic_credentials ?? 'N/A',
                'supervisor_code' => $supervisor->supervisor->supervisor_code ?? 'N/A',
                'designation'=> $supervisor->supervisor->designation ?? 'N/A',
                'department'=> $supervisor->supervisor->department ?? 'N/A',
                'years_of_experience'=> $supervisor->supervisor->years_of_experience ?? 'N/A',
                'gender'=> $supervisor->supervisor->gender ?? 'N/A'
            ];

            return response()->json([
                'message' => 'Supervisor created successfully',
                'data'    => $formattedSupervisor
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
            $supervisor = User::with(['university', 'program', 'supervisor'])->where('role_id', 2)->findOrFail($id);

            $formattedSupervisor = [
                'id'               => $supervisor->id,
                'first_name'       => $supervisor->first_name,
                'last_name'        => $supervisor->last_name,
                'email'            => $supervisor->email,
                'telephone'        => $supervisor->telephone,
                'created_at'       => $supervisor->created_at,
                'updated_at'       => $supervisor->updated_at,
                'role_id'          => $supervisor->role_id,
                'university_name'  => $supervisor->university->name ?? 'N/A',
                "expertise"        => $supervisor->supervisor->expertise ?? 'N/A',
                "academic_credentials" => $supervisor->supervisor->academic_credentials ?? 'N/A',
                'supervisor_code' => $supervisor->supervisor->supervisor_code ?? 'N/A',
                'designation'=> $supervisor->supervisor->designation ?? 'N/A',
                'department'=> $supervisor->supervisor->department ?? 'N/A',
                'years_of_experience'=> $supervisor->supervisor->years_of_experience ?? 'N/A',
                'gender'=> $supervisor->supervisor->gender ?? 'N/A'
            ];

            return $this->responseSuccess($formattedSupervisor, 'Successfully retrieved supervisor');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ PUT: Update an Existing Supervisor
     */
    public function update(Request $request, string $id)
    {
        try {
            $supervisor = User::where('role_id', 2)->findOrFail($id);

            $validatedData = $request->validate([
                'first_name'       => 'required|string|max:255',
                'last_name'        => 'required|string|max:255',
                'email'            => 'required|email',
                'telephone'        => 'nullable|string|max:15',
                'password'         => 'required|string|min:6',
                'university_id'    => 'required|exists:universities,id',
                "expertise"        => "nullable|string",
                "academic_credentials" => "nullable|string ",
                'supervisor_code' => "nullable|string",
                'designation'=> "nullable|string ",
                'department'=> "nullable|string ",
                'years_of_experience'=> "nullable|integer ",
                'gender'=> "nullable|string "

                // 'course_id'        => 'exists:programs,id',

            ]);

            $supervisor->update([
                'first_name'       => $validatedData['first_name'],
                'last_name'        => $validatedData['last_name'],
                'email'            => $validatedData['email'],
                'telephone'        => $validatedData['telephone'] ?? null,
                'password'         => Hash::make($validatedData['password']),
                'university_id'    => $validatedData['university_id'],

                'role_id'          => 2, // ✅ Supervisor Role
            ]);

            $supervisor->supervisor()->updateOrCreate(
                ['user_id' => $supervisor->id,
                "expertise" => $validatedData['expertise'],
                "academic_credentials"=> $validatedData['academic_credentials'],
                "supervisor_code" => $validatedData['supervisor_code'], 
                'designation'  => $validatedData['designation'],
                'department'  => $validatedData['department'],
                'gender'  => $validatedData['gender'],
                'years_of_experience'  => $validatedData['years_of_experience'],
                ], 
            );


            // Load relationships
            $supervisor->load(['university', 'program', 'supervisor']);

            // Format the updated supervisor for response
            $formattedSupervisor = [
                'id'               => $supervisor->id,
                'first_name'       => $supervisor->first_name,
                'last_name'        => $supervisor->last_name,
                'email'            => $supervisor->email,
                'telephone'        => $supervisor->telephone,
                'created_at'       => $supervisor->created_at,
                'updated_at'       => $supervisor->updated_at,
                'role_id'          => $supervisor->role_id,
                'university_name'  => $supervisor->university->name ?? 'N/A',
                "expertise"        => $supervisor->supervisor->expertise ?? 'N/A',
                "academic_credentials" => $supervisor->supervisor->academic_credentials ?? 'N/A',
                'supervisor_code' => $supervisor->supervisor->supervisor_code ?? 'N/A',
                'designation'=> $supervisor->supervisor->designation ?? 'N/A',
                'department'=> $supervisor->supervisor->department ?? 'N/A',
                'years_of_experience'=> $supervisor->supervisor->years_of_experience ?? 'N/A',
                'gender'=> $supervisor->supervisor->gender ?? 'N/A'
            ];

            return $this->responseSuccess($formattedSupervisor, 'Supervisor updated successfully');
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
            $supervisor = User::where('role_id', 2)->findOrFail($id);
            $supervisor->delete();
            return $this->responseSuccess(null, 'Supervisor deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

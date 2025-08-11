<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Http\Requests\AluminiRequest;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;

use App\Http\Requests\StudentRequest;
use App\Events\GlobalEvent;

class AlumniController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $students = Student::where(['university_id' => 1])
        ->whereHas('user.roles', function ($query) {
            $query->where('name', 'alumni'); // or whereIn if multiple roles
        })
       
        ->with('program', 'user', 'studentIntake', 'sponsorshipType', 'supervisor', 'user.assignedExaminers')->get();
        return $this->responseSuccess($students, 'Successfully retrieved all alumni');
    }


    /**
     * Store a newly created resource in storage.
     */

        public function store(StudentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['university_id'] = 1;
            $user = User::create($validated);

            $user->student()->Create(
                $validated
            );

            $user->assignRole('alumni');

            $user->sendEmailVerificationNotification();
            event(new GlobalEvent(
                $validated['email'],
                "Your default password is: {$validated['student_number']}. You are advised to change it.",
                'Welcome'
            ));
            return $this->responseSuccess($user, "Alumni created successfully", JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alumni = User::findOrFail($id)->with('student')->get();
        return $this->responseSuccess($alumni, 'Successfully retrieved this university\'s data');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AluminiRequest $request, string $id)
    {
        $validated = $request->validated();
        $user = User::find($id);
        
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
        ]);
        $user->student()->updateOrCreate(
            ['user_id' => $user->id,
            "program_details" => $validated['program_details'],
            "academic_history"=> $validated['academic_history'], 
            'achievements' => $validated['achievements'], 
        ], // Condition
           
        );

        return $this->responseSuccess('Alumni data updated successfully',  $user);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alumni = User::find($id);

        $alumni->student()->delete();

        return $this->responseSuccess('Alumni deleted successfully',  $alumni);

    }

     // Approve Alumni
    public function approve(Request $request, $id)
    {
        // Ensure the authenticated user is an admin
        /* if (!$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        } */

        // Find alumni profile
        $alumniProfile = Student::find($id);

        if (!$alumniProfile) {
            return response()->json([
                'message' => 'Alumni profile not found'
            ], 404);
        }

        // Approve alumni
        $alumniProfile->training_status = 'completed';
        $alumniProfile->save();

        return response()->json([
            'message' => 'Alumni approved successfully',
            'alumni' => $alumniProfile
        ]);
    }
}

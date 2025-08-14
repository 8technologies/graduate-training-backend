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
    public function updateAlumni(Request $request, $id)
{
    // Validate input
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'telephone' => 'nullable|string|max:255',
        'avatar' => 'nullable|string|max:255',
        'DOB' => 'nullable|date',
        'address' => 'nullable|string|max:255',
        'gender' => 'nullable|in:male,female',
        'achievements' => 'nullable|string',
        'professional_growth' => 'nullable|string',
        'graduation_year' => 'nullable|integer|min:1950|max:' . date('Y'),
        'about' => 'nullable|string',
        'company' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
    ]);

    // Find the alumni student record
    $student = Student::findOrFail($id);

    // Find related user record
    $user = User::findOrFail($student->user_id);

    // Update user details
    $user->first_name = $validated['first_name'];
    $user->last_name = $validated['last_name'];
    $user->email = $validated['email'];
    $user->telephone = $validated['telephone'] ?? $user->telephone;
    $user->avatar = $validated['avatar'] ?? $user->avatar;
    $user->save();

    // Update student details
    $student->DOB = $validated['DOB'] ?? $student->DOB;
    $student->address = $validated['address'] ?? $student->address;
    $student->gender = $validated['gender'] ?? $student->gender;
    $student->achievements = $validated['achievements'] ?? $student->achievements;
    $student->professional_growth = $validated['professional_growth'] ?? $student->professional_growth;
    $student->graduation_year = $validated['graduation_year'] ?? $student->graduation_year;
    $student->about = $validated['about'] ?? $student->about;
    $student->company = $validated['company'] ?? $student->company;
    $student->position = $validated['position'] ?? $student->position;
    $student->save();

    return response()->json([
        'message' => 'Alumni updated successfully',
        'user' => $user,
        'student' => $student
    ]);
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

    public function reject(Request $request, $id)
    {
        
        // Find alumni profile
        $alumniProfile = Student::find($id);

        if (!$alumniProfile) {
            return response()->json([
                'message' => 'Alumni profile not found'
            ], 404);
        }

        // Approve alumni
        $alumniProfile->training_status = 'rejected';
        $alumniProfile->save();

        return response()->json([
            'message' => 'Alumni application rejected',
            'alumni' => $alumniProfile
        ]);
    }
}

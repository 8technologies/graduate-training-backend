<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $courses = Program::all();
    //     return $this->responseSuccess($courses, 'Successfully retrieved all Courses');
    // }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(ProgramRequest $request)
    // {
    //     $validated = $request->validated();
    //     $program = Program::create($validated);

    //     return $this->responseSuccess($program, "Successfully Created Program", JsonResponse::HTTP_CREATED);
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id)->get();
        return $this->responseSuccess($user, 'Successfully retrieved this user\'s data');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id)->get();
        return $this->responseSuccess($user, 'Successfully retrieved this user\'s data');
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     // $validated = $request->validated();
    //     $user = User::find($id);
    //     Log::info($request);

    //     if ($request->hasFile('profile_picture')) {
    //         // Delete the old profile picture if exists
    //         if ($user->profile) {
    //             Storage::delete('public/profile_pictures/' . $user->profile);
    //         }
    
    //         // Store new profile picture
    //         $image = $request->file('profile_picture');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->storeAs('public/profile_pictures', $imageName);
            
    //         // Save new image path in database
    //         $user->profile = $imageName;
    //     }
        
    //     $user->update([
    //         'first_name' => $request->first_name,
    //         'profile' => $imageName,
    //         'last_name' => $request->last_name,
    //         'telephone' => $request->telephone,
    //         'email' => $request->email,
    //     ]);
    //     return $this->responseSuccess('user profile updated successfully',  $user);

    // }

    public function update(Request $request, string $id)
{
    // Get the user being updated
    $user = User::findOrFail($id);

    // Ensure users can only update their own profile unless they are admins
    if (auth()->user()->id !== $user->id && !auth()->user()->hasRole('admin')) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Base user validation
    $validatedData = $request->validate([
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:15',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        // Delete old profile picture if exists
        if ($user->profile) {
            Storage::delete('public/profile_pictures/' . $user->profile);
        }

        // Store new profile picture
        $image = $request->file('profile_picture');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/profile_pictures', $imageName);
        $validatedData['profile'] = $imageName;
    }

    // **Handle Role-Specific Updates**
    switch ($user->roles->first()->name) {
        case 'student':
            $validatedData += $request->validate([
                'program_id' => 'nullable|exists:programs,id',
                'registration_number' => 'nullable|string|unique:students,registration_number,' . $user->id,
                'enrollment_year' => 'nullable|integer|min:2000',
            ]);
            $user->student()->updateOrCreate(['user_id' => $user->id], $validatedData);
            break;

        case 'supervisor':
            $validatedData += $request->validate([
                'department' => 'nullable|string|max:255',
                'university_id' => 'nullable|exists:universities,id',
                'expertise' => 'nullable|string',
            ]);
            $user->supervisor()->updateOrCreate(['user_id' => $user->id], $validatedData);
            break;

        case 'examiner':
            $validatedData += $request->validate([
                'examiner_code' => 'nullable|string|unique:examiners,examiner_code,' . $user->id,
                'academic_credentials' => 'nullable|string',
                'years_of_experience' => 'nullable|integer',
            ]);
            $user->examiner()->updateOrCreate(['user_id' => $user->id], $validatedData);
            break;
    }

    // Update the user details
    $user->update(array_filter($validatedData));

    return $this->responseSuccess($user, 'User profile updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::find($id);

        $program->delete();

        return $this->responseSuccess('University deleted successfully',  $program);

    }
}

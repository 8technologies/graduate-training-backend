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

class AlumniController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role_id = Role::where('name', 'alumni');
        $alumni = User::where('role_id', $role_id)->with('students');
        
        return $this->responseSuccess($alumni, 'Successfully retrieved all alumni');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AluminiRequest $request)
    {
        $validated = $request->validated();
        $alumni = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password'=> $validated['password'],
            'telephone' => $validated['telephone'],
        ]);

        $alumni->student()->updateOrCreate(
            ['user_id' => $alumni->id,
            "program_details" => $validated['program_details'],
            "academic_history"=> $validated['academic_history'], 
            'achievements' => $validated['achievements'], 
        ], // Condition
           
        );


        return $this->responseSuccess($alumni, "Successfully Created Program", JsonResponse::HTTP_CREATED);
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

        return $this->responseSuccess('University deleted successfully',  $alumni);

    }
}

<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;

class ProgramController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programs = Program::where(['university_id' => $request->user()->university_id])->with('program_level', 'program_track', 'milestone_profile.milestones')->get();
        return $this->responseSuccess($programs, 'Successfully retrieved all programs');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProgramRequest $request)
    {
        $validated = $request->validated();
        $validated['university_id'] = $request->user()->university_id;
        $program = Program::create($validated);

        return $this->responseSuccess($program, "Successfully Created Program", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = Program::findOrFail($id);
        return $this->responseSuccess($program, 'Successfully retrieved this Program\'s data');
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
    public function update(ProgramRequest $request, string $id)
    {
        $validated = $request->validated();
        $program = Program::find($id);
        $validated['university_id'] = $request->user()->university_id;
        $program->update($validated);

        return $this->responseSuccess('Program updated successfully', $program);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::find($id);

        $program->delete();

        return $this->responseSuccess('Program deleted successfully', $program);

    }
}

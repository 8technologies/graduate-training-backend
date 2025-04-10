<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramLevelRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;
use App\Models\ProgramLevel;

class ProgramLevelController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programLevels = ProgramLevel::where(['university_id' => $request->user()->university_id])->get();
        return $this->responseSuccess($programLevels, 'Successfully retrieved all Program Levels');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProgramLevelRequest $request)
    {
        $validated = $request->validated();
        $validated['university_id'] = $request->user()->university_id;
        $program_level = ProgramLevel::create($validated);

        return $this->responseSuccess($program_level, "Successfully Created Program Levels", JsonResponse::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(ProgramLevelRequest $request, string $id)
    {
        $validated = $request->validated();
        $program_level = ProgramLevel::find($id);
        $validated['university_id'] = $request->user()->university_id;

        $program_level->update($validated);

        return $this->responseSuccess('program level updated successfully', $program_level);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program_level = ProgramLevel::find($id);

        $program_level->delete();

        return $this->responseSuccess('program level deleted successfully', $program_level);

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramTrack;
use App\Http\Requests\ProgramTrackRequest;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;

class ProgramTrackController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $program_tracks = ProgramTrack::where(['university_id' => $request->user()->university_id])->get();
        return $this->responseSuccess($program_tracks, 'Successfully retrieved all Program Tracks');
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(ProgramTrackRequest $request)
    {
        $validated = $request->validated();
        $validated['university_id'] = $request->user()->university_id;
        $programTrack = ProgramTrack::create($validated);

        return $this->responseSuccess($programTrack, "Successfully Created Program Track", JsonResponse::HTTP_CREATED);
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
    public function update(ProgramTrackRequest $request, string $id)
    {
        $validated = $request->validated();
        $programTrack = ProgramTrack::find($id);
        $validated['university_id'] = $request->user()->university_id;

        $programTrack->update($validated);

        return $this->responseSuccess('Program Track updated successfully', $programTrack);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program_track = ProgramTrack::find($id);

        $program_track->delete();

        return $this->responseSuccess('program track deleted successfully', $program_track);

    }
}

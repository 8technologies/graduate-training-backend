<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MilestoneProfileRequest;
use Illuminate\Http\Request;
use App\Models\MilestoneProfile;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;

class MilestoneProfileController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $milestone_profile = MilestoneProfile::where(['university_id' => $request->user()->university_id])->with('university', 'milestones')->get();
        return $this->responseSuccess($milestone_profile, 'Successfully retrieved all milestone profile');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(MilestoneProfileRequest $request)
    {
        $validated = $request->validated();
        $validated['university_id'] = $request->user()->university_id;
        $milestone_profile = MilestoneProfile::create($validated);

        return $this->responseSuccess($milestone_profile, "Successfully Created milestone profile", JsonResponse::HTTP_CREATED);
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
    public function update(MilestoneProfileRequest $request, string $id)
    {
        $validated = $request->validated();
        $milestone_profile = MilestoneProfile::find($id);

        $milestone_profile->update($validated);

        return $this->responseSuccess('milestone profile updated successfully', $milestone_profile);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $milestone_profile = MilestoneProfile::find($id);

        $milestone_profile->delete();

        return $this->responseSuccess('milestone profile deleted successfully', $milestone_profile);

    }
}

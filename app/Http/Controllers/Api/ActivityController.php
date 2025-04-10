<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;

class ActivityController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activities = Activity::where(['milestone_id' => $request->milestone_id])->get();
        return $this->responseSuccess($activities, 'Successfully retrieved all activities');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityRequest $request)
    {
        $validated = $request->validated();
        $activity = Activity::create($validated);

        return $this->responseSuccess($activity, "Successfully Created activity", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activity::findOrFail($id);
        return $this->responseSuccess($activity, 'Successfully retrieved this activity\'s data');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activity = Activity::findOrFail($id);
        return $this->responseSuccess($activity, 'Successfully retrieved this activity\'s data');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityRequest $request, string $id)
    {
        $validated = $request->validated();
        $activity = Activity::find($id);

        $activity->update($validated);

        return $this->responseSuccess('Activity updated successfully', $activity);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::find($id);

        $activity->delete();

        return $this->responseSuccess('Activity deleted successfully', $activity);

    }
}

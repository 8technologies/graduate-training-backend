<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;
use App\Http\Requests\TrainingScheduleRequest;
use App\Models\Milestone;

class TrainingScheduleController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $training_schedules = Milestone::where(['university_id' => $request->user()->university_id])->get();
        return $this->responseSuccess($training_schedules, 'Successfully retrieved all training schedules');
    }

    public function getMilestoneForProfile(Request $request, $id)
    {
        $training_schedules = Milestone::where(['milestone_profile_id' => $id, 'university_id' => $request->user()->university_id])->get();
        return $this->responseSuccess($training_schedules, 'Successfully retrieved all milestones');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingScheduleRequest $request)
    {
        $validated = $request->validated();
        $trainingSchedule = Milestone::create($validated);

        return $this->responseSuccess($trainingSchedule, "Successfully Created a MileStone", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $training_schedule = Milestone::findOrFail($id)->get();
        return $this->responseSuccess($training_schedule, 'Successfully retrieved this Training Schedule\'s data');

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
    public function update(TrainingScheduleRequest $request, string $id)
    {
        $validated = $request->validated();
        $training_schedule = Milestone::find($id);

        $training_schedule->update($validated);

        return $this->responseSuccess('Training Schedule updated successfully', $training_schedule);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $training_schedule = Milestone::find($id);

        $training_schedule->delete();

        return $this->responseSuccess('MileStone deleted successfully', $training_schedule);

    }
}

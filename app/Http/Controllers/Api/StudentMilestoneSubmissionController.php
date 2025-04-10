<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentMilestoneSubmissionRequest;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Models\Milestone;
use App\Models\MilestoneSubmission;
use App\Models\Program;
use App\Models\ProgramTrack;
use App\Models\StudentMilestoneSubmission ;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class StudentMilestoneSubmissionController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display the form for file upload.
     */

     public function index(Request $request)
     {
         // Get the program and its milestones
         $program = Program::findOrFail($request['program_id']);
         $milestones = $program->milestone_profile->milestones ?? collect(); // Ensure it's a collection
     
         // Get the student's submissions for these milestones
         $submissions = StudentMilestoneSubmission::where('student_id', $request['student_id'])
             ->whereIn('milestone_id', $milestones->pluck('id'))
             ->get()
             ->keyBy('milestone_id'); // Store submissions using milestone_id as key
     
         // Format milestones list with status
         $milestoneList = $milestones->map(function ($milestone) use ($submissions) {
             return [
                 'id' => $milestone->id,
                 'name' => $milestone->name,
                 'status' => isset($submissions[$milestone->id]) ? 'Completed' : 'Not Completed'
             ];
         });
     
         // Calculate progress
         $totalMilestones = $milestones->count();
         $completedMilestones = $submissions->count();
         $progress = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100, 2) : 0;
     
         $data = [
             'program' => $program,
             'milestones' => $milestoneList,
             'progress' => $progress
         ];
     
         return $this->responseSuccess($data, "Successfully Retrieved Student Progress", JsonResponse::HTTP_OK);
     }
     
    /**
     * Handle the file upload.
     */
    public function store(StudentMilestoneSubmissionRequest $request)
    {
        
        $validated = $request->validated();

        $milestone = Milestone::findOrFail($validated['milestone_id']);

        // Store file
        if($milestone->requires_submission == 1){
            $filePath = $request->file('documents')->store('uploads/milestones');
        }
        
        // Save record in database
        $submission = new StudentMilestoneSubmission();
        $submission->student_id = auth()->id();
        $submission->milestone_id = $validated['milestone_id'];
        $submission->documents = $filePath;
        $submission->description = $validated['description'];
        $submission->save();

        return $this->responseSuccess($submission, "Successfully Created Student InTake", JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentMilestoneSubmissionRequest $request, string $id)
    {
        $validated = $request->validated();
        $submission = StudentMilestoneSubmission::find($id);

        $submission->update($validated);

        return $this->responseSuccess('Submission updated successfully', $submission);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = StudentMilestoneSubmission::findOrFail($id);
        return $this->responseSuccess($program, 'Successfully retrieved this submission\'s data');
    }


    /**
     * view details of a miletone .
     */
    public function view()
    {
        
    }

    /**
     * Download a submitted file.
     */
    public function download($submissionId)
    {
        $submission = StudentMilestoneSubmission::findOrFail($submissionId);

        if ($submission->student_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return Storage::download($submission->file_path);
    }

    /**
     * supervisor signoff
     */

    public function signoff(StudentMilestoneSubmissionRequest $request, $submissionId){
        $submission = StudentMilestoneSubmission::findOrFail($submissionId);
        $validated = $request->validated();

        $submission->status = $validated['status'];
        $submission->save();

        return $this->responseSuccess($submission, 'Successfully signed off this submission\'s data');
    
    }
}

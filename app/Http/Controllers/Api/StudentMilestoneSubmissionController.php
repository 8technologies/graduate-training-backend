<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentMilestoneSubmissionRequest;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Models\Milestone;
use App\Models\MilestoneSubmission;
use App\Models\Program;
use App\Models\Student;
use App\Models\ProgramTrack;
use App\Models\StudentMilestoneSubmission ;
use App\Models\SubmissionVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentMilestoneSubmissionController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display the form for file upload.
     */

    //  public function index(Request $request)
    //  {
    //      // Get the program and its milestones
    //      $program = Program::findOrFail($request['program_id']);
    //      $milestones = $program->milestone_profile->milestones ?? collect(); // Ensure it's a collection
     
    //      // Get the student's submissions for these milestones
    //      $submissions = StudentMilestoneSubmission::where('student_id', $request['student_id'])
    //          ->whereIn('milestone_id', $milestones->pluck('id'))
    //          ->get()
    //          ->keyBy('milestone_id'); // Store submissions using milestone_id as key
     
    //      // Format milestones list with status
    //      $milestoneList = $milestones->map(function ($milestone) use ($submissions) {
    //          return [
    //              'submission_id' => isset($submissions[$milestone->id]) ? $submissions[$milestone->id]->id : null,
    //              'milestone_id' => $milestone->id,
    //              'name' => $milestone->name,
    //              'status' => isset($submissions[$milestone->id]) ? 'Completed' : 'Not Completed'
    //          ];
    //      });
     
    //      // Calculate progress
    //      $totalMilestones = $milestones->count();
    //      $completedMilestones = $submissions->count();
    //      $progress = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100, 2) : 0;
     
    //      $data = [
    //          'program' => $program,
    //          'milestones' => $milestoneList,
    //          'progress' => $progress
    //      ];
     
    //      return $this->responseSuccess($data, "Successfully Retrieved Student Progress", JsonResponse::HTTP_OK);
    //  }
    public function index(Request $request)
{
     $user = Auth::user();
    
    // Get all submissions for the logged-in user with their versions
    $submissions = StudentMilestoneSubmission::with(['versions' => function($query) {
            $query->select('id', 'submission_id', 'file_name', 'file_path', 'created_at', 'is_approved');
        }])
        ->get();

    $data = $submissions->map(function ($submission) {
        $versions = $submission->versions->map(function ($version) {
            return [
                'id' => $version->id,
                'file_name' => $version->file_name,
                'file_path' => $version->file_path,
                'created_at' => $version->created_at,
                'is_approved' => $version->is_approved,
            ];
        });

        return [
            'submission_id' => $submission->id,
            'milestone_id' => $submission->milestone_id,
            'status' => $submission->status,
            'versions' => $versions,
        ];
    });

    return response()->json([
        'submissions' => $data
    ]);
}

     
    /**
     * Handle the file upload.
     */
    
    public function store(Request $request)
{
    $request->validate([
        'milestone_id' => 'required|exists:milestones,id',
        'documents' => 'required|file|max:10240', // 10MB max size
        'description' => 'nullable|string',
    ]);

    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->firstOrFail();
    $milestone = Milestone::findOrFail($request->milestone_id);

    DB::beginTransaction();

    try {
        // Check for existing submission
        $submission = StudentMilestoneSubmission::where('student_id', $student->id)
            ->where('milestone_id', $milestone->id)
            ->first();

        if (!$submission) {
            // Create new submission
            $submission = StudentMilestoneSubmission::create([
                'milestone_id' => $milestone->id,
                'student_id' => $student->id,
                'status' => 'pending',
            ]);
        }

        // Handle file upload
        $file = $request->file('documents');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $fileType = $file->getMimeType();
        $uniqueFileName = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('submissions/' . $submission->id, $uniqueFileName, 'public');

        // Create submission version
        $version = SubmissionVersion::create([
            'submission_id' => $submission->id,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'is_approved' => false,
            // 'created_by' => $user->id,
            'created_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'submission_id' => $submission->id,
            'milestone_id' => $submission->milestone_id,
            'status' => $submission->status,
            'current_version' => [
                'id' => $version->id,
                'created_at' => $version->created_at,
                'is_approved' => $version->is_approved,
            ]
        ], $submission->wasRecentlyCreated ? 201 : 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Submission failed: ' . $e->getMessage()], 500);
    }
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
    // public function download($submissionId)
    // {
    //     $submission = StudentMilestoneSubmission::findOrFail($submissionId);

    //     return Storage::download($submission->documents);
    // }

    public function download(string $version_id)
    {
        Log::info(['version', $version_id]);
        $version = SubmissionVersion::findOrFail($version_id);
        if (!Storage::disk('public')->exists($version->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        return Storage::disk('public')->download(
            $version->file_path,
            $version->file_name
        );
        
        
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

    public function approveVersion(Request $request )
    {
        $request->validate([
            'submission' => 'required|exists:student_milestone_submissions,id',
            'version' => 'required|exists:submission_versions,id', // 10MB max size
        ]);
        $version_id = $request->version;
        $version = SubmissionVersion::findOrFail($version_id);
        $submission_id = $request->submission;
        $submission = StudentMilestoneSubmission::findOrFail($submission_id);
        
        // Make sure the version belongs to this submission
        if ($version->submission_id !== $submission->id) {
            return response()->json(['error' => 'Version does not belong to this submission'], 400);
        }
        
        DB::beginTransaction();
        
        try {
            // First, remove approved status from all versions
            SubmissionVersion::where('submission_id', $submission->id)
                ->update(['is_approved' => false]);
            
            // Set this version as approved
            $version->is_approved = true;
            $version->save();
            
            // Update submission status
            $submission->status = 'approved';
            $submission->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Version approved successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to approve version: ' . $e->getMessage()], 500);
        }
    }
}

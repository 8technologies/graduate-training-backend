<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintRequest;
use App\Models\Complaint;
use App\Models\Supervisor;
use App\Traits\HttpApiResponseTrait;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    use HttpApiResponseTrait;
    

    public function index()
    {
        // $complaints = Complaint::all();
        $complaints = Complaint::with('student', 'supervisor', 'registrar')->get();
        return $this->responseSuccess($complaints, 'Successfully retrieved all complaints');

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'supervisor_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        $validated['status'] = 'pending'; // Default status
        $validated['submitted_at'] = now(); // Optional: track submission time

        $complaint = Complaint::create($validated);

        return response()->json([
            'message' => 'Complaint submitted successfully.',
            'data' => $complaint
        ]);
    }

    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'response' => 'nullable|string',
            'status' => 'nullable|in:open,in_progress,resolved,closed',
        ]);

        if ($request->has('response')) {
            $validated['responded_at'] = now(); // Track time of response
        }

        $complaint->update($validated);

        return $this->responseSuccess($complaint, 'Complaint updated successfully.');
    }

    public function reply(Request $request, $complaintId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $complaint = Complaint::findOrFail($complaintId);

        $message = $complaint->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Optionally update complaint status
        if ($complaint->status === 'pending') {
            $complaint->update(['status' => 'in_progress']);
        }

        return response()->json(['message' => 'Reply added', 'data' => $message]);
    }

}

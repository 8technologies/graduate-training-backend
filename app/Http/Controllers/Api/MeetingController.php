<?php

namespace App\Http\Controllers\Api;

use App\Models\Meeting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Meeting::with(['student', 'supervisor'])->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    // app/Http/Controllers/MeetingController.php

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'supervisor_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'mode' => 'required|in:online,physical',
            'location' => 'required|string',
        ]);

        $meeting = Meeting::create($validated);

        return response()->json(['message' => 'Meeting scheduled', 'meeting' => $meeting], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'supervisor_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'mode' => 'required|in:online,physical',
            'location' => 'required|string',
        ]);
        $meeting = Meeting::find($id);
        $meeting->update($validated);

        return response()->json(['message' => 'Meeting updated', 'meeting' => $meeting], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        //
    }
}

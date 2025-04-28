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
        'user_id'=> 'required|exists:users,id',
            'registrar_id'=>'nullable||exists:users,id' ,
            'supervisor_id'=> 'required|exists:users,id',
            'subject'=> 'required|string',
            'description' => 'required|string',
            'response' => 'nullable|string',
            'status' => '',
            
    ]);
    Complaint::create($validated);

    return response()->json(['message' => 'Complaint submitted successfully.']);
}

public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'response' => 'nullable|string',
        'status' => '',
            
    ]);
    $complaint = Complaint::find($id);

    $complaint->update($validated);


    return$this->responseSuccess('Complaint submitted successfully.', $complaint);
}

}

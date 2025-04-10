<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Http\Requests\AcademicHistoryRequest;
use Illuminate\Http\JsonResponse;
use App\Models\AcademicHistory;

class AcademicHistoryController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $history = AcademicHistory::where('user_id', '=', $request->user()->id)->get();
        return $this->responseSuccess($history, 'Successfully retrieved all academic history');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademicHistoryRequest $request)
    {



        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $academicHistory = AcademicHistory::create($validated);


        return $this->responseSuccess($academicHistory, "Successfully Created academic history", JsonResponse::HTTP_CREATED);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

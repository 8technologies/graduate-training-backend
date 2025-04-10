<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\HttpApiResponseTrait;
use App\Models\StudentInTake;
use App\Http\Requests\StudentInTakeRequest;
class StudentInTakeController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $studentInTakes = StudentInTake::where(['university_id' => $request->university_id])->get();
        return $this->responseSuccess($studentInTakes, 'Successfully retrieved all Student InTakes');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentInTakeRequest $request)
    {
        $validated = $request->validated();
        $StudentInTake = StudentInTake::create($validated);

        return $this->responseSuccess($StudentInTake, "Successfully Created Student InTake", JsonResponse::HTTP_CREATED);
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

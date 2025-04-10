<?php

namespace App\Http\Controllers\Api;
use App\Traits\HttpApiResponseTrait;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\University;
use App\Http\Requests\UniverityRequest;

class UniversityController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $universities = University::all();
        return $this->responseSuccess($universities, 'Successfully retrieved all universities');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UniverityRequest $request)
    {
        $validated = $request->validated();
        $university = University::create($validated);

        return $this->responseSuccess($university, "Successfully Created univerity", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $universities = University::findOrFail($id)->get();
        return $this->responseSuccess($universities, 'Successfully retrieved this university\'s data');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UniverityRequest $request, string $id)
    {
        $validated = $request->validated();
        $university = University::find($id);
        
        $university->update($validated);

        return $this->responseSuccess('University updated successfully',  $university);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $university = University::find($id);

        $university->delete();

        return $this->responseSuccess('University deleted successfully',  $university);

    }
}

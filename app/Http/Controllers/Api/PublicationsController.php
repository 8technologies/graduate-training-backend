<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Publication;
use App\Http\Requests\PublicationRequest;

class PublicationsController extends Controller
{
    use HttpApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publications = Publication::all();
        return $this->responseSuccess($publications, 'Successfully retrieved all publications');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PublicationRequest $request)
    {
        $validated = $request->validated();
        $publication = Publication::create($validated);

        // Store file
        if($milestone->requires_submission == 1){
            $filePath = $request->file('documents')->store('uploads/milestones');
        }

        return $this->responseSuccess($publication, "Successfully created publication", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $publication = Publication::findOrFail($id);
        return $this->responseSuccess($publication, 'Successfully retrieved publication data');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PublicationRequest $request, string $id)
    {
        $validated = $request->validated();
        $publication = Publication::findOrFail($id);
        $publication->update($validated);

        return $this->responseSuccess($publication, 'Publication updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publication = Publication::findOrFail($id);
        $publication->delete();

        return $this->responseSuccess($publication, 'Publication deleted successfully');
    }
}

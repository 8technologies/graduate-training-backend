<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SponsorshipType;
use App\Http\Requests\SponsorshipTypeRequest;

class SponsorshipTypeController extends Controller
{
    use HttpApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sponsorshipTypes = SponsorshipType::where(['university_id' => $request->university_id])->get();
        return $this->responseSuccess($sponsorshipTypes, 'Successfully retrieved all sponsorship types');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SponsorshipTypeRequest $request)
    {
        $validated = $request->validated();
        $sponsorshipType = SponsorshipType::create($validated);

        return $this->responseSuccess($sponsorshipType, "Successfully created sponsorship type", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sponsorshipType = SponsorshipType::findOrFail($id);
        return $this->responseSuccess($sponsorshipType, 'Successfully retrieved sponsorship type data');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SponsorshipTypeRequest $request, string $id)
    {
        $validated = $request->validated();
        $sponsorshipType = SponsorshipType::findOrFail($id);

        $sponsorshipType->update($validated);

        return $this->responseSuccess($sponsorshipType, 'Sponsorship type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sponsorshipType = SponsorshipType::findOrFail($id);

        $sponsorshipType->delete();

        return $this->responseSuccess($sponsorshipType, 'Sponsorship type deleted successfully');
    }
}

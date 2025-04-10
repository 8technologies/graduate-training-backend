<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DurationUnit;
use App\Http\Requests\DurationUnitRequest;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;

class DurationUnitController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $duration_unit = DurationUnit::all();
        return $this->responseSuccess($duration_unit, 'Successfully retrieved all duration units');

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(DurationUnitRequest $request)
    {
        $validated = $request->validated();
        $duration_unit = DurationUnit::create($validated);

        return $this->responseSuccess($duration_unit, "Successfully Created duration units", JsonResponse::HTTP_CREATED);
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

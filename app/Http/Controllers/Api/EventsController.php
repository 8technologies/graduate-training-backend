<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

use App\Traits\HttpApiResponseTrait;
use Illuminate\Http\JsonResponse;

class EventsController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return $this->responseSuccess($events, 'Successfully retrieved all Events');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $validated = $request->validated();
        $event = Event::create($validated);

        return $this->responseSuccess($event, "Successfully Created an Event", JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id)->get();
        return $this->responseSuccess($event, 'Successfully retrieved this events\'s data');
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
    public function update(EventRequest $request, string $id)
    {
        $validated = $request->validated();
        $event = Event::find($id);
        
        $event->update($validated);

        return $this->responseSuccess('Event updated successfully',  $event);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);

        $event->delete();

        return $this->responseSuccess('Event deleted successfully',  $event);

    }
}

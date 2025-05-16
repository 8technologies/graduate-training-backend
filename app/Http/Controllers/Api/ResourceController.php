<?php

namespace App\Http\Controllers\Api;

use App\Models\Resource;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResourceRequest;
use Illuminate\Http\Request;

use App\Traits\HttpApiResponseTrait;
use GuzzleHttp\BodySummarizer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Resource::with('program')->latest()->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceRequest $request)
    {
        
         $validated = $request->validated();
        Log::info(['request', $validated]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('resources', 'public');
            $validated['file_path'] = $path;
        }

        $resource = Resource::create($validated);

        return response()->json($resource, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceRequest $request, Resource $resource)
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('resources', 'public');
            $validated['file_path'] = $path;
        }

        $resource->update($validated);

        return response()->json($resource);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();
        return response()->json(null, 204);
    }


    public function download(string $resource)
    {
        Log::info(['version', $resource]);
        $resource = Resource::findOrFail($resource);

        if (!Storage::disk('public')->exists($resource->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        return Storage::disk('public')->download(
            $resource->file_path,
            $resource->title
        );
        
        
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Requests\PermissionRequest;
use App\Traits\HttpApiResponseTrait;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $all_permissions = Permission::select(['id', 'name', 'guard_name'])->get();
        return response()->json(['data' => $all_permissions]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => ["required", "string", "max:30", Rule::unique(Permission::class)],
            'guard_name' => ['nullable', 'string', 'min:3', 'max:100'],
        ]);
        $validData['created_by'] = $request->user()->id;
        // $this->authorize('create', [Permission::class]);

        return response()->json(Permission::create($validData), 201);
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

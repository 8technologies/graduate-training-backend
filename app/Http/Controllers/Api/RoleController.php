<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Traits\HttpApiResponseTrait;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return $this->responseSuccess($roles, 'Successfully retrieved all roles');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => ["required", "string", "max:30", Rule::unique(Role::class)],
            'guard_name' => ['nullable', 'string', 'min:3', 'max:100'],
        ]);
        // $validData['created_by'] = $request->user()->id;
        return response()->json(Role::create($validData), 201);
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

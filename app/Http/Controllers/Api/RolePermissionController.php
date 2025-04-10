<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpApiResponseTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    use HttpApiResponseTrait;
    /**
     * Display a listing of the role-permission associations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $roleId)
    {
        $role = Role::findById($roleId);
        return response()->json(['data' => $role->getPermissionNames()]);
    }

    /**
     * Store a newly created role-permission association in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function store(Request $request, int $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = $request->validate([
            'permissions.*.name' => 'required|string|min:1',
        ]);
        $role->syncPermissions($permissions['permissions']);
        return response()->json(['message' => 'Role Permissions updated']);
    }

    /**
     * Display the specified role-permission association.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rolePermission = RolePermission::findOrFail($id);
        return response()->json($rolePermission);
    }

    /**
     * Update the specified role-permission association in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rolePermission = RolePermission::findOrFail($id);

        $validated = $request->validate([
            'role_id' => 'sometimes|required|exists:roles,id',
            'permission_id' => 'sometimes|required|exists:permissions,id',
        ]);

        $rolePermission->update($validated);

        return response()->json($rolePermission);
    }

    /**
     * Remove the specified role-permission association from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $rolePermission = RolePermission::findOrFail($id);
        $rolePermission->delete();

        return response()->json(null, 204);
    }
}

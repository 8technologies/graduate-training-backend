<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AssignRequest;
use App\Models\AssignModel;
use App\Traits\HttpApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    use HttpApiResponseTrait;
    public function assign_Supervisor(AssignRequest $request)
    {
        $validated = $request->validated();
        $assign = AssignModel::create($validated);

        return $this->responseSuccess($assign, "Successfully Assigned", JsonResponse::HTTP_CREATED);
    }
}

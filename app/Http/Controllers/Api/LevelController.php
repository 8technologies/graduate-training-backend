<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\HttpApiResponseTrait;
use App\Models\LevelModel;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    use HttpApiResponseTrait;
    public function index()
    {
        $getStudentLevel = LevelModel::all();
        return $this->responseSuccess($getStudentLevel, 'Successfully retrieved all Student Levels');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        $level = LevelModel::create($validated);

        return $this->responseSuccess($level, "Successfully Created student level", JsonResponse::HTTP_CREATED);
    }
}

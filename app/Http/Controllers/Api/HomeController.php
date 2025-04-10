<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\HttpApiResponseTrait;
use App\Models\Examiner;
use App\Models\Program;
use App\Models\Student;
use App\Models\Supervisor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    use HttpApiResponseTrait;
    
    public function index()
    {
        $studentCount = Student::count();

        $supervisorCount = Supervisor::count();

        $examinerCount = Examiner::count();

        $programCounter = Program::count();

        $today = Carbon::today();

        // upcoming events
        $events = Event::where('start_date', '>=', $today)
                    ->orderBy('start_date', 'asc') // Sort by nearest event first
                    ->get();

        $data = [
            'studentCount' => $studentCount,
            'supervisorCount' => $supervisorCount,
            'examinerCount' => $examinerCount,
            'programCounter' => $programCounter,
            'events' => $events
        ];

        return $this->responseSuccess($data, "Dashboard data retrieved successfully", JsonResponse::HTTP_CREATED);
        
    }

}
<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UniversityController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\AcademicHistoryController;
use App\Http\Controllers\Api\TrainingScheduleController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\PublicationsController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\ProgramLevelController;
use App\Http\Controllers\Api\StudentInTakeController;
use App\Http\Controllers\Api\ProgramTrackController;
use App\Http\Controllers\Api\DurationUnitController;
use App\Http\Controllers\Api\MilestoneProfileController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\api\ComplaintController;
use App\Http\Controllers\Api\ExaminerController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SponsorshipTypeController;
use App\Http\Controllers\Api\StudentMilestoneSubmissionController;
use App\Http\Controllers\Api\RolePermissionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('login', [AuthController::class, 'Login']);

Route::apiResources([
    'universities' => UniversityController::class
]);

Route::apiResources([
    'activities' => ActivityController::class
]);

Route::apiResources([
    'duration_units' => DurationUnitController::class
]);
Route::apiResources([
    'sponsorship_types' => SponsorshipTypeController::class
]);

Route::apiResources([
    'publications' => PublicationsController::class
]);


Route::apiResources([
    'roles' => RoleController::class
]);
// student progress
Route::apiResources([
    'complaints' => ComplaintController::class
]);


Route::apiResources([
    'student_intakes' => StudentInTakeController::class
]);

Route::get('supervisor/students/{id}', [SupervisorController::class, 'assigned_students']);

Route::middleware([ 'passport-auth'/*, 'auth:api'*/])->group(function () {
    Route::get("milestone/profile/{id}", [TrainingScheduleController::class, 'getMilestoneForProfile']);
    Route::apiResources([
        'milestone_profiles' => MilestoneProfileController::class
    ]);
    Route::apiResources([
        'students' => StudentController::class
    ]);
    Route::apiResources([
        'programs' => ProgramController::class
    ]);
    Route::apiResources([
        'program_tracks' => ProgramTrackController::class
    ]);
    Route::apiResources([
        'program_levels' => ProgramLevelController::class
    ]);
    Route::post('register', [AuthController::class, 'Register']);
    Route::apiResource('urole.permission', RolePermissionController::class)->only(['index', 'store']);
    Route::delete('urole/{role_id}/permission/{permission_id}', 'RolePermissionController@destroy');
    Route::apiResources([
        'permissions' => PermissionController::class
    ]);
    Route::get("urole/{guard?}", [RoleController::class, 'index'])->name('urole.index');
    Route::apiResource('urole', RoleController::class)->shallow()->except('index');
    Route::post('superviser/assign', [AdminController::class, 'assign_Supervisor']);
    Route::apiResources([
        'academic_history' => AcademicHistoryController::class,
    ]);
    Route::apiResources([
        'milestones' => TrainingScheduleController::class
    ]);

    // student progress
    Route::apiResources([
        'student_progress' => StudentMilestoneSubmissionController::class
    ]);

     // student progress
    // Route::apiResources([
    //     'complaints' => ComplaintController::class
    // ]);

    Route::post('signoff/{id}', [StudentMilestoneSubmissionController::class, 'signoff']);


});


Route::get('studentlevels', [LevelController::class, 'index']);
Route::post('studentlevels', [LevelController::class, 'store']);

//Students
Route::apiResources([
    'alumni' => AlumniController::class
]);


//Supervisors
//Route::get('supervisors',[SupervisorController::class,'getAllSupervisors']);
Route::get('supervisors', [SupervisorController::class, 'index']); // ✅ Get all supervisors
Route::post('supervisors', [SupervisorController::class, 'store']); // ✅ Create a supervisor
Route::get('supervisors/{id}', [SupervisorController::class, 'show']); // ✅ Get a single supervisor
Route::put('supervisors/{id}', [SupervisorController::class, 'update']); // ✅ Update a supervisor
Route::delete('supervisors/{id}', [SupervisorController::class, 'destroy']); // ✅ Delete a supervisor

// Examiners
Route::apiResources([
    'examiners' => ExaminerController::class
]);

//users

Route::get('user', [AuthController::class, 'User']);

//user profile
Route::post('user_profile/{id}', [UserProfileController::class, 'update']);

// dashboard urls
Route::get('dashboard', [HomeController::class, 'index']);

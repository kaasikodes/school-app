<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CustodianController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthController::class, 'createAccount'])->name('createAccount');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // sessions
    // create or update
    Route::post('/sessions/save', [SchoolSessionController::class, 'store']);
    Route::get('/sessions', [SchoolSessionController::class, 'index']);

    // departments
    // create or update
    Route::post('/departments/save', [DepartmentController::class, 'store']);
    Route::get('/departments', [DepartmentController::class, 'index']);

    //levels
    Route::post('/levels/save', [LevelController::class, 'store']);
    Route::post('/levels/{id}/assign-session', [LevelController::class, 'assignToSession']);

    //courses
    Route::post('/courses/save', [CourseController::class, 'store']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses/{id}/assign-class', [CourseController::class, 'assignToLevel']);
    Route::post('/courses/{id}/add-participant', [CourseController::class, 'addParticipant']);
    Route::get('/courses/{id}/participants', [CourseController::class, 'courseParticipants']);
    Route::post('/courses/{id}/assign-teacher', [CourseController::class, 'assignTeacher']);
    Route::get('/courses/{id}/teachers', [CourseController::class, 'courseTeachers']);
    Route::post('/courses/{id}/assign-department', [CourseController::class, 'assignDepartment']);
    Route::post('/courses/{id}/add-lesson', [CourseController::class, 'addLesson']);
    Route::get('/courses/{id}/lessons', [CourseController::class, 'courseLessons']);



    // staff
    Route::post('/staff/saveprofile', [StaffController::class, 'store']);
    Route::get('/staff', [StaffController::class, 'index']);

    // custodian
    Route::post('/custodian/saveprofile', [CustodianController::class, 'store']);
    Route::get('/custodian', [CustodianController::class, 'index']);

    // student
    Route::post('/student/saveprofile', [StudentController::class, 'store']);
    Route::get('/student', [StudentController::class, 'index']);
    Route::post('/student/{id}/assign-custodian', [StudentController::class, 'assignToCustodian']);





});
Route::get('/levels', [LevelController::class, 'index']);







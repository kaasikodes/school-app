<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CustodianController;
use App\Http\Controllers\UserController;


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
    Route::get('/logout/{id}', [AuthController::class, 'logout']);

    // user
    Route::post('/users/{id}/update-choosenSchool', [UserController::class, 'updateChoosenSchoolId']);
    Route::get('/users/{id}', [UserController::class, 'show']);


    // schools
    // create or update
    Route::post('/schools/save', [SchoolController::class, 'store']);
    Route::get('/schools', [SchoolController::class, 'index']);
    Route::get('/schools/{id}', [SchoolController::class, 'show']);
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);
    Route::post('/schools/{id}/add-user', [SchoolController::class, 'addUserToSchool']);


    // sessions
    // create or update
    Route::post('/sessions/create', [SchoolSessionController::class, 'store']);
    Route::patch('/sessions/{id}', [SchoolSessionController::class, 'update']);
    Route::get('/sessions/{id}', [SchoolSessionController::class, 'show']);
    Route::patch('/sessions/{id}/end-session', [SchoolSessionController::class, 'endSession']);
    Route::get('/schools/{id}/sessions', [SchoolSessionController::class, 'index']);


    // departments
    // create or update
    Route::post('/departments/save', [DepartmentController::class, 'store']);
    Route::get('/departments', [DepartmentController::class, 'index']);

    //levels
    Route::post('/levels/save', [LevelController::class, 'store']);
    Route::post('/levels/{id}/assign-session', [LevelController::class, 'assignToSession']);
    Route::get('/schools/{id}/levels', [LevelController::class, 'index']);
    Route::get('/levels/{id}', [LevelController::class, 'show']);

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
    Route::post('/courses/{id}/add-assessment', [CourseController::class, 'addAssessment']);
    Route::get('/courses/{id}/assessments', [CourseController::class, 'courseAssessments']);
    Route::post('/course-assessment/{id}/add-question', [CourseController::class, 'addCourseAssessmentQuestion']);
    Route::get('/course-assessment/{id}/questions', [CourseController::class, 'courseAssessmentQuestions']);
    Route::patch('/update-question/{id}', [CourseController::class, 'updateQuestion']);
    Route::patch('/update-question/section/{id}', [CourseController::class, 'updateQuestionSection']);
    Route::patch('/update-question/assessment/{id}', [CourseController::class, 'updateQuestionAssessment']);
    Route::post('/course-assessment/{id}/save-section', [CourseController::class, 'saveCourseAssessmentSection']);
    Route::patch('/update-assessment-section/{id}/status', [CourseController::class, 'updateAssessmentSectionActivationStatus']);
    Route::post('/question/{id}/save-option', [CourseController::class, 'saveQuestionOption']);
    Route::post('/question/{id}/save-participant-answer', [CourseController::class, 'savePartcipantAnswer']);
    Route::patch('/score-answer/{id}', [CourseController::class, 'scoreAnswer']);
    Route::post('/question/{id}/save-correct-answer', [CourseController::class, 'saveCorrectAnswer']);



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
// Route::get('/levels', [LevelController::class, 'index']);







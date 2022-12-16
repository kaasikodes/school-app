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
use App\Http\Controllers\PaymentController;
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
    Route::post('/users/{id}/update-user_rolein-school', [UserController::class, 'updateUserChoosenRoleInSchool']);
    Route::get('/users/{id}', [UserController::class, 'show']);


    // schools
    // create or update
    Route::post('/schools/save', [SchoolController::class, 'store']);
    Route::get('/schools', [SchoolController::class, 'index']);
    Route::get('/schools/{id}', [SchoolController::class, 'show']);
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);
    Route::post('/schools/{id}/add-user', [SchoolController::class, 'addUserToSchool']);
    // school session templates & policies
    Route::post('/schools/{id}/setup-session-course-record-template', [SchoolController::class, 'setupSchoolSessionCRTemplate']);
    // course record template
    Route::post('/schools/{id}/save-course-record-template', [SchoolController::class, 'saveCourseRecordTemplate']);
    Route::get('/schools/{id}/course-record-templates', [SchoolController::class, 'getCourseRecordTemplates']);
    Route::get('/course-record-templates/{id}', [SchoolController::class, 'getCourseRecordSingleTemplate']);


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
    Route::get('/schools/{id}/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);


    //levels
    Route::post('/levels/save', [LevelController::class, 'store']);
    Route::post('/levels/{id}/assign-session', [LevelController::class, 'assignToSession']);
    // Route::get('/schools/{id}/levels', [LevelController::class, 'index']);
    Route::get('/levels/{id}', [LevelController::class, 'show']);

    //courses
    Route::post('/courses/save', [CourseController::class, 'store']);
    Route::get('/schools/{id}/courses', [CourseController::class, 'index']);
    Route::get('/courses/sessionCourseParticipants', [CourseController::class, 'sessionCourseParticipants']);
    Route::post('/courses/addSessionCourseParticipant', [CourseController::class, 'addSessionCourseParticipant']);
    Route::post('/courses/addSessionCourseTeacher', [CourseController::class, 'addSessionCourseTeacher']);
    Route::get('/schools/{id}/coursesGroupedByLevel', [CourseController::class, 'coursesGroupedByLevel']);
    Route::post('/save-participant-record', [CourseController::class, 'saveParticipantRecord']);
    
    //HERE
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
    // course records
    Route::get('courses/{id}/participant-records', [CourseController::class, 'participantRecords']);
 




    // staff
    Route::post('/staff/save-profile', [StaffController::class, 'store']);
    Route::get('/schools/{id}/staff', [StaffController::class, 'index']);
    Route::get('/schools/{schoolId}/staff/{staffId}', [StaffController::class, 'singleStaff']);
    Route::get('/staff/{staffId}/staffSessionLevelsAndCourses', [StaffController::class, 'staffSessionLevelsAndCourses']);



    // custodian
    Route::post('/custodian/saveprofile', [CustodianController::class, 'store']);
    Route::get('/custodian', [CustodianController::class, 'index']);

    // student
    Route::post('/schools/{schoolId}/enroll-student-for-session', [StudentController::class, 'enrollStudentForSession']);
    Route::get('/schools/{schoolId}/students', [StudentController::class, 'index']);
    Route::get('/schools/{schoolId}/students/{studentId}', [StudentController::class, 'singleStudent']);
    Route::post('/student/{id}/assign-custodian', [StudentController::class, 'assignToCustodian']);
    // /api/student/${studentId}/studentSessionCoursesGroupedByLevel?sessionId=${sessionId}&levelId=${levelId}
    Route::get('/student/{staffId}/studentSessionCoursesGroupedByLevel', [StudentController::class, 'studentSessionCoursesGroupedByLevel']);


    // payment
    Route::post('/payment-categories/create', [PaymentController::class, 'createPaymentCategory']);
    Route::put('/payment-categories/{id}/edit', [PaymentController::class, 'updatePaymentCategory']);
    Route::get('/schools/{schoolId}/payment-categories', [PaymentController::class, 'paymentCategories']);
    // level fees
    Route::post('/level-fees/create', [PaymentController::class, 'createLevelFee']);
    Route::put('/level-fees/{id}/edit', [PaymentController::class, 'updateLevelFee']);
    Route::get('/schools/{schoolId}/level-fees', [PaymentController::class, 'levelFees']);
    // currencies
    Route::get('/schools/{schoolId}/currencies', [PaymentController::class, 'getCurrencies']);
    // student session payment records
    Route::get('/schools/{schoolId}/student-payment-records', [PaymentController::class, 'getStudentPaymentRecords']);







});
// Route::get('/levels', [LevelController::class, 'index']);
Route::get('/schools/{id}/levels', [LevelController::class, 'index']);



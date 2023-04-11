<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolUser;

use App\Models\StudentSessionPayment;
use App\Models\LevelSchoolFee;
use App\Models\EnrolledStudent;
use App\Models\CourseParticipantRecord;
use App\Models\Custodian;
use App\Models\School;
use App\Models\Level;
use App\Models\Course;
use App\Models\SchoolSessionSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyUser;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\StaffSessionCourseNLevelResource;


use App\Http\Resources\StudentResource;
use App\Traits\BaseUserTrait;



class StudentController extends Controller
{
    use BaseUserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function studentAcademicResultView(Request $request)
    {
        $studentId= $request->studentId;
        $sessionId= $request->sessionId;
        $levelId= $request->levelId;
        $schoolId= $request->schoolId;
        $setting = SchoolSessionSetting::where('session_id', $sessionId)->where('school_id', $schoolId)->first();
        return $setting;
        // TO DO: check to see wether is custodian or student or admin or staff-classteacher if not throw err
        // TO DO: throw err if the required params are not provided
        $courses = CourseParticipantRecord::with(['course', 'level'])->where('school_session_id',$sessionId)->where('student_id',$studentId)->where('level_id',$levelId)->get();

       

        return view('student.result', [
            'courses'=> $courses,
            'title' => 'CodeAndDeploy.com Laravel Pdf Tutorial',
            'description' => 'This is an example Laravel pdf tutorial.',
            'footer' => 'by <a href="https://codeanddeploy.com">codeanddeploy.com</a>'
        ]);
    
    }
    public function studentAcademicResult(Request $request)
    {
        $studentId= $request->studentId;
        $sessionId= $request->sessionId;
        $levelId= $request->levelId;
        $schoolId= $request->schoolId;
        if(!$studentId | !$sessionId | !$levelId | !$schoolId){
            return response()->json([
                'status' => false,
                'message' => 'Incomplete Parameters',
            ], 400);
        }
        $school = School::find($schoolId);
        $level = Level::find($levelId);
        $student = Student::find($studentId);
        if(!$school){
            return response()->json([
                'status' => false,
                'message' => 'School not found',
            ], 400);
        }
        if(!$level){
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 400);
        }
        if(!$student){
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ], 400);
        }
        $setting = SchoolSessionSetting::where('session_id', $sessionId)->where('school_id', $schoolId)->first();
        if(!$setting){
            return response()->json([
                'status' => false,
                'message' => 'School has not set not completed setup',
            ], 400);
        }
        // TO DO: check to see wether is custodian or student or admin or staff-classteacher if not throw err
        // TO DO: throw err if the required params are not provided
        $courses = CourseParticipantRecord::with(['course', 'level'])->where('school_session_id',$sessionId)->where('student_id',$studentId)->where('level_id',$levelId)->get();
        $levelStudentCount = count(CourseParticipantRecord::where('school_session_id',$sessionId)->where('level_id',$levelId)->get()->unique('student_id')->values()->all());
        $classParticipants = CourseParticipantRecord::where('school_session_id',$sessionId)->where('level_id',$levelId)->get()->groupBy(['level_id', 'student_id']);
        $totalOfSelectedStudent = $courses->sum('total');
        $averageOfSelectedStudent = $courses->avg('total');
        $totalOfEachStudent = [];
        $avgOfEachStudent = [];
        $studentsInClass = $classParticipants->all()[$levelId];
        foreach ($studentsInClass as $key => $value) {
            $total = $value->sum('total');
            $avg = $value->avg('total');
            
            array_push($totalOfEachStudent, $total);
            array_push($avgOfEachStudent, $avg);
        }
        $avgOfEachStudentRanked = collect($avgOfEachStudent)->sortDesc()->unique(); //unique so position is determined
        $avgOfEachStudentRanked = $avgOfEachStudentRanked->values()->all(); //unique so position is determined
        $position = 0;
        foreach ($avgOfEachStudentRanked as $key => $value) {
            if($value === $averageOfSelectedStudent){
                $position = $key + 1;
                break;
            }
        }
        $classAverage = collect($avgOfEachStudent); //done so avg can be calculated properly with repeated values
        $classAverage = $classAverage->avg();

        $data = [
            'leftItems'=> ['Student ID' => $student->id_number, 'Class' => $level->name, 'Gender' => $student->user->gender, 'No in Class'=> $levelStudentCount, 'session'=>$setting->session_id->name],
            'rightItems'=> ['Student Name' => $student->user->name, 'position'=>$position, 'Total Score'=> $totalOfSelectedStudent, 'Student Average'=>$averageOfSelectedStudent, 'Class Average'=>$classAverage],
            'student'=>$student,
            'school'=>$school,
            'setting'=>$setting,
            'courses'=> $courses,
            'title' => 'Academic Progress Report',
            'description' => 'This is an example Laravel pdf tutorial.',
            'footer' => 'by <a href="https://isaac-odeh-portfolio.netlify.app/">Isaac Odeh</a>'
        ];
        $pdf = PDF::loadView('student.result', $data);
        return $pdf->stream('resume.pdf');

        // return $pdf->download('sample.pdf');
    
    }
    public function singleStudent(Request $request, $schoolId, $studentId)
    {
        //
        // $results = Student::all();
        // // return $results;
        // return StudentResource::collection($results);

         // PLease use resoURCES
        //  $sessionId = $request->sessionId;

         $result = Student::find($studentId);
        //  if($sessionId){
        //      $enrollmentStatus = EnrolledStudent::where('student_id',$studentId)->where('school_session_id', $sessionId)->first();
        //      $result->enrollmentStatus = $enrollmentStatus ? true : false;
        //     //  add enrollment status here => with
        //  }
        // sessionId is present here

         return new StudentResource($result);
    }

    public function studentSessionCoursesGroupedByLevel(Request $request, $studentId)
    {


        // return levels by course association
        // and levels by level association
        // or consider join
        // or on frontend do the necessary -- bingo


        $courses = CourseParticipantRecord::with(['course', 'level'])->where('school_session_id',$request->sessionId)->where('student_id',$studentId)->get();

        $coursesGroupedByLevelForStudent = $courses->groupBy('level_id')->all();



        // also return the level details, so you can group it properly
        // return both and seperate in frontendForStudent
        // START FROM HERE -> api.php -> postman  -> frontend

        $data =  [ 'courses'=> $courses , 'coursesGroupedByLevel' => $coursesGroupedByLevelForStudent];

        return new StaffSessionCourseNLevelResource($data);

    }
    public function index(Request $request, $id)
    {
        //
        // $results = Student::all();
        // // return $results;
        // return StudentResource::collection($results);

         // PLease use resoURCES
         $perPage = $request->limit ? $request->limit : 4;
         $results = Student::where('school_id',$id)->paginate($perPage);


         if($request->searchTerm){
             $results = Student::where('school_id',$id)->whereLike(['user.name'], $request->searchTerm)->paginate($perPage);
         }


         if($request->custodianId){
             $results = Custodian::find($request->custodianId)->students;
         }
         return StudentResource::collection($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function exportBulkUpload(Request $request, $schoolId)
     {
         //
         return Excel::download(new StudentsExport($schoolId), 'students.xlsx');
     }
     public function addStudentInBulk(Request $request)
     {
       $school = School::find($request->schoolId);
       $currentSessionId = $school->current_session_id;
         $password = $this->defaultPassword;
          //validate the request
          if($request->id && $request->schoolId !==  Student::find($request->id)->school_id  ){
             return 'Not allowed';
         }
         // create the departments
         $entries = $request->jsonData;
         $records = [];
         // TO do
         // The user accounts should first be created with unique emails / or found if they exist
         // when created they should be iterated over while comparing with the array sent
         // this will then result in a user id merger that will allow a fully formed array that can be inserted
         // should be a try & catch and validation has to exist
         // notify all accounts
         // also do you want all staff to be able to login
         // or rather staff accounts alone be created for them
         // and on login the user account is created for them

         // create user in bulk
         // create/update user/school in bulk
         // create staff account in bulk
         // notify all concerned
         $emails = [];
         foreach ($entries as $entry) {
            # code...
            array_push($emails,$entry['student email']);
         }
         $users = User::whereIn('email',$emails)->get();

         $emailsWithAccounts = [];
         foreach ($users as $user) {

            array_push($emailsWithAccounts,$user->email);
         }
         $emailsWithoutAccounts = [];
         foreach ($emails as $email) {
            # code...
            if (!in_array($email,$emailsWithAccounts)) {
              // code...
              array_push($emailsWithoutAccounts,$email);


            }
         }
         $entriesWithoutAccounts = [];

         // create user accounts for emails without one
         foreach ($entries as $entry) {
            # code...
            if (!in_array($entry['student email'],$emailsWithAccounts)) {
              $userName = $entry['student first name'] ." ".$entry['student middle name']." ". $entry['student last name'];

              array_push($entriesWithoutAccounts, [
                  // 'name'=>"$entry['first name']" ." $entry['last name']",
                  'name' => $userName,
                  'email' => $entry['student email'],
                  'choosen_school_id'=>$request->schoolId,
                  'password' => Hash::make($password),
                  'created_at'=>date('Y-m-d H:i:s'),
                  'updated_at'=>date('Y-m-d H:i:s'),


              ]);
          }
         }
         // Insert the user records
         $users = User::insert($entriesWithoutAccounts);
         $allUsers = User::whereIn('email',$emails)->get();
         // Insert many to many record with appropriate role


         $studentEntries = [];
         foreach ($allUsers as $user) {
           $levelName = $this->findStudentByEmail($user->email, $entries)['student current class'];
           $level = Level::where('name', $levelName)->where('school_id', $request->schoolId)->first();

            array_push($studentEntries, [

                'id_number' => $this->findStudentByEmail($user->email, $entries)['student ID'],
                'user_id' => $user->id,
                'school_id' => $request->schoolId ,'current_level_id'=> $level->id,  'latest_session_id' => $currentSessionId,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),


            ]);
         }
         $students = Student::insert($studentEntries);
         $userIds = $allUsers->map(function ($item, $key) {
             return $item['id'];
         });

         $studentRecords = Student::where('school_id', $request->schoolId )->whereIn('user_id', $userIds)->get();



         $schoolUserEntries = [];
         foreach ($studentRecords as $record) {


            array_push($schoolUserEntries, [

                'user_id' => $record->user_id,
                'school_id'=>$request->schoolId,
                'choosen_role'=>'student',
                'school_user_roles'=>'["student"]',
                'staff_id' =>$record->id,

                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),


            ]);
         }
         SchoolUser::insert($schoolUserEntries);

         $school = School::find($request->schoolId);

         // notify all users
         Notification::send($allUsers,new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been added as a student to $school->name", "Student Profile Created", "Password: $password"));




         return response()->json([
            'status' => true,
            'message' => count($studentRecords)  .' students were added successfully!',
            'data' => $studentRecords,



        ], 200);
     }
     private function findStudentByEmail($email, $entries)
     {
       // code...
       foreach ($entries as $entry) {

          if($entry['student email'] === $email){
            return $entry;
          }
       }
       return null;
     }

    public function enrollNewStudentForSession(Request $request, $schoolId)
    {
        // idNo: string;
        // name: string;
        // email?: string;
        // phone?: string;
        // userId?: string;
        // currentClassId: string;
        // schoolFeeAmountPaid: string;
        // popDocumentUrl: string;
        // sessionCourses: { levelId: string; courseId: string }[];
        // return 'card';



        $user = User::where('email',$request->email)->first();
        $school = School::find($schoolId);
        $currentSessionId = $school->current_session_id;


        // create user if user doesn't exist
        if(!$user){

            $user = $this->createUser($request->name,
                $request->email,
                $this->defaultPassword
            );
            // attach the user acc to a school
            // $this->addUserToSchool($user->id, $schoolId);
            $this->addUserToSchool($user->id, $request->schoolId, 'student',['student']);
            // Also update the user role
            // $this->addRoleToUserInSchool($user->id, $schoolId, ['student']);
        }




        // create student profile if doesnt exisst
        $student = Student::create([ 'user_id'=> $user->id, 'school_id' => $schoolId ,'current_level_id'=> $request->currentClassId,  'id_number'=>$request->idNo, 'latest_session_id' => $currentSessionId, 'alt_phone'=>$request->altPhone, 'alt_email'=>$request->altEmail]);
        // update the school_user relationship
        $user->schools()->updateExistingPivot($school->id, ['student_id'=>$student->id ]);


        // $levelFee = LevelSchoolFee::where('session_id', $request->currentSessionId)->where('fee_category_id', $request->paymentCategoryId)->where('level_id',$request->currentClassId)->first();
        // if(!$levelFee){
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'A fee was not created for selected class, contact your admin!',
        //
        //
        //     ], 404);
        // }
        // // student < - > school_sessions (many to many)
        // // create the students payments & indicate wether payment is complete based on policy wether part payment is allowed
        // $isComplete = $request->schoolFeeAmountPaid < $levelFee->amount ? 0 : 1;
        // $isBelow = $request->schoolFeeAmountPaid < $levelFee->amount ? 1 : 0;
        // $isInExcess = $request->schoolFeeAmountPaid > $levelFee->amount ? 1 : 0;
        // StudentSessionPayment::create(['student_id'=>$student->id,'pop_document_url'=> $request->popDocumentUrl, 'amount'=>$request->schoolFeeAmountPaid, 'is_complete'=>$isComplete, 'session_id'=>$request->currentSessionId, 'level_fee_id'=>$levelFee->id, 'recorder_id'=>auth()->user()->id]);

        // create the student session courses
        $studentCourseCombo = [];
        foreach ($request->sessionCourses as $entry) {
          array_push($studentCourseCombo, [
              'school_session_id' => $currentSessionId,
              'course_id'=>$entry['courseId'],
              'level_id'=>$entry['levelId'],
              'student_id'=>$student->id,
              'created_at'=>date('Y-m-d H:i:s'),
              'updated_at'=>date('Y-m-d H:i:s'),


          ]);
       }
       CourseParticipantRecord::insert($studentCourseCombo);


       // create the student custodians
       $entries = $request->custodians;
       if($request->custodians){
         $emails = [];
         foreach ($request->custodians as $entry) {
            # code...
            array_push($emails,$entry['email']);
         }

         $users = User::whereIn('email',$emails)->get();

         $emailsWithAccounts = [];
         foreach ($users as $user) {

            array_push($emailsWithAccounts,$user->email);
         }
         $emailsWithoutAccounts = [];
         foreach ($emails as $email) {
            # code...
            if (!in_array($email,$emailsWithAccounts)) {
              // code...
              array_push($emailsWithoutAccounts,$email);


            }
         }
         $entriesWithoutAccounts = [];

         // create user accounts for emails without one
         foreach ($entries as $entry) {
            # code...
            if (!in_array($entry['email'],$emailsWithAccounts)) {
              $userName = $entry['name'];

              array_push($entriesWithoutAccounts, [
                  // 'name'=>"$entry['first name']" ." $entry['last name']",
                  'name' => $userName,
                  'email' => $entry['email'],
                  'choosen_school_id'=>$request->schoolId,
                  'password' => Hash::make($this->defaultPassword),
                  'created_at'=>date('Y-m-d H:i:s'),
                  'updated_at'=>date('Y-m-d H:i:s'),


              ]);
          }
         }
         // Insert the user records
         $users = User::insert($entriesWithoutAccounts);

         $allUsers = User::whereIn('email',$emails)->get();
         $custodians = [];

         foreach ($allUsers as $user) {
           array_push($custodians, [

               'school_id' => $schoolId,
               'user_id'=>$user->id,
               'alt_phone'=>$this->findCustodianByEmail($user->email, $entries)['phone'],
               'occupation'=>$this->findCustodianByEmail($user->email, $entries)['occupation'],

               'alt_email'=>$user->email,
               'created_at'=>date('Y-m-d H:i:s'),
               'updated_at'=>date('Y-m-d H:i:s'),


           ]);
        }
        Custodian::insert($custodians);
       }
      // Enroll student (necessary for distinctions that have paid or not -> useful when school fees is done)
      $enrolledStudent = EnrolledStudent::create(['student_id'=>$student->id,'school_session_id'=>$currentSessionId]);


        // TO DO
        // Enroll Student with selected course combo

        // return new StudentResource($student);

        // notify all custodians
        $studentName = $student->user->name;
        // TO DO store in .env -> http://localhost:3000/login
        if(isset($allUsers)){
          Notification::send($allUsers,new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been added as a custodian for $studentName in $school->name", "Custodian Profile Created", "Password: $this->defaultPassword"));
        }

        // notify student
        $student->user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been added as a student to $school->name", "Student Profile Created", "Password: $this->defaultPassword")));




        return response()->json([
           'status' => true,
           'message' => "$studentName was added successfully as a student!",
           'data' => $student,



       ], 200);
    }
    private function findCustodianByEmail($email, $entries)
    {
      // code...
      foreach ($entries as $entry) {

         if($entry['email'] === $email){
           return $entry;
         }
      }
      return null;
    }

    public function assignToCustodian(Request $request, $id)
    {
        //
        $student = Student::find($id);
        $student->custodians()->syncWithoutDetaching($request->custodianId);
        // return $level->schoolSessions[0];
        return ['message'=>'This student was successfully assigned to a custodian'];

        // return new LevelResource($level);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

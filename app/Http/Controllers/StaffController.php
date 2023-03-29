<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\School;
use App\Models\User;
use App\Models\ClassTeacherRecord;
use App\Models\SchoolUser;
use App\Models\CourseTeacherRecord;
use App\Http\Resources\StaffResource;
use App\Http\Resources\CourseTeacherRecordResource;
use App\Http\Resources\StaffSessionCourseNLevelResource;
use Illuminate\Support\Facades\Hash;
use App\Traits\BaseUserTrait;
use App\Notifications\NotifyUser;
use App\Exports\StaffExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;



class StaffController extends Controller
{
    use BaseUserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function singleStaff(Request $request, $schoolId, $staffId)
    {
        //
        // $results = Student::all();
        // // return $results;
        // return StudentResource::collection($results);

         // PLease use resoURCES
        //  $sessionId = $request->sessionId;

         $result = Staff::where('id',$staffId)->where('school_id', $schoolId)->first();
        //  if($sessionId){
        //      $enrollmentStatus = EnrolledStudent::where('student_id',$studentId)->where('school_session_id', $sessionId)->first();
        //      $result->enrollmentStatus = $enrollmentStatus ? true : false;
        //     //  add enrollment status here => with
        //  }
        // sessionId is present here

         return new StaffResource($result);
    }



    // this function returns the levels associated with a staff for a particular session
    // association can be by teaching courses in that class or by being a class teacher
    public function staffSessionLevelsAndCourses(Request $request, $staffId)
    {


        // return levels by course association
        // and levels by level association
        // or consider join
        // or on frontend do the necessary -- bingo

        $courses = CourseTeacherRecord::with(['course', 'level'])->where('school_session_id',$request->sessionId)->where('staff_id',$staffId)->get();

        $coursesGroupedByLevel = $courses->groupBy('level_id')->all();

        $classesGroupedBySession = ClassTeacherRecord::with('level')->where('school_session_id',$request->sessionId)->where('staff_id',$staffId)->get();

        // also return the level details, so you can group it properly
        // return both and seperate in frontend
        // START FROM HERE -> api.php -> postman  -> frontend

        $data =  ['classesGroupedBySession' => $classesGroupedBySession, 'coursesGroupedByLevel' => $coursesGroupedByLevel, ];

        return new StaffSessionCourseNLevelResource($data);

    }
    public function index(Request $request, $id)
    {
        $perPage = $request->limit ? $request->limit : 4;


        // return $results;
        $results = Staff::with(['user', 'classTeacherRecords'])->where('school_id',$id)->paginate($perPage);
        if($request->searchTerm){
            $results = Staff::where('school_id',$id)->whereLike(['user.name', 'staff_no'], $request->searchTerm)->with(['user'])->paginate($perPage);
        }
        // return $results;

        return StaffResource::collection($results);

    }

    public function singleStaffCourseTeacherRecords(Request $request, $schoolId, $staffId)
    {
        $perPage = $request->limit ? $request->limit : 4;
        $sessionId = $request->sessionId;
        $levelId = $request->levelId;


        // return $results;
        $results = CourseTeacherRecord::with(['course', 'staff.user'])->where('staff_id',$staffId)->where('school_session_id',$sessionId)->paginate($perPage);
        if($request->searchTerm){
            $results =CourseTeacherRecord::whereLike(['course.name'], $request->searchTerm)->with(['course', 'staff'])->where('staff_id',$staffId)->where('school_session_id',$sessionId)->paginate($perPage);
        }
        if($request->levelId && $request->searchTerm){

            $results =CourseTeacherRecord::whereLike(['course.name'], $request->searchTerm)->with(['course', 'staff.user'])->where('staff_id',$staffId)->where('level_id',$levelId)->where('school_session_id',$sessionId)->paginate($perPage);
        }
        if($request->levelId){
            $results =CourseTeacherRecord::with(['course', 'staff.user'])->where('staff_id',$staffId)->where('level_id',$levelId)->where('school_session_id',$sessionId)->paginate($perPage);
        }
        // return $results;

        return CourseTeacherRecordResource::collection($results);

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
    public function exportBulkUpload()
    {
        //
        return Excel::download(new StaffExport, 'staff.xlsx');
    }
    private function findStaffByEmail($email, $entries)
    {
      // code...
      foreach ($entries as $entry) {

         if($entry['email'] === $email){
           return $entry;
         }
      }
      return null;
    }
    public function addStaffInBulk(Request $request)
    {
        $password = $this->defaultPassword;
         //validate the request
         if($request->id && $request->schoolId !==  Staff::find($request->id)->school_id  ){
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
             $userName = $entry['first name'] ." ".$entry['middle name']." ". $entry['last name'];

             array_push($entriesWithoutAccounts, [
                 // 'name'=>"$entry['first name']" ." $entry['last name']",
                 'name' => $userName,
                 'email' => $entry['email'],
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


        $staffEntries = [];
        foreach ($allUsers as $user) {


           array_push($staffEntries, [

               'staff_no' => $this->findStaffByEmail($user->email, $entries)['staff no'],
               'user_id' => $user->id,
               'school_id'=>$request->schoolId,
               'isActive'=>1,
               'created_at'=>date('Y-m-d H:i:s'),
               'updated_at'=>date('Y-m-d H:i:s'),


           ]);
        }
        $staff = Staff::insert($staffEntries);
        $userIds = $allUsers->map(function ($item, $key) {
            return $item['id'];
        });

        $staffRecords = Staff::where('school_id', $request->schoolId )->whereIn('user_id', $userIds)->get();



        $schoolUserEntries = [];
        foreach ($staffRecords as $record) {


           array_push($schoolUserEntries, [

               'user_id' => $record->user_id,
               'school_id'=>$request->schoolId,
               'choosen_role'=>'staff',
               'school_user_roles'=>'["staff"]',
               'staff_id' =>$record->id,

               'created_at'=>date('Y-m-d H:i:s'),
               'updated_at'=>date('Y-m-d H:i:s'),


           ]);
        }
        SchoolUser::insert($schoolUserEntries);

        $school = School::find($request->schoolId);

        // notify all users
        Notification::send($allUsers,new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been added as a staff to $school->name", "Staff Profile Created", "Password: $password"));




        return response()->json([
           'status' => true,
           'message' => count($staffRecords)  .' staff were added successfully!',
           'data' => $staffRecords,



       ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create a base user account
        $user = User::where('email',$request->email)->first();
        if(!$user){
            // return 'reached';
            // create user account
            $user = $this->createUser($request->name,
                $request->email,
                $request->password
            );


        }
        // attach the user acc to a school
        $this->addUserToSchool($user->id, $request->schoolId, 'staff',['staff']);

        // create a staff profile for user in the school

        // conditions
        // first check if the user with the account exists
        // if the user exists create the user account but notify the user
        // the user has the option to exit himself
        // if the user doesnt exist attach user to school set no priveledges
        // also create a staff profile for user
        $staff = Staff::where('user_id',$user->id)->first();
        if($staff){
          return response()->json([
              'status' => true,
              'message' => "$user->name's staff profile already exists.",
              'data' => $staff,



          ], 400);

        }
        $staff = Staff::create(['user_id'=> $user->id, 'staff_no'=> $request->staffNo, 'isActive' => $request->isActive ? $request->isActive : true, 'school_id' => $request->schoolId]);
        // inform the staff via email that account has been created alonside the password to login
        $school = School::find($request->schoolId);
        $user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been added as a staff to $school->name", "Staff Profile Created", "Password: $request->password")));

        return response()->json([
            'status' => false,
            'message' => "$user->name's staff profile created successfully",
            'data' => $staff,



        ], 200);
        // return new StaffResource($staff);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $staff = Staff::with('user')->find($id);
      if($staff ===  null){
          return response()->json([
              'status' => false,
              'message' => 'This course doesn\'t exist.',
              'data' => null,



          ], 400);

      }
      return response()->json([
          'status' => true,
          'message' => "Staff retrieved successfully",
          'data' => $staff,



      ], 200);
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
      // TO do
      // Need to rethink staff, as staff name might need to be editted
        //
        $staff = Staff::find($id);
        $staff = $staff->update([ 'staff_no'=> $request->staffNo, 'isActive' => $request->isActive ? $request->isActive : true]);
        // inform the staff via email that account has been created alonside the password to login
        $school = School::find($request->schoolId);
        $staff = Staff::find($id);

        $staff->user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been added as a staff to $school->name", "Staff Profile Updated", "Staff No: $request->staffNo")));

        return response()->json([
            'status' => false,
            'message' => "$user->name's staff profile updated successfully",
            'data' => $staff,



        ], 200);
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

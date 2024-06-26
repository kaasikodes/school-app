<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\SchoolSession;
use App\Models\SchoolCourseRecordTemplate;
use App\Models\SchoolSessionSetting;
use App\Traits\BaseUserTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;




class SchoolController extends Controller
{
    use BaseUserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function registerStaff(Request $request, $schoolId)
     {
         try{
           //create user or get user if users exists already
           $user = User::where('email', $request->userEmail)->first();

           if(!$user){
             // consider verifying by email & the forgot password
             $user = User::create([
                 'name' => $request->userFullName,
                 'email' => $request->userEmail,
                 'password' => Hash::make($request->password)
             ]);
           }
           // create schools
           // assign the user an admin and a staff role
           //validate the request
           // create the school
           $school = School::find($schoolId);
           if(!$school){
            // throw err / or return proper response
            
           }
            //TO DO: check 4 staff, if already exists in school, via staffNo




           // add the user who created the school to :the school as admin
           $school->users()->syncWithoutDetaching($user->id);
           $staff = Staff::create(['user_id'=>$user->id, 'school_id'=>$school->id, 'staff_no' => $request->staffNo ?? 'SCH:NAME_RANDOM_5']);
           // make user admin in school created
           $user->schools()->updateExistingPivot($school->id, ['staff_id'=>$staff->id, 'choosen_role'=> 'staff','school_user_roles'=> json_encode(['staff'])]);

           // update the choosen_school_id
           $user->choosen_school_id = $school->id;
           $user->save();

           return response()->json([
               'status' => true,
               'user' => $user,
               'schools' => $user->schools,


               'message' => 'School registered successfully',
               'token' => $user->createToken("API TOKEN")->plainTextToken,
           ], 200);
         }catch (\Throwable $th) {
             return response()->json([
                 'status' => false,
                 'message' => $th->getMessage()
             ], 500);
         }


     }
     public function registerSchool(Request $request)
     {
         try{
           //create user or get user if users exists already
           $user = User::where('email', $request->userEmail)->first();

           if(!$user){
             // consider verifying by email & the forgot password
             $user = User::create([
                 'name' => $request->userFullName,
                 'email' => $request->userEmail,
                 'password' => Hash::make($request->password)
             ]);
           }
           // create schools
           // assign the user an admin and a staff role
           //validate the request
           // create the school
           $school = School::create(['name' => $request->schoolName, 'email' =>$request->userEmail, 'phone'=> $request->userPhone ]);

           // create first school session
           $session = SchoolSession::create(['name'=>'First Session', 'school_id' => $school->id, 'description'=>'This is the first academic session in your school', 'starts'=>date("Y-m-d", time())]);
           $school->current_session_id = $session->id;
           $school->save();



           // add the user who created the school to :the school as admin
           $school->users()->syncWithoutDetaching($user->id);
           $admin = Admin::create(['user_id'=>$user->id, 'school_id'=>$school->id]);
           $staff = Staff::create(['user_id'=>$user->id, 'school_id'=>$school->id, 'staff_no' => '0001']);
           // make user admin in school created
           $user->schools()->updateExistingPivot($school->id, ['staff_id'=>$staff->id, 'admin_id'=>$admin->id,'choosen_role'=> 'admin','school_user_roles'=> json_encode(['admin','staff'])]);

           // update the choosen_school_id
           $user->choosen_school_id = $school->id;
           $user->save();

           return response()->json([
               'status' => true,
               'user' => $user,
               'schools' => $user->schools,


               'message' => 'School registered successfully',
               'token' => $user->createToken("API TOKEN")->plainTextToken,
           ], 200);
         }catch (\Throwable $th) {
             return response()->json([
                 'status' => false,
                 'message' => $th->getMessage()
             ], 500);
         }


     }
    public function index(Request $request)
    {
        //
        $perPage = $request->limit ? $request->limit : 4;
        $results = auth()->user()->schools;

        return response()->json([
            'status' => true,
            'message' => 'User schools retrieved succesfully!',
            'data' => $results,

        ], 200);
    }


  
    public function allSchools(Request $request)
    {
        $perPage = $request->limit ? $request->limit : 4;

        $results = School::paginate($perPage);
        return response()->json([
            'status' => true,
            'message' => 'Existing schools retrieved succesfully!',
            'data' => $results,

        ], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(Request $request, $id)
    {
        //
        $school =  School::find($id);
        $school->logo = $request->photo;
        $school->save();

        return response()->json([
            'status' => true,
            'message' => 'The school logo has been updated successfully!',
            'school' => $school,
            'photo' => $request->photo,


        ], 200);
    }
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
    public function store(Request $request)
    {
        //
        //validate the request
        // create the school
        $school = School::updateOrCreate(['id'=> $request->id],[ 'name' => $request->name, 'description'=> $request->description, 'email'=>auth()->user()->email, 'phone'=>auth()->user()->phone]);

        $session = SchoolSession::create(['name'=>'First Session', 'school_id' => $school->id, 'description'=>'This is the first academic session in your school', 'starts'=>date("Y-m-d", time())]);
        $school->current_session_id = $session->id;
        $school->save();

        $this->addUserToSchool(auth()->user()->id, $school->id, 'admin',['admin']);


        return ['result'=>$school, 'schools'=>auth()->user()->schools];
    }
    public function saveCourseRecordTemplate(Request $request , $schoolId)
    {
        $breakDown = json_encode($request->breakDown);
        $result = SchoolCourseRecordTemplate::updateOrCreate(['id'=> $request->crtId],[ 'title' => $request->title, 'school_id'=> $schoolId, 'isActive'=> $request->isActive ? $request->isActive : true, 'break_down'=> $breakDown]);

        return response()->json([
            'status' => true,
            'message' => 'Template saved succesfully!',
            'data' => $result,

        ], 200);
    }
    public function getCourseRecordTemplates( $schoolId)
    {
        $result = SchoolCourseRecordTemplate::with(['sessionsUsedIn'])->where('school_id',$schoolId)->get();

        return response()->json([
            'status' => true,
            'message' => 'Templates retrieved succesfully!',
            'data' => $result,

        ], 200);
    }
    public function getCourseRecordSingleTemplate($id)
    {
        $result = SchoolCourseRecordTemplate::find($id);

        return response()->json([
            'status' => true,
            'message' => 'Template retrieved succesfully!',
            'data' => $result,

        ], 200);
    }

    // school session templates & policies
    public function setupSchoolSessionCRTemplate(Request $request , $schoolId)
    {
        $result = SchoolSessionSetting::updateOrCreate(['school_id'=> $schoolId, 'session_id' => $request->sessionId],['course_record_template_id'=> $request->templateId]);

        return response()->json([
            'status' => true,
            'message' => 'Course Recording Template assigned to current school session succesfully!',
            'data' => $result,

        ], 200);
    }
    // schools settings
    public function saveSchoolSessionSetting(Request $request , $schoolId)
    {
        $result = SchoolSessionSetting::updateOrCreate(['school_id'=> $schoolId, 'session_id' => $request->sessionId],['course_record_template_id'=> $request->templateId, 'grading_policy_id'=>$request->gradePolicyId, 'student_enrollment_policy_id' => $request->studentEnrollmentPolicyId]);

        return response()->json([
            'status' => true,
            'message' => 'School session settings saved succesfully!',
            'data' => $result,

        ], 200);
    }
    public function getSchoolSessionSetting(Request $request , $schoolId, $sessionId)
    {
        $result = SchoolSessionSetting::where(['school_id'=> $schoolId, 'session_id' => $sessionId])->first();

        return response()->json([
            'status' => true,
            'message' => 'School session setting retrieved succesfully!',
            'data' => $result,

        ], 200);
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
        return School::find($id);
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

        try {
            $result = School::find($id);
            $sessions = $result->sessions;

            $message = 'The school cannot be deleted because it has sessions!';


            if (count($sessions) > 0) {
                return response()->json(['message' => $message, 'data' => $result],400);

            }


            $result->delete();



            return response()->json(['message' => $message, 'data' => $result],201);

        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()],404);
        }
    }
}

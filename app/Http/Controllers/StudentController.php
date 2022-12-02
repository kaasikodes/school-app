<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentSessionPayment;
use App\Models\LevelSchoolFee;
use App\Models\EnrolledStudent;

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
    public function enrollStudentForSession(Request $request, $schoolId)
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
        $user = User::where('email',$request->email)->orWhere('id',$request->userId)->first();
         
        // create user if user doesn't exist
        if(!$user){
            
            $user = $this->createUser($request->name,
                $request->email,
                $this->defaultPassword
            );
            // attach the user acc to a school
            $this->addUserToSchool($user->id, $schoolId);
            // Also update the user role
            $this->addRoleToUserInSchool($user->id, $schoolId, ['student']);
        }
        
        
        

        // create student profile if doesnt exisst
        $student = Student::updateOrCreate([ 'user_id'=> $user->id, 'school_id' => $schoolId], ['current_level_id'=> $request->currentClassId,  'id_number'=>$request->idNo, 'current_session_id' => $request->currentSessionId, 'alt_phone'=>$request->altPhone, 'alt_email'=>$request->altEmail]);


        $levelFee = LevelSchoolFee::where('session_id', $request->currentSessionId)->where('fee_category_id', $request->paymentCategoryId)->where('level_id',$request->currentClassId)->first();
        if(!$levelFee){
            return response()->json([
                'status' => false,
                'message' => 'A fee has not created for selected class, contact your admin!',
              
    
            ], 404);
        }
        // student < - > school_sessions (many to many)
        // create the students payments & indicate wether payment is complete based on policy wether part payment is allowed
        $isComplete = $request->schoolFeeAmountPaid < $levelFee->amount ? 0 : 1; 
        $isBelow = $request->schoolFeeAmountPaid < $levelFee->amount ? 1 : 0; 
        $isInExcess = $request->schoolFeeAmountPaid > $levelFee->amount ? 1 : 0; 
        StudentSessionPayment::create(['student_id'=>$student->id,'pop_document_url'=> $request->popDocumentUrl, 'amount'=>$request->schoolFeeAmountPaid, 'is_complete'=>$isComplete, 'session_id'=>$request->currentSessionId, 'level_fee_id'=>$levelFee->id, 'recorder_id'=>auth()->user()->id]);
        // create the student session courses

        // Enroll student
        $enrolledStudent = EnrolledStudent::create(['student_id'=>$student->id,'school_session_id'=>$request->currentSessionId]);
       
        return new StudentResource($student);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentSessionPayment;
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
 
         return $results;
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
    public function store(Request $request)
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
            $this->addUserToSchool($user->id, $request->schoolId);
            // Also update the user role
            $this->addRoleToUserInSchool($user->id, $request->schoolId, ['student']);
        }
        
        
        

        // create student profile if doesnt exisst
        $student = Student::updateOrCreate([ 'user_id'=> $user->id, 'school_id' => $request->schoolId], ['current_level_id'=> $request->currentClassId,  'id_number'=>$request->idNo, 'current_session_id' => $request->currentSessionId]);
        // student < - > school_sessions (many to many)
        // create the students payments & indicate wether payment is complete based on policy
        $isComplete = 0; //based of the school fee amount set for each class for that session //alloww for supporting doc breakdown upload for now
        StudentSessionPayment::create(['student_id'=>$student->id,'pop_document_url'=> $request->popDocumentUrl, 'amount'=>$request->schoolFeeAmountPaid, 'is_complete'=>$isComplete])
        // create the student session courses
       
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

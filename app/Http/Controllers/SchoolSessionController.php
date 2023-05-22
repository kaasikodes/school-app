<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolSession;
use App\Models\School;
use App\Models\ClassTeacherRecord;
use App\Models\CourseTeacherRecord;
use App\Models\CourseParticipantRecord;


class SchoolSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $perPage = $request->limit ? $request->limit : 4;

        $results = SchoolSession::where('school_id',$id)->paginate($perPage);
        if($request->searchTerm){
            $results = SchoolSession::where('school_id',$id)->whereLike(['name','ends','starts'], $request->searchTerm)->paginate($perPage);
        }

        return $results;
    }
    public function endSession($id)
    {
        $session= SchoolSession::find($id);
        
        if($session->result_issued !== 'YES'){
            return response()->json([
                'status' => false,
                'message' => "Session result is yet to be issued",
                'data' => $session
               
              
    
            ], 400);

        }
        $session->ends = date('Y-m-d H:i:s');
        $session->ended_at = date('Y-m-d H:i:s');
        $session->save();

        return response()->json([
            'status' => true,
            'message' => 'Session has been closed successfully!',
            'data' => $session,
        ], 200);


    }
    public function issueSessionResult($id)
    {
        $session= SchoolSession::find($id);
        $courseTeachersInViolation = CourseTeacherRecord::where(['school_session_id'=> $session->id, 'submitted_assessment_for_compilation' => 'NO'])->get();
        $courseTeachersInViolationCount = $courseTeachersInViolation->count();
        if($courseTeachersInViolationCount > 0){
            return response()->json([
                'status' => false,
                'message' => "$courseTeachersInViolationCount course teachers are yet to submit assessments for compilation!",
                'data' => ['courseTeachersInViolation'=>$courseTeachersInViolation]
               
              
    
            ], 400);

        }
        $session->result_issued = 'YES';
        $session->result_issued_at = date('Y-m-d H:i:s');
        $session->save();

        return response()->json([
            'status' => true,
            'message' => 'Result successfully issued for session!',
            'data' => $session,
        ], 200);


    }
    
    public function schoolSessionTaskCompletion($id)
    {
        
        //

        $session= SchoolSession::find($id);
        $school = School::find($session->school_id);
        $classTeachers = ClassTeacherRecord::where('school_session_id', $session->id)->get();
        $courseTeachers = CourseTeacherRecord::where('school_session_id', $session->id)->get();
        $courseParticipantsWithAssessment = CourseParticipantRecord::where('school_session_id', $session->id)->whereNotNull('break_down')->get();
        $courseTeachersWhoHaveSumbittedForReview = CourseTeacherRecord::where(['school_session_id'=> $session->id, 'submitted_assessment_for_compilation'=> 'YES'])->get();

        $status = 0;
        if($session){
            $status = 0;
        }
        if($school && $school->departments->count() >= 1){

            $status = 1;
            
        }
        if($school && $school->levels->count() >= 1){

            $status = 2;
            
        }
        if($school && $school->courses->count() >= 1){

            $status = 3;
            
        }
        if($school && $school->staff->count() >= 1){

            $status = 4;
            
        }
        if($school && $classTeachers->count() >= 1){

            $status = 5;
            
        }
        if($school && $courseTeachers->count() >= 1){

            $status = 6;
            
        }
        if($school && $school->students->count() >= 1){

            $status = 7;
            
        }
        if($school && $courseParticipantsWithAssessment->count() >= 1){

            $status = 8;
            
        }
        if($school && $courseTeachersWhoHaveSumbittedForReview->count() >= 1){

            $status = 9;
            
        }
        if($school && $session->result_issued === 'YES'){

            $status = 10;
            
        }
        if($school && $session->ended_at){

            $status = 11;
            
        }

       

        return response()->json([
            'status' => true,
            'message' => 'School Session Task Completion returned!',
            'data' => ['status'=> $status, 'sessionId' => $session->id],
           
          

        ], 200);
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
        $currentSchoolSession = SchoolSession::where('school_id',$request->schoolId)->orderBy('starts', 'desc')->first();
        if($currentSchoolSession && $currentSchoolSession->ends === null){
            return response()->json([
                'status' => false,
                'message' => 'You cannot create a new session without ending the current one!',
                'data' => null,
               
              
    
            ], 405);

        }
        //validate the request
        // create the session
        $session = SchoolSession::create(['starts'=> $request->starts, 'ends'=> $request->ends, 'name' => $request->name, 'description'=> $request->description, 'school_id'=>$request->schoolId]);
        return response()->json([
            'status' => true,
            'message' => 'School session has been created successfully!',
            'data' => $session,
           
          

        ], 200);

        return $session;
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
        $session =SchoolSession::find($id);
        if($session ===  null){
            return response()->json([
                'status' => false,
                'message' => 'School session doesn\'t exist.',
                'data' => null,
               
              
    
            ], 400);

        }
        return response()->json([
            'status' => true,
            'message' => 'School session retrieved successfully',
            'data' => $session,
           
          

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
        //
        $session =SchoolSession::find($id);
        if($session ===  null){
            return response()->json([
                'status' => false,
                'message' => 'School session doesn\'t exist.',
                'data' => null,
               
              
    
            ], 400);

        }

        //validate the request
        // update the session
        $session = $session->update(['starts'=> $request->starts, 'ends'=> $request->ends, 'name' => $request->name, 'description'=> $request->description]);
        return response()->json([
            'status' => true,
            'message' => 'School session has been updated successfully!',
            'data' => $session,
           
          

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

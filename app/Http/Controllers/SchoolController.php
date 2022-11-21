<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolCourseRecordTemplate;
use App\Models\SchoolSessionSetting;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->limit ? $request->limit : 4;
        $results = auth()->user()->schools;

        return response()->json([
            'status' => true,
            'message' => 'Template retrieved succesfully!',
            'data' => $results,

        ], 200);
    }
    public function addUserToSchool(Request $request, $id)
    {
        // return 'works';
        $school = School::find($id);
        $school->users()->syncWithoutDetaching($request->userId);
        return ['message'=>'This user was successfully added to the school'];

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
        //
        //validate the request
        // create the school
        $school = School::updateOrCreate(['id'=> $request->id],[ 'name' => $request->name, 'description'=> $request->description, 'studentLimit'=> $request->studentLimit, 'staffLimit'=> $request->staffLimit]);

        // add the user who created the school to :the school as admin
        $school->users()->syncWithoutDetaching(auth()->user()->id);
        // make user admin in school created
        auth()->user()->schools()->updateExistingPivot($school->id, ['choosen_role'=> 'admin','school_user_roles'=> json_encode(['admin','staff'])]);


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
        $result = SchoolCourseRecordTemplate::where('school_id',$schoolId)->get();

        return response()->json([
            'status' => true,
            'message' => 'Template retrieved succesfully!',
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
        $result = SchoolSessionSetting::updateOrCreate(['id'=> $request->ssId],[ 'session_id' => $request->session_id, 'school_id'=> $schoolId, 'course_record_template_id'=> $request->course_record_template_id]);

        return response()->json([
            'status' => true,
            'message' => 'Template saved succesfully!',
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

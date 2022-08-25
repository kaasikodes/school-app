<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\CourseParticipant;
use App\Models\CourseTeacher;
use App\Models\CourseLesson;
use App\Http\Resources\CourseResource;



use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Course::all();
        return CourseResource::collection($results);
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
        $level = Course::updateOrCreate(['id' => $request->id],['name'=> $request->name, 'description'=> $request->description, 'department_id' => $request->departmentId]);
        return $level;
    }
    public function assignToLevel(Request $request, $id)
    {
        //
        $course = Course::find($id);
        $course->levels()->syncWithoutDetaching($request->levelId);
        // return $course->levels[0];
        return ['message'=>'This course was successfully assigned to this level'];

    }
    public function addParticipant(Request $request, $id)
    {
        //
        $course = CourseParticipant::firstOrcreate(['student_id' => $request->studentId, 'level_id'=>$request->levelId, 'school_session_id'=> $request->sessionId, 'course_id' =>$id]);
        return ['message'=>'The participant was successfully added to this course'];

    }
    public function courseParticipants(Request $request, $id)
    {
        //
        $participants = CourseParticipant::where('course_id', $id)->get();
        return $participants;

    }
    public function assignTeacher(Request $request, $id)
    {
        //
        $result = CourseTeacher::firstOrcreate(['staff_id' => $request->staffId, 'level_id'=>$request->levelId, 'school_session_id'=> $request->sessionId, 'course_id' =>$id]);
        return ['message'=>'The staff was successfully assigned to this course'];

    }
    public function courseTeachers(Request $request, $id)
    {
        //
        $results = CourseTeacher::where('course_id', $id)->get();
        return $results;

    }
    public function assignDepartment(Request $request, $id)
    {
        //
        $course = Course::find($id);
        $course->update(['department_id' => $request->departmentId]);
        return ['message'=>'The course was successfully assigned to dept'];

    }
    public function addLesson(Request $request, $id)
    {
        //
        $result = CourseLesson::firstOrCreate(['name' => $request->name, 'content' => $request->content, 'level_id'=>$request->levelId, 'school_session_id'=> $request->sessionId, 'course_id' =>$id]);
        return ['message'=>'The lesson was successfully added to course'];

    }
    public function courseLessons(Request $request, $id)
    {
        //
        $results = CourseLesson::where('id', $id)->get();
        return $results;

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

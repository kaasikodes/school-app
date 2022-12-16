<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Level;
use App\Models\CourseParticipant;
use App\Models\CourseTeacher;
use App\Models\CourseLesson;
use App\Models\CourseAssessment;
use App\Models\CourseParticipantRecord;
use App\Models\CourseTeacherRecord;
use App\Models\CourseAssessmentQuestion;
use App\Models\CourseAssessmentSection;
use App\Models\CourseAssessmentQuestionOption;
use App\Models\CourseAssessmentQuestionAnswer;
use App\Models\CourseAssessmentQuestionCorrectAnswer;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LevelResource;
use App\Http\Resources\SchoolSessionCourse;
use App\Http\Resources\CourseParticipantResource;



use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
// from here
     public function coursesGroupedByLevel(Request $request, $schoolId)
     {
         //
         
         $levels = Level::where('school_id',$schoolId)->get();

         return LevelResource::collection($levels);
         // return CourseResource::collection($results);
     }
   
     public function sessionCourseParticipants(Request $request)
     {
        // return 'works';
         // calculate the total from the breakdown sent | since your comfortable with
         //  js calc from front and send to back
         $sessionId = $request->sessionId;
         $levelId = $request->levelId;
         $courseId = $request->courseId;
       
         $result = CourseParticipantRecord::where('school_session_id', $sessionId)->where('level_id',$levelId)->where('course_id', $courseId)->paginate();

      

        

      
         return CourseParticipantResource::collection($result);
     }


    //  student enrolling for one course
     public function saveParticipantRecord(Request $request)
     {
        // return 'works';
         // calculate the total from the breakdown sent | since your comfortable with
         //  js calc from front and send to back
 
         $participantId = $request->participantId;
        
         $breakDown = json_encode($request->breakDown);
         $participant = CourseParticipantRecord::find($participantId);

        //  grade policy as per diagram for users
        $total = $request->total;
        $grade = '';
        if($total >= 70 && $total <= 100) {
            $grade = 'A';
        }
        if($total >= 55 && $total < 70) {
            $grade = 'B';
        }
        if($total >= 45 && $total < 55) {
            $grade = 'C';
        }
        if($total >= 40 && $total < 45) {
            $grade = 'D';
        }
        if($total >= 20 && $total < 40) {
            $grade = 'P';
        }
        if($total >= 0 && $total < 20) {
            $grade = 'F';
        }
       

         $result = $participant->update([
          
            'grade'=>$grade,
            'total'=> $total,
            'break_down'=> $breakDown,
         ]);

         return response()->json([
            'status' => true,
            'message' => 'Record stored succesfully!',
            'data' => $result,
           
          

        ], 200);

        

      
         // return CourseResource::collection($results);
     }
     public function addSessionCourseTeacher(Request $request)
     {
        
         $sessionId = $request->sessionId;
         $levelId = $request->levelId;
         $staffId = $request->staffId;
         $courses = $request->courses;
         $records = [];
         foreach ($courses as $course) {
            # code...
            array_push($records, [
                'course_id'=>$course['courseId'],
                'level_id'=>$course['levelId'],
                'school_session_id'=>$sessionId,
                'staff_id'=>$staffId,

            ]);
         }
        //  make sure that you record make sure that an exception is 
        // thrown if a record has a session level n course as existing record
        // see how you can make all multiple keys serve as a unique one
         $result = CourseTeacherRecord::insert($records);

         return response()->json([
            'status' => true,
            'message' => 'Staff assigned session courses successfully!',
            'data' => $result,
           
          

        ], 200);

        
     }
     public function addSessionCourseParticipant(Request $request)
     {
        
         $sessionId = $request->sessionId;
         $levelId = $request->levelId;
         $studentId = $request->studentId;
         $courses = $request->courses;
         $records = [];
         foreach ($courses as $course) {
            # code...
            array_push($records, [
                'course_id'=>$course['courseId'],
                'level_id'=>$course['levelId'],
                'school_session_id'=>$sessionId,
                'student_id'=>$studentId,

            ]);
         }
        //  make sure that you record make sure that an exception is 
        // thrown if a record has a session level n course as existing record
        // see how you can make all multiple keys serve as a unique one
         $result = CourseParticipantRecord::insert($records);

         return response()->json([
            'status' => true,
            'message' => 'Student enrolled for courses successfully!',
            'data' => $result,
           
          

        ], 200);

        
     }
    public function participantRecords(Request $request, $id)
    {
        //
        $sessionId = $request->sessionId;
        $levelId = $request->levelId;
        $participantId = $request->participantId;
        // to filter result 
        $perPage = $request->limit ? $request->limit : 4;


        

        $records = CourseParticipantRecord::where('course_id',$id)->where('student_id',$participantId)->where('school_session_id',$sessionId)->where('level_id',$levelId)->paginate($perPage);

        // use a resource to return data in proper format
        // name
        // breakdown as json
        // etc
        // for now let only the primary teacher be able to mark

        

      
    

        return $records;
        // return CourseResource::collection($results);
    }
    public function index(Request $request, $id)
    {
        //
        $sessionId = $request->sessionId;

        $perPage = $request->limit ? $request->limit : 4;

        $results = Course::where('school_id',$id)->paginate($perPage);

        if($request->searchTerm){
            $results = Course::where('school_id',$id)->whereLike(['name','ends','starts',], $request->searchTerm)->paginate($perPage);

        }
    

        return $results;
        // return CourseResource::collection($results);
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
        $level = Course::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'description' => $request->description, 'department_id' => $request->departmentId, 'school_id' =>$request->schoolId]);
        return $level;
    }
    public function assignToLevel(Request $request, $id)
    {
        //
        $course = Course::find($id);
        $course->levels()->syncWithoutDetaching($request->levelId);
        // return $course->levels[0];
        return ['message' => 'This course was successfully assigned to this level'];
    }
    public function addParticipant(Request $request, $id)
    {
        //
        $course = CourseParticipant::firstOrcreate(['student_id' => $request->studentId, 'level_id' => $request->levelId, 'school_session_id' => $request->sessionId, 'course_id' => $id]);
        return ['message' => 'The participant was successfully added to this course'];
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
        $result = CourseTeacher::firstOrcreate(['staff_id' => $request->staffId, 'level_id' => $request->levelId, 'school_session_id' => $request->sessionId, 'course_id' => $id]);
        return ['message' => 'The staff was successfully assigned to this course'];
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
        return ['message' => 'The course was successfully assigned to dept'];
    }
    public function addLesson(Request $request, $id)
    {
        //
        $result = CourseLesson::firstOrCreate(['name' => $request->name, 'content' => $request->content, 'level_id' => $request->levelId, 'school_session_id' => $request->sessionId, 'course_id' => $id, 'course_teacher_id' => $request->teacherId]);
        return ['message' => 'The lesson was successfully added to course'];
    }
    public function courseLessons(Request $request, $id)
    {
        //
        $results = CourseLesson::where('course_id', $id)->get();
        return $results;
    }
    public function addAssessment(Request $request, $id)
    {
        //
        $result = CourseAssessment::firstOrCreate(['name' => $request->name, 'content' => $request->content, 'level_id' => $request->levelId, 'school_session_id' => $request->sessionId, 'course_id' => $id, 'course_teacher_id' => $request->teacherId]);
        return ['message' => 'The Assesment was successfully added to course'];
    }
    public function courseAssessments(Request $request, $id)
    {
        //
        $results = CourseAssessment::where('course_id', $id)->get();

        return $results;
    }
    public function addCourseAssessmentQuestion(Request $request, $id)
    {
        //
        $result = CourseAssessmentQuestion::firstOrCreate(['type' => $request->type, 'content' => $request->content, 'hint' => $request->hint, 'time_allowed' => $request->timeAllowed, 'course_assessment_id' => $request->assessmentId, 'course_assessment_section_id' => $request->sectionId, 'score' => $request->score]);
        return ['message' => 'The question was successfully added to assessment.'];
    }
    public function courseAssessmentQuestions(Request $request, $id)
    {
        //
        $results = CourseAssessmentQuestion::where('course_assessment_id', $id)->get();

        return $results;
    }
    public function updateQuestion(Request $request, $id)
    {
        //
        $question = CourseAssessmentQuestion::find($id);
        $result = $question;
        $result->update(['type' => $request->type ? $request->type : $result->type, 'content' => $request->content ? $request->content : $result->content, 'hint' => $request->hint ? $request->hint : $result->hint, 'time_allowed' => $request->timeAllowed ? $request->timeAllowed : $result->time_allowed, 'score' => $request->score ? $request->score : $result->score]);
        $result->save();

        return ['message' => 'Question updated successfully!', 'data' => $result];
    }
    public function updateQuestionSection(Request $request, $id)
    {
        //
        $question = CourseAssessmentQuestion::find($id);
        $result = $question;
        $result->course_assessment_section_id = $request->sectionId;
        $result->save();

        return ['message' => 'The Question\'s section updated successfully!', 'data' => $result];
    }
    public function updateQuestionAssessment(Request $request, $id)
    {
        //
        $question = CourseAssessmentQuestion::find($id);
        $result = $question;
        $result->course_assessment_id = $request->assessmentId;
        $result->save();

        return ['message' => 'The Question\'s assessment updated successfully!', 'data' => $result];
    }
    public function saveCourseAssessmentSection(Request $request, $id)
    {
        //
        $result = CourseAssessmentSection::updateOrCreate(['id' => $request->sectionId], ['course_assessment_id' => $id, 'name' => $request->name, 'description' => $request->description, 'weight' => $request->weight, 'time_allowed' => $request->timeAllowed]);


        return ['message' => 'The section has been added successfully!', 'data' => $result];
    }
    public function updateAssessmentSectionActivationStatus(Request $request, $id)
    {
        //
        $result = CourseAssessmentSection::find($id);
        $result->isActive = $request->isActive;
        $result->save();


        return ['message' => 'The section\'s status has been updated successfully!', 'data' => $result];
    }
    public function saveQuestionOption(Request $request, $id)
    {
        // create policy to verify the max number of options per question a school needs
        $result = CourseAssessmentQuestionOption::updateOrCreate(['id' => $request->optionId, 'course_assessment_question_id' => $id], ['content' => $request->content]);



        return ['message' => 'The option has been saved successfully!', 'data' => $result];
    }
    public function savePartcipantAnswer(Request $request, $id)
    {
        // create policy to verify the max number of options per question a school needs
        $result = CourseAssessmentQuestionAnswer::updateOrCreate(['id' => $request->answerId, 'course_assessment_question_id' => $id], ['content' => $request->content, 'time_taken_to_complete' => $request->timeToComplete, 'participant_id' => $request->participantId, 'assessment_id' => $request->assessmentId, 'section_id' => $request->sectionId]);



        return ['message' => 'Your answer has been saved successfully!', 'data' => $result];
    }
    public function scoreAnswer(Request $request, $id)
    {
        // create policy to verify the max number of options per question a school needs
        $result = CourseAssessmentQuestionAnswer::find($id);
        $result->score = $request->score;
        $result->save();



        return ['message' => 'Answer has been scored successfully!', 'data' => $result];
    }

    public function saveCorrectAnswer(Request $request, $id)
    {
        // create policy to verify the max number of options per question a school needs
        $result = CourseAssessmentQuestionCorrectAnswer::updateOrCreate(['id' => $request->answerId, 'course_assessment_question_id' => $id], ['content' => $request->content]);



        return ['message' => 'The correct answer to question has been saved successfully!', 'data' => $result];
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

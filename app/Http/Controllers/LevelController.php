<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\ClassTeacherRecord;
use App\Http\Resources\LevelResource;
use App\Http\Resources\ClassTeacherRecordResource;
use App\Exports\LevelsExport;
use Maatwebsite\Excel\Facades\Excel;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function assignStaffToHandleClassesForASession(Request $request)
     {

         // $currentSessionId = $school->current_session_id;
         $currentSessionId = $request->sessionId;

         $classStaffIds = $request->classStaffIds;

         $records = [];

         foreach ($classStaffIds as $entry) {
            //    array_push($records, [
            //        'school_session_id' => $currentSessionId,
            //        'level_id'=>$entry['levelId'],
            //        'staff_id'=>$entry['staffId'],
            //        'created_at'=>date('Y-m-d H:i:s'),
            //        'updated_at'=>date('Y-m-d H:i:s'),


            //    ]);
           // code...
           ClassTeacherRecord::updateOrCreate(['school_session_id' => $currentSessionId,'level_id'=>$entry['levelId']], ['staff_id'=>$entry['staffId']]);
         }

        // ClassTeacherRecord::insert($records);



        //  $recordCount = count($records);
         $recordCount = count($classStaffIds);

         // foreach ($staff as $staff) {
         //   $staff->user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/", "You have been assigned to teach $course->name in  $classCount classes.", "You have been assigned $course->name to manage", "Classes: $levelsAsStr")));
         // }


         return response()->json([
             'status' => true,
             'message' => "Staff have been succesfully assigned to $recordCount classes!",
             'data' => null,



         ], 200);
         // return new StaffResource($staff);
     }
    public function classTeacherRecordsPerSession(Request $request, $sessionId)
    {
        // PLease use resoURCES
        $perPage = $request->limit ? $request->limit : 4;

        $results = ClassTeacherRecord::with(['staff.user', 'level'])->where('school_session_id',$sessionId)->paginate($perPage);
       

        return ClassTeacherRecordResource::collection($results);
    }
    public function index(Request $request, $id)
    {
        // PLease use resoURCES
        $perPage = $request->limit ? $request->limit : 4;

        $results = Level::with(['courses','classSessionTeachers' => fn($query) => $query->with(['staff' => fn($q) => $q->with('user')])])->where('school_id',$id)->paginate($perPage);
    
        if($request->searchTerm){

            $results = Level::with(['courses','classSessionTeachers' => fn($query) => $query->with(['staff' => fn($q) => $q->with('user')])])->where('school_id',$id)->whereLike(['name'], $request->searchTerm)->paginate($perPage);
        }

        return LevelResource::collection($results);
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

     public function exportBulkUpload()
     {
         //
         return Excel::download(new LevelsExport, 'levels.xlsx');
     }

     public function addLevelsInBulk(Request $request)
     {
          //validate the request
          if($request->id && $request->schoolId !==  Level::find($request->id)->school_id  ){
             return 'Not allowed';
         }
         // create the departments
         $entries = $request->jsonData;
         $records = [];
         foreach ($entries as $entry) {
            # code...
            array_push($records, [
                'name'=>$entry['name'],
                'description'=>$entry['description'],
                'school_id'=>$request->schoolId,
                'created_at'=>date('Y-m-d H-i-s'),
                'updated_at'=>date('Y-m-d H-i-s'),
                // 'admin_id'=>$request->adminId

            ]);
         }
         // return $records;
         $result = Level::insert($records);
         return response()->json([
            'status' => true,
            'message' => count($records)  .' classes were added successfully!',
            'data' => $result,



        ], 200);
     }

    public function store(Request $request)
    {
        // only perform operation when schoolId is present
        if($request->id && $request->schoolId !==  Level::find($request->id)->school_id  ){
            return 'Not allowed';
        }
        //
        $level = Level::create(['name'=> $request->name, 'description'=> $request->description, 'school_id'=>$request->schoolId]);
        return response()->json([
            'status' => true,
            'message' => "$level->name level created successfully",
            'data' => $level,



        ], 200);
    }
    public function assignToSession(Request $request, $id)
    {
        //
        $level = Level::find($id);
        $level->Levels()->syncWithoutDetaching($request->sessionId);
        // return $level->Levels[0];
        return ['message'=>'This class was successfully assigned to the session'];

        // return new LevelResource($level);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show(Request $request, $id)
    {
        $sessionId = $request->sessionId;
        $level =Level::with(['courses'] )->find($id);
        if($sessionId ){
            $level =Level::with(['courses.courseTeacherRecords' => fn($q) => $q->where('school_session_id', $sessionId)->where('level_id', $id)->with('staff.user')->get()] )->find($id);
        }
        if($level ===  null){
            return response()->json([
                'status' => false,
                'message' => 'This class(level) doesn\'t exist.',
                'data' => null,



            ], 400);

        }
        return response()->json([
            'status' => true,
            'message' => "Level retrieved successfully",
            'data' => $level,



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
       //validate the request
       if($request->id && $request->schoolId !==  Level::find($request->id)->school_id  ){
          return 'Not allowed';
        }
        // create the departments
        // the audit log should make use of the admin id of the person who updated the department
        // currently the admin id is passed but $this can also be from the auth()->user()-> admin in school
        $level = Level::find($id);
        $level->update([ 'name' => $request->name, 'description'=> $request->description]);

        return response()->json([
            'status' => true,
            'message' => "$level->name  was updated successfully",
            'data' => $level,

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

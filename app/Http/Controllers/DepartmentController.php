<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Exports\DepartmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\DepartmentResource;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        // PLease use resoURCES
        $perPage = $request->limit ? $request->limit : 4;

        $results = Department::where('school_id',$id)->paginate($perPage);
        if($request->searchTerm){
            $results = Department::where('school_id',$id)->whereLike(['name'], $request->searchTerm)->paginate($perPage);
        }




        return DepartmentResource::collection($results);
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

     public function addDepartmentsInBulk(Request $request)
     {
          //validate the request
          if($request->id && $request->schoolId !==  Department::find($request->id)->school_id  ){
             return 'Not allowed';
         }
         $adminId = auth()->user()->schools()->firstWhere('school_id',$request->schoolId)->pivot->admin_id;
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
                'admin_id'=>$adminId

            ]);
         }
         // return $records;
         $result = Department::insert($records);
         return response()->json([
            'status' => true,
            'message' => count($records)  .' departments were added successfully!',
            'data' => $result,



        ], 200);
     }



    public function store(Request $request)
    {
         //validate the request
         if($request->id && $request->schoolId !==  Department::find($request->id)->school_id  ){
            return 'Not allowed';
        }
        // create the departments
        $adminId = auth()->user()->schools()->firstWhere('school_id',$request->schoolId)->pivot->admin_id;
        $department = Department::create([ 'name' => $request->name, 'description'=> $request->description, 'school_id'=>$request->schoolId,'admin_id'=>$adminId]);

        return response()->json([
            'status' => true,
            'message' => "$department->name department created successfully",
            'data' => $department,



        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function exportBulkUpload()
     {
         //
         return Excel::download(new DepartmentsExport, 'departments.xlsx');
     }
    public function show($id)
    {
        //
        $department =Department::find($id);
        if($department ===  null){
            return response()->json([
                'status' => false,
                'message' => 'This department doesn\'t exist.',
                'data' => null,



            ], 400);

        }
        return response()->json([
            'status' => true,
            'message' => "Department retrieved successfully",
            'data' => $department,



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
      if($request->id && $request->schoolId !==  Department::find($request->id)->school_id  ){
         return 'Not allowed';
       }
       // create the departments
       // the audit log should make use of the admin id of the person who updated the department
       // currently the admin id is passed but $this can also be from the auth()->user()-> admin in school
       $department = Department::find($id);
       $department->update([ 'name' => $request->name, 'description'=> $request->description]);

       return response()->json([
           'status' => true,
           'message' => "$department->name deparment was updated successfully",
           'data' => $department,

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

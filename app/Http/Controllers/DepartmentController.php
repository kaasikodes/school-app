<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;


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
         //validate the request
         if($request->id && $request->schoolId !==  Department::find($request->id)->school_id  ){
            return 'Not allowed';
        }
        // create the departments

        $department = Department::updateOrCreate(['id'=> $request->id],[ 'name' => $request->name, 'description'=> $request->description, 'school_id'=>$request->schoolId]);

        return $department;
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
            'message' => 'This department retrieved successfully',
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolSession;


class SchoolSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $results = SchoolSession::where('school_id',$id)->paginate();
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
    public function endSession(Request $request, $id){
        $session =SchoolSession::find($id);
        if($session ===  null){
            return response()->json([
                'status' => false,
                'message' => 'School session doesn\'t exist.',
                'data' => null,
               
              
    
            ], 400);

        }
        $session->ends = $request->ends;
        $session->save();
        return response()->json([
            'status' => true,
            'message' => 'School has ended successfully',
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

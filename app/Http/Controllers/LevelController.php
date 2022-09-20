<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Http\Resources\LevelResource;

class LevelController extends Controller
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

        $results = Level::where('school_id',$id)->paginate($perPage);
        if($request->searchTerm){
            $results = Level::where('school_id',$id)->whereLike(['name','ends','starts',], $request->searchTerm)->paginate($perPage);
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
        // only perform operation when schoolId is present
        if($request->id && $request->schoolId !==  Level::find($request->id)->school_id  ){
            return 'Not allowed';
        }
        //
        $level = Level::updateOrCreate(['id' => $request->id],['name'=> $request->name, 'description'=> $request->description, 'school_id'=>$request->schoolId]);
        // return $level;
        // return 'works';
        return new LevelResource($level);
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
    public function show($id)
    {
        //
        $level =Level::find($id);
        if($level ===  null){
            return response()->json([
                'status' => false,
                'message' => 'This class(level) doesn\'t exist.',
                'data' => null,
               
              
    
            ], 400);

        }
        return response()->json([
            'status' => true,
            'message' => 'This class(level) retrieved successfully',
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

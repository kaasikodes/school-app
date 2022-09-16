<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
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
        $results = School::paginate($perPage);
        return $results;
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
        // create the session
        $result = School::updateOrCreate(['id'=> $request->id],[ 'name' => $request->name, 'description'=> $request->description, 'studentLimit'=> $request->studentLimit, 'staffLimit'=> $request->staffLimit]);

        return $result;
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

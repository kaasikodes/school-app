<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChoosenSchoolId(Request $request, $id)
    {
        //
        $user = User::find($id);
        $userIsNotPartOfSchool = !$user->schools->where('id',$request->schoolId)->first();
        if ($userIsNotPartOfSchool) {
            return response()->json([
                'status' => false,
                'message' => 'The user is not a part of the selected school, hence the school cannot be the user\'s default school.',
                'data' => $user,
                'schools' => $user->schools,
              
    
            ], 405);
        }
        $user->choosen_school_id = $request->schoolId;
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'The choosen school has been updated successfully',
            'data' => $user,
            'schools' => $user->schools,
          

        ], 200);
 
    }
    public function index()
    {
        //
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
        $user =  User::find($id);

        return response()->json([
            'status' => true,
            'message' => 'The user has been retrieved successfully!',
            'user' => $user,
            'schools' => $user->schools,
          

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

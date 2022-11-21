<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\School;
use App\Models\User;
use App\Http\Resources\StaffResource;
use Illuminate\Support\Facades\Hash;
use App\Traits\BaseUserTrait;



class StaffController extends Controller
{
    use BaseUserTrait;
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $perPage = $request->limit ? $request->limit : 15;


        // return $results;
        $results = Staff::where('school_id',$id)->paginate($perPage);
        if($request->searchTerm){
            $results = Staff::where('school_id',$id)->whereLike(['user.name'], $request->searchTerm)->with(['user'])->paginate($perPage);
        }
        return $results;

        // return StaffResource::collection($results);

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
        // create a base user account
        $user = User::where('email',$request->email)->first();
        if(!$user){
            // return 'reached';
            // create user account
            $user = $this->createUser($request->name,
                $request->email,
                $request->password
            );


        }
        // attach the user acc to a school
        $this->addUserToSchool($user->id, $request->schoolId);
       
        // create a staff profile for user in the school

        // conditions
        // first check if the user with the account exists
        // if the user exists create the user account but notify the user
        // the user has the option to exit himself
        // if the user doesnt exist attach user to school set no priveledges
        // also create a staff profile for user
        $staff = Staff::updateOrCreate(['user_id'=> $user->id],[ 'staff_no'=> $request->staffNo, 'isActive' => $request->isActive ? $request->isActive : true, 'school_id' => $request->schoolId]);
        // return $Staff;
        // return 'works';
        return new StaffResource($staff);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custodian;
use App\Models\User;
use App\Models\Student;

use App\Traits\BaseUserTrait;

use App\Http\Resources\CustodianResource;
use App\Notifications\NotifyUser;


class CustodianController extends Controller
{
  use BaseUserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        // return $results;
        $results = Custodian::with(['user'])->where('school_id',$id)->paginate($perPage);
        if($request->searchTerm){
            $results = Custodian::where('school_id',$id)->whereLike(['user.name', 'staff_no'], $request->searchTerm)->with(['user'])->paginate($perPage);
        }
        // return $results;
        return CustodianResource::collection($results);
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
        $user = User::where('email', $request->email)->first();
        if(!$user){
          $user = $this->createUser($request->name,
              $request->email,
              $this->defaultPassword
          );
        }

        // attach the user acc to a school
        $this->addUserToSchool($user->id, $request->schoolId, 'custodian',['custodian']);
        $custodian = Custodian::create([ 'user_id'=> $user->id, 'occupation'=> $request->occupation, 'alt_phone'=>$request->phone, 'alt_email'=>$request->email, 'school_id' => $request->schoolId, 'isActive' => $request->isActive ? $request->isActive : 1,]);

        $custodian->students()->syncWithoutDetaching($request->studentId);

        // inform the custodian via email that account has been created alonside the password to login
        $student = Student::find($request->studentId);
        $studentName = $student->user->name;

        $user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/login", "Congratulations! You have been assigned as a cutodian to $studentName", "Custodian Profile Created", "Password: $this->defaultPassword")));

        return new CustodianResource($custodian);
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

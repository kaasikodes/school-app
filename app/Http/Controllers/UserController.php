<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Custodian;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Admin;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function export()
     {
         return Excel::download(new UsersExport, 'users.xlsx');
     }
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
    public function updateUserChoosenRoleInSchool(Request $request, $id)
    {
        //
        $user = User::find($id);
        $school= School::find($request->schoolId);

        // update the role here
        // $user->schools()->syncWithoutDetaching($request->schoolId, ['choosen_role' => $request->role]);


        $userRolesInSchool = $school->users()->wherePivot('user_id', $user->id)->first()->pivot->school_user_roles;
        $school->users()->detach($user->id);
        $school->users()->attach($user->id, ['choosen_role' => $request->role, 'school_user_roles'=>$userRolesInSchool]);
        // return concerned ids
        $admin = Admin::where('school_id', $request->schoolId)->where('user_id',$user->id)->where('isActive', 1)->first();
        $custodian = Custodian::where('school_id', $request->schoolId)->where('user_id',$user->id)->where('isActive', 1)->first();
        $staff = Staff::where('school_id', $request->schoolId)->where('user_id',$user->id)->where('isActive', 1)->first();
        $student = Student::where('school_id', $request->schoolId)->where('user_id',$user->id)->where('isActive', 1)->first();
        // return $admin->id;
        $adminId = $admin ? $admin->id : null;
        $custodianId = $custodian  ? $custodian->id : null;
        $staffId = $staff ? $staff->id : null;
        $studentId = $student ? $student->id : null;

        return response()->json([
            'status' => true,
            'message' => 'The Role for choosen school has been updated successfully',
            'data' => ['user'=>$user, 'adminId' => $adminId, 'custodianId'=>$custodianId, 'staffId'=> $staffId, 'studentId'=> $studentId],
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
    public function getUserByEmail(Request $request)
    {
        //TO DO use a token middleware guard to guard this controller
        // e.g check wether this token exists in the requests sent
        // tokens can be stored in DB with no expiry date or hardcoded

        $user =  User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'This user does not exist in application!',
                'user' => null,
                'schools' => null,
    
    
            ], 404);

        }

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

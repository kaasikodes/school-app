<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Custodian;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Admin;
use App\Models\User;
use App\Http\Resources\StaffResource;
use Illuminate\Support\Facades\Hash;

trait BaseUserTrait
{
    // TO DO -> should be randomly generated
    public $defaultPassword = 'Inokpa123$';

    private function getRandomStringBin2hex($length = 16)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length / 2);
        } else {
            $bytes = openssl_random_pseudo_bytes($length / 2);
        }
        $randomString = bin2hex($bytes);
        return $randomString;
    }

    public function generateRandomPassword()
    {
        
        return $this->getRandomStringBin2hex(8);
    }

    public function addUserToSchool($userId,$schoolId,$choosenRole, $schoolUserRoles)
    {
      // TO DO
      // Add consitons to ensure the table is doing what is suppose todo
      // then also need to test it
      // and also do this => addRoleToUserInSchool


        $school = School::find($schoolId);
        $school->users()->syncWithoutDetaching($userId);
        // make school default school if user has no default
        $user = User::find($userId);
        if(!$user->choosen_school_id){
            $user->choosen_school_id = $schoolId;
            $user->save();
        }
        $choosenRole = $choosenRole ? $choosenRole : 'none';
        if(in_array('custodian',$schoolUserRoles)){
            $custodian = Custodian::create(['user_id' => $userId, 'school_id'=>$schoolId]);

        }
        if(in_array('staff',$schoolUserRoles)){
            $staff = Staff::create(['user_id' => $userId, 'school_id'=>$schoolId, 'staff_no'=> $this->getRandomStringBin2hex(6)]);

        }
        if(in_array('student',$schoolUserRoles)){
            $student = Student::create(['user_id' => $userId, 'school_id'=>$schoolId, 'current_level_id'=> 0, 'latest_session_id'=> $school->current_session_id ?? 0]); //student might not be assigned a class at moment, or school might not have a session

        }
        if(in_array('admin',$schoolUserRoles)){
            $admin =  Admin::create(['user_id' => $userId, 'school_id'=>$schoolId]);

        }
        $schoolUserRoles = $schoolUserRoles ? json_encode($schoolUserRoles) : json_encode(['none']);

        $user->schools()->updateExistingPivot($school->id, ['choosen_role'=> $choosenRole,'school_user_roles'=>$schoolUserRoles, 'admin_id' => isset($admin) ? $admin->id : null, 'student_id' => isset($student) ? $student->id : null, 'staff_id' => isset($staff) ? $staff->id : null, 'custodian_id' => isset($custodian) ? $custodian->id : null]);

        $updatedSchool = $user->schools($school->id);
        
        return $user;

    }
    public function addRoleToUserInSchool($userId,$schoolId,$role)
    {
        $user = User::find($userId);
        $school = School::find($schoolId);

        $userRoleId = null;
        if($role === 'custodian' && !$user->custodian_id){
            $userRole = Custodian::create(['user_id' => $userId, 'school_id'=>$schoolId]);

        }
        if($role === 'staff' && !$user->staff_id){
            $userRole = Staff::create(['user_id' => $userId, 'school_id'=>$schoolId, 'staff_no'=> $this->getRandomStringBin2hex(4)]);

        }
        if($role === 'student' && !$user->student_id){
            $userRole = Student::create(['user_id' => $userId, 'school_id'=>$schoolId, 'current_level_id'=> 0, 'latest_session_id'=> $school->current_session_id ?? 0]); //student might not be assigned a class at moment, or school might not have a session

        }
        if($role === 'admin' && !$user->admin_id){
            $userRole = Admin::create(['user_id' => $userId, 'school_id'=>$schoolId]);

        }

        $userRoleId = $userRole->id;

        $currentRolesAsJSON = $user->schools()->where('school_id', $schoolId)->first()->pivot->school_user_roles;
        $currentRoles = json_decode($currentRolesAsJSON);
        $updatedRoles = $currentRoles;
        array_push($updatedRoles, $role);
        $uniqueRoles = array_unique($updatedRoles);
        // return ['UR'=> $uniqueRoles, 'json' => $currentRolesAsJSON];
        


        
        if($role === 'custodian' && !$user->custodian_id){
            $user->schools()->updateExistingPivot($schoolId,  ['school_user_roles'=> json_encode($uniqueRoles), 'custodian_id'=> $userRoleId]);

        }
        if($role === 'staff' && !$user->staff_id){
            $user->schools()->updateExistingPivot($schoolId,  ['school_user_roles'=> json_encode($uniqueRoles), 'staff_id'=> $userRoleId]);

        }
        if($role === 'student' && !$user->student_id){
            $user->schools()->updateExistingPivot($schoolId, ['school_user_roles'=> json_encode($uniqueRoles), 'student_id'=> $userRoleId,]);

        }
        if($role === 'admin' && !$user->admin_id){
            $user->schools()->updateExistingPivot($schoolId,['school_user_roles'=> json_encode($uniqueRoles), 'admin_id'=> $userRoleId]);

        }
       
        return $user;

    }
    public function createUser($name, $email, $password){
        // return ['info' => $info, 'message' => 'works'];
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
        return $user;

    }
}

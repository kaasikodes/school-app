<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Http\Resources\StaffResource;
use Illuminate\Support\Facades\Hash;

trait BaseUserTrait
{
    public $defaultPassword = 'Inokpa123$';

    public function addUserToSchool($userId,$schoolId)
    {
        
        $school = School::find($schoolId);
        $school->users()->syncWithoutDetaching($userId);
        // make school default school if user has no default
        $user = User::find($userId);
        if(!$user->choosen_school_id){
            $user->choosen_school_id = $schoolId;
            $user->save();
        }
        $user->schools()->updateExistingPivot($school->id, ['choosen_role'=> 'none','school_user_roles'=> json_encode(['none'])]);
        return $user;

    }
    public function addRoleToUserInSchool($userId,$schoolId,$roles)
    {
        
        $user = User::find($userId);
     
        $user->schools()->updateExistingPivot($schoolId, ['school_user_roles'=> json_encode($roles)]);
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
  
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\BaseUserTrait;
use App\Notifications\NotifyUser;



class AuthController extends Controller
{
    use BaseUserTrait;
    
    public function createAccountFromInvitation(Request $request, $schoolId)
    {
        try {

            //Validated
            $validateInvitation = Validator::make($request->all(),
            [
                'userType' => 'required',
                'email' => 'required',
                'inviteCode' => 'required',
                'userName' => 'required'
            ]);

            if($validateInvitation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $school = School::find($schoolId);
            if(!$school){
                return response()->json([
                    'status' => false,
                    'message' => 'School does not exist!',
                ], 404);
            }
            // check if invitation exists or if code has expired | is accepted already
            $sessionId = $request->sessionId ? $school->current_session_id : null;
            if(!$sessionId ){
                return response()->json([
                    'status' => false,
                    'message' => 'School Session is not recognized!',
                ], 404);
            }
            $invite = Invitation::where('email', $request->email)->where('user_type', $request->userType)->where('school_id', $schoolId)->where('session_id', $sessionId)->first();
            if(!$invite ){
                return response()->json([
                    'status' => false,
                    'message' => 'This invitation is invalid!',
                ], 404);
            }
            if($invite->accepted_at){
                return response()->json([
                    'status' => false,
                    'message' => 'This invitation has already been accepted, try logging in instead!',
                ], 400);
            }

            
        

            // in both cases send notification to inform user that they have succesully been added to school
            $user = User::where('email', $request->email)->first();

            //  check if user exists and if user exists append to user
            $newUserPassword;
            if(!$user){
                $newUserPassword = $this->generateRandomPassword();
                $user = User::create([
                    'name' => $request->userName ?? '',
                    'email' => $request->email,
                    'password' => Hash::make($newUserPassword)
                ]);


            }
          
            $userAlreadyExistsInSchool = $user->schools()->where('school_id', $schoolId)->first();
            if($userAlreadyExistsInSchool){
                // return 'works';
                $this->addRoleToUserInSchool($user->id, $schoolId, $request->userType);

            }else{
                
                $user = $this->addUserToSchool($user->id, $schoolId, $request->userType,[$request->userType]);
            }
            

            // else create a new account for person


            // if invite passes validation then set invite to accepted when, user acc has the school appended to it
            $now = date_create()->format('Y-m-d H:i:s');
            $invite->accepted_at = $now;
            $invite->accepted = 1;
            $invite->save();


            // notifications
            // 1.) New user to system, needs login credentials and to be informed of his role in school
            if(isset($newUserPassword)){
                $user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/", "Congrats! You are now a $request->userType in $school->name.", "You login credentials are provided below!", "Password: $newUserPassword")));
            }else{
                $user->notify((new NotifyUser(env('APP_FRONTEND_URL')."/", "Congrats! You are now a $request->userType in $school->name.", "You can proceed to perform your duties as a $request->userType.", "")));
            }
            // 2.) Existing user needs to be notified of his new role in school



            return response()->json([
                'status' => true,
                'user' => $user,
                'schools' => $user->schools,


                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    // create account
    //this method adds new users
    public function createAccount(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'user' => $user,
                'schools' => $user->schools,


                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $user,
                'schools' => $user->schools,

                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'abilities' => $user->createToken("API TOKEN")->accessToken->abilities

            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function logout($id)
    {
        // auth()->user()->currentAccessToken()->delete();
        $user = User::find($id);
        $user->tokens()->where('id', $id )->delete();



        return [
            'message' => 'Tokens Revoked',
            // 'user'=> $user
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\InvitesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Invitation;
use App\Models\School;
use App\Http\Resources\InviteResource;
use App\Notifications\NotifyUser;




class InvitesController extends Controller
{
    public function index(Request $request, $schoolId)
    {
        // PLease use resoURCES
        $perPage = $request->limit ? $request->limit : 4;

        $results = Invitation::where('school_id',$schoolId)->where('session_id',$request->sessionId)->where('user_type',$request->userType)->paginate($perPage);
        if($request->searchTerm){
            $results = Invitation::where('school_id',$id)->whereLike(['email'], $request->searchTerm)->paginate($perPage);
        }




        return InviteResource::collection($results);
    }
    

    public function store(Request $request)
    {
        $school = School::find($request->schoolId);
        if(!$school){
            return response()->json([
                'status' => false,
                'message' => "School doesn't exist!",
                'data' =>null,
    
    
    
            ], 400);

        }
        $code = $school->name  . $this->getRandomStringBin2hex(6); // TO D0 : Should be randomly generated
        // create the invitation
        $invitation = Invitation::updateOrCreate(['email'=> $request->email, 'school_id'=>$request->schoolId, 'user_type'=> $request->userType],[ 'code' => $code, 'email'=> $request->email, 'school_id'=>$request->schoolId,'session_id'=>$request->sessionId, 'user_type'=> $request->userType]);

        $redirectUrl = env('APP_FRONTEND_URL')."/register?schoolId=$request->schoolId&code=$code&sessionId=$request->sessionId&type=$request->userType&email=$request->email";
        // TO DO: If user exists, and has an active session use a different url to avoid auth redirect

        // notify person
        $invitation->notify((new NotifyUser($redirectUrl, "Congratulations! You have been invited to be a $request->userType at $school->name", "$school->name invitation", "Invitation Code: $code", $schoolId)));

        return response()->json([
            'status' => true,
            'message' => "Invitation created successfully",
            'data' => $invitation,



        ], 200);
    }

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



    public function exportBulkUpload()
    {
         //
         return Excel::download(new InvitesExport, 'invitations.xlsx');
    }
    
}

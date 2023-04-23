<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Requisition;
use App\Models\Approval;
use App\Models\School;
use App\Models\Staff;
use App\Http\Resources\RequisitionResource;
use App\Notifications\NotifyUser;




class RequisitionController extends Controller
{
    public function index(Request $request, $schoolId)
    {
        // PLease use resoURCES
        $perPage = $request->limit ? $request->limit : 4;

        $results = Requisition::with(['requester','currentApprover'])->where(['school_id'=>$schoolId,'session_id'=>$request->sessionId, 'requester_id' => auth()->user()->id]);
        if($request->status){
            $results = $results->where(['status' => $request->status]);
        }
        if($request->type){
            $results = $results->where(['type' => $request->type]);
        }
        if($request->searchTerm){
            $results =  Requisition::where(['school_id'=>$schoolId,'session_id'=>$request->sessionId, 'requester_id' => auth()->user()->id])->whereLike(['title'], $request->searchTerm);
        }
        $results = $results->paginate($perPage);




        return RequisitionResource::collection($results);
    }
    

    public function show(Request $request, $id)
    {
       
        $data = Requisition::with(['requester','currentApprover'])->find($id);

        return response()->json([
            'status' => true,
            'message' => "Requisition retrieved successfully",
            'data' => $data,



        ], 200);
    }
    public function store(Request $request)
    {
        $staff = Staff::find($request->staffId);
        if(!$staff){
            return response()->json([
                'status' => false,
                'message' => "The Approver selected is not allowed to perform action!",
                'data' => null,
    
            ], 400);
        }
        $approver = $staff->user;
        $approver_id = $approver->id;

        $requisition = Requisition::create(['title'=>$request->title,'course_id'=>$request->courseId, 'level_id'=>$request->levelId, 'type'=>$request->type,'content'=>$request->content, 'session_id'=>$request->sessionId, 'school_id'=>$request->schoolId, 'requester_id'=>auth()->user()->id, 'requested_as'=>'course_teacher', 'current_approver_id'=>$approver_id]);
        // notify the approver of what needs to be done
        $approval = Approval::create(['requisition_id'=>$requisition->id,'approver_id'=>$approver_id]);
        $requestor = auth()->user();
        $approver->notify((new NotifyUser(env('APP_FRONTEND_URL')."/approvals?id=$approval->id", "$request->content", "Approval for $request->title", "Requestor: $requestor->name")));

        return response()->json([
            'status' => true,
            'message' => "Requisition created successfully",
            'data' => $requisition,



        ], 200);
    }



    
    
}

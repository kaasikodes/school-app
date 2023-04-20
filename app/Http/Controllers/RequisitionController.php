<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Requisition;
use App\Models\School;
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



    
    
}

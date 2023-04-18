<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Approval;
use App\Models\School;
use App\Http\Resources\RequisitionResource;
use App\Notifications\NotifyUser;




class ApprovalController extends Controller
{
    public function index(Request $request, $schoolId)
    {
        // PLease use resoURCES
        $perPage = $request->limit ? $request->limit : 4;

        $results = Approval::with(['requisition' => function($q) {
            $q->with('requester');
        } ,'approver'])->whereHas('requisition', function ($query)  use($request) {
            return $query->where('session_id', '=', $request->sessionId);
        })->where([ 'approver_id' => auth()->user()->id]);
        if($request->status){
            $results = $results->where(['status' => $request->status]);
        }
        if($request->type){
            $results = $results->whereHas('requisition', function ($query)  use($request) {
                return $query->where('type', '=', $request->type);
            });
        }
        if($request->searchTerm){
            $results =  Approval::with(['requisition' => function($q) {
                $q->with('requester');
            } ,'approver'])->whereHas('requisition', function ($query)  use($request) {
                return $query->where('session_id', '=', $request->sessionId);
            })->where([ 'approver_id' => auth()->user()->id])->whereLike(['title'], $request->searchTerm);
        }
        $results = $results->paginate($perPage);




        return RequisitionResource::collection($results);
    }
    

    public function show(Request $request, $id)
    {
       
        $data = Approval::with(['requisition' => function($q) {
            $q->with('requester');
        } ,'approver'])->find($id);

        return response()->json([
            'status' => true,
            'message' => "Requisition retrieved successfully",
            'data' => $data,



        ], 200);
    }
    public function approveOrReject(Request $request, $id)
    {
       
        $data = Approval::find($id);
        $data->update(['status' => $request->status]);
        $data->requisition()->update(['status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => "Requisition has been $request->status successfully!",
            'data' => $data,



        ], 200);
    }



    
    
}

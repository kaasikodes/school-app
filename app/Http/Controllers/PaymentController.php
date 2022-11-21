<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentCategory;
use App\Models\SchoolLevelFee;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentCategories($schoolId)
    {
        //
        $result = PaymentCategory::where('school_id', $schoolId)->get();
        return response()->json([
            'status' => true,
            'message' => 'Payment categries retrieved!',
            'data' => $result,

        ], 200);
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
    public function createPaymentCategory(Request $request)
    {
        //
        $result = PaymentCategory::create(['school_id'=>$request->schoolId,'name'=>$request->name, 'description'=>$request->description]);
        return response()->json([
            'status' => true,
            'message' => 'Payment Category saved succesfully!',
            'data' => $result,

        ], 200);
    }
    public function createLevelFee(Request $request)
    {
        // please validate request n throw appropriate errs
        $result = SchoolLevelFee::create(['school_id'=>$request->schoolId,'level_id'=>$request->levelId, 'school_session_id'=>$request->sessionId,'fee_category_id'=>$request->categoryId, 'breakdown_document_url' => $request->docUrl, 'amount'=>$request->amount, 'can_be_installmental' => $request->inPart, 'currency_id' => $request->currencyId]);
        return response()->json([
            'status' => true,
            'message' => 'Class Fee saved succesfully!',
            'data' => $result,

        ], 200);
    }
    public function updatePaymentCategory(Request $request, $id)
    {
        //
        $record = PaymentCategory::find($id);
        $record->update(['name'=>$request->name, 'description'=>$request->description]);
        return $record;
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

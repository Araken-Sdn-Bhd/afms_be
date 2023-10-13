<?php

namespace App\Http\Controllers;
use App\Models\Tenders;

use Illuminate\Http\Request;

class TendersController extends Controller
{
    public function createNewTender(Request $request) {
        $validator = Validator::make($request->all(), [
            'tender_id' => 'required|int',
            'client_id' => 'required|int',
            'user_id' => 'required|int',
            'title' => 'required|string',
            'submission_date' => 'required|string',
            'submission_price' => 'required|string',
            'reference_no' => 'required|string',
            'remark' => 'required|string',
            'tender_requirement' => 'required|string',
            'technical_doc_loc' => 'required|string',
            'financial_doc_loc' => 'required|string',
            'other_doc_loc' => 'required|string',
            'timestamp' => '',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }

        $tender = [
            'client_id'=>$request->client,
            'title'=>$request->title,
            'submission_date'=>$request->sub_date,
            'submission_price'=>$request->sub_price,
            'reference_no'=>$request->reference_no,
            'remark'=>$request->remarks,

        ];
        Tenders::create($tender);
    }
}

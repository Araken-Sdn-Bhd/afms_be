<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tenders;
use Validator;

class TendersController extends Controller
{
    public function createNewTender(Request $request) {

        $validator = Validator::make($request->all(), [
            'client_id' => 'required|int',
            'user_id' => 'required|int',
            'title' => 'required|string',
            'submission_date' => 'required',
            'submission_price' => 'required',
            'reference_no' => 'required',
            'remark' => 'required',
            // 'tender_requirement' => 'required|string',
            // 'technical_doc_loc' => 'required|string',
            // 'financial_doc_loc' => 'required|string',
            // 'other_doc_loc' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }

        $tender = [
            'client_id'=>$request->client_id,
            'user_id' =>$request-> user_id,
            'title'=>$request->title,
            'submission_date'=>$request->submission_date,
            'submission_price'=>$request->submission_price,
            'reference_no'=>$request->reference_no,
            'remark'=>$request->remark,

        ];
        // dd($tender);
        Tenders::create($tender);
        return response()->json(["message" => "Record Successfully Saved", "code" => 200]);
    }

    public function tenderList(Request $request){
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        if($date_from && $date_to){
            $fileList = Tenders::select('*')
            ->whereDate('submission_date', '>=', $date_from)
            ->whereDate('submission_date', '<=', $date_to)
            ->orderBy('title','asc')
            ->get();
        }
        else if($date_from && !$date_to){
            $fileList = Tenders::select('*')
            ->whereDate('submission_date', '>=', $date_from)
            ->orderBy('title','asc')
            ->get();
        }
        else if(!$date_from && $date_to){
            $fileList = Tenders::select('*')
            ->whereDate('submission_date', '<=', $date_to)
            ->orderBy('title','asc')
            ->get();
        }
        else{
            $fileList = Tenders::select('*')
            ->orderBy('title','asc')
            ->get();
        }
       return response()->json(["message" => "Tender List", 'list' => $fileList, "code" => 200]);
   }

   public function getTender(Request $request) {

    $tender = Tenders::select('tenders.*', 'clients.client_name')
    ->leftjoin('clients', 'clients.client_id', '=', 'tenders.client_id')
    ->where('tenders.tender_id', '=', $request->tender_id)
    ->get();

    return response()->json(["message" => "Tender Info", 'tender' => $tender, "code" => 200]);
   }
}

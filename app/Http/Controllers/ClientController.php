<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use Validator;

class ClientController extends Controller
{
    public function clientList()
    {
        $clientList = Clients::select('*')
        ->orderBy('client_name','asc')
        ->get();
        return response()->json(["message" => "Client List", 'list' => $clientList, "code" => 200]);
    }

    public function deleteClient(Request $request){
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }

        $clientList = Clients::where(
            ['client_id' => $request->client_id]
        );
        $clientList->delete();
        return response()->json(["message" => "Deleted Successfully.", "code" => 200]);
   }

    public function clientStore(Request $request){
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string',
            'client_status' => 'required|int',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }
        $dataClient = [
            'client_name' => $request->client_name,
            'client_status' => $request->client_status,
        ];

        if($request->editId =='' || $request->editId == 0){
          $res = Clients::create($dataClient);
        }else{
            $res = Clients::where('client_id',$request->editId)->update($dataClient);
        }

        if ($res) {
            return response()->json(["message" => "Record Successfully Saved", "code" => 200]);
        }
    }
    
}

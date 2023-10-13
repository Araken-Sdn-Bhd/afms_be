<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\GeneralSetting;

class GeneralSettingController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'added_by' => 'required|integer',
            'type' => 'required|string',
            'parameter' => 'required|string',
            'value' => 'required|string',
            'index' => 'required|integer',
            'description' =>'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }

        $dataSetting = [
            'added_by'=>$request->added_by,
            'type' => $request->type,
            'parameter' => $request->parameter,
            'value' => $request->value,
            'code' => $request->code,
            'index' => $request->index,
            'description' => $request->description,
        ];

        if ($request->editId =='' || $request->editId == 0) 
        {
            $res = GeneralSetting::create($dataSetting);
        } else
        {
            $res = GeneralSetting::where('id', $request->editId)->update($dataSetting);
        }

        if($res){
            return response()->json(["message" => "Setting has updated successfully", "code" => 200]);
        }
    }

    public function getList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }
        $list = GeneralSetting::select('id', 'section', 'section_value', 'section_order','code', 'status')
        ->where('section', $request->section)->where('status', '1')->orderBy('section_order', 'asc')->get();
        return response()->json(["message" => $request->section . " List", 'list' => $list, "code" => 200]);
    }

    public function getListSetting()
    {
        $settingList = GeneralSetting::select('*')
        ->orderBy('type','asc')
        ->orderBy('index','asc')
        ->orderBy('parameter','asc')
        ->get();
        return response()->json(["message" => "Setting List", 'list' => $settingList, "code" => 200]);
    }

    public function typeList()
    {
        $typeList = GeneralSetting::select('type')
        ->groupBy('type')
        ->orderBy('index','asc')
        ->orderBy('parameter','asc')
        ->get();
        return response()->json(["message" => "Type List", 'list' => $typeList, "code" => 200]);
    }

    public function typeSearchList(Request $request)
    {
        $settingList = GeneralSetting::select('*')
        ->where('type',$request->type)
        ->orderBy('index','asc')
        ->orderBy('parameter','asc')
        ->get();
        return response()->json(["message" => "Setting List", 'list' => $settingList, "code" => 200]);
    }

    public function deleteSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }
        $settingList = GeneralSetting::where('id', $request->id);
        $settingList->delete();
        return response()->json(["message" => "Deleted Successfully.", "code" => 200]);
    }

}

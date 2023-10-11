<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Files;
use Validator;

class FilesController extends Controller
{
    public function fileList(Request $request){
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        if($date_from && $date_to){
            $fileList = Files::select('*')
            ->where('user_id', $request->user_id)
            ->whereDate('created_at', '>=', $date_from)
            ->whereDate('created_at', '<=', $date_to)
            ->orderBy('file_name','asc')
            ->get();
        }
        else if($date_from && !$date_to){
            $fileList = Files::select('*')
            ->where('user_id', $request->user_id)
            ->whereDate('created_at', '>=', $date_from)
            ->orderBy('file_name','asc')
            ->get();
        }
        else if(!$date_from && $date_to){
            $fileList = Files::select('*')
            ->where('user_id', $request->user_id)
            ->whereDate('created_at', '<=', $date_to)
            ->orderBy('file_name','asc')
            ->get();
        }
        else{
            $fileList = Files::select('*')
            ->where('user_id', $request->user_id)
            ->orderBy('file_name','asc')
            ->get();
        }
       return response()->json(["message" => "File List", 'list' => $fileList, 'envURL' => env('APP_URL'), "code" => 200]);
   }

    public function fileStore(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'file' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }
        $files = $request->file('file');
        $file_name = $files->getClientOriginalName();
        $isUploaded = upload_file($files, 'backup/' .$request->email); //file, foldername
        $file_path = $isUploaded->getData()->path;
        $dataStore = [
            'user_id' => $request->user_id,
            'file_name' => $file_name,
            'file_path' => $file_path,
        ];

        $dS = Files::create($dataStore);

        return response()->json(["message" => "Record Successfully Saved", "code" => 200]);
    }

    public function deleteFile(Request $request){
        $validator = Validator::make($request->all(), [
            'backup_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "code" => 422]);
        }

        $unlink_path = trim($request->file_path, "/"); //trim the first slash to act as absolute path
        if($unlink_path){
            unlink($unlink_path);
        }
        $fileList = Files::where(
            ['backup_id' => $request->backup_id]
        );
        $fileList->delete();
        return response()->json(["message" => "Deleted Successfully.", "code" => 200]);
   }
}

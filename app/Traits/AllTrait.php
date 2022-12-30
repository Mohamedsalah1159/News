<?php

namespace App\Traits;

trait AllTrait
{
    public function saveFile($src, $file){
        // save  in folder
        $file_extentions = $src->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extentions;
        $path = $file;
        $src->move($path, $file_name);
        return $file_name;
    }

    public function returnError($status=500, $msg='',$key=null ,$value=null){
        return response()->json([
            'status' => $status,
            'message' => $msg,
            $key => $value

        ], $status);
    }
    public function returnSuccess($status=200, $msg='',$value= null){
        return response()->json([
            'status' => $status,
            'message' => $msg,
            'data' => $value
        ], $status);
    }
    public function returnData($status=200, $msg='' ,$value=null){
        return response()->json([
            'status' => $status,
            'message' => $msg,
            'data' => $value
        ], $status);
    }
    protected function createNewToken($token, $status=201, $msg=''){
        return response()->json([
            'status' => $status,
            'msg' => $msg,
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], $status);
    }

}

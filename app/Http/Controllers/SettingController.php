<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\AllTrait;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use AllTrait;
    public function getSetting(){
        $setting = Setting::first();
        if(! $setting){
            return $this->returnError(200, 'sorry this is not exists');
        }
        return $this->returnData(200, 'this is Setting', $setting);

    }
    public function update(Request $request){
        try{
            //find setting
            $setting = Setting::first();
            if(! $setting){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'title' => 'string|max:191',
                'desc' => 'string'
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $setting->update([
                'title' => $request->title,
                'desc' => $request->desc,
            ]);

            return $this->returnSuccess(200, 'this setting is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
}

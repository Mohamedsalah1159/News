<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VideoContoller extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'name.*' => 'mimes:mp4,mov,ogg'
            ]);
     
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            // save photo in folder
                        // insert into db 
            if($request->hasFile('name')){
                foreach($request->name as $src){
                    
                    $file_extentions = $src->getClientOriginalExtension();
                    $file_name = uniqid('', true) . '.' . $file_extentions;
                    $src->move("video", $file_name);
                    Video::create([
                        'name' => $file_name
                        
                    ]);
                }
                return $this->returnSuccess(200, 'video is added succssfuly' );
            }else{
                $validator = Validator::make($request->all(), [
                    'name' => 'required|url'
                ]);
                if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
                }  
                Video::create([
                   
                    'name' => $request->name
                ]);
                $lastVideo = Video::latest('id')->first();
                return $this->returnSuccess(200, 'link video is added succssfuly', $lastVideo );
                
            }
            return $this->returnError('422', 'there is an error in uploading');




        } catch (\Exception $ex) {
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllVideos(){
        try{
            $video = Video::select('*')->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all videos', $video);
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
        
    }
    public function destroy($id){
        try{
            $video = Video::find($id);
            if($video){
                //delete from database
                $video->delete();
                return $this->returnSuccess(200, 'This video successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}

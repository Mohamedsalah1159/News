<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Newsimage;
use App\Models\News;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsimagesController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'name.*' => 'image|mimes:jpeg,png,jpg,gif,svg'
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
                    $src->move("newsimages", $file_name);
                    Newsimage::create([
                        'name' =>  $file_name,
                        
                    ]);
                    //get count of data in input name
                    $lastImgAdded=count($request->name);
                    $newsimg = Newsimage::select("id")->latest('id')->take($lastImgAdded)->get();
                }
                return $this->returnSuccess(200, 'photo is added succssfuly', 'data',  $newsimg);


            }
            return $this->returnError('422', 'there is an error in uploading');

        } catch (\Exception $ex) {
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAll($id){
        try{
            $news = News::find($id);
            if(!$news){
                return $this->returnError(200, 'sorry this is not exists');
            }
            $getID = $news->with(['idimage' => function($q){
                $q->select('id_image', 'news_i_id');
            }])->where('id', $id)->first();
            $imagesID = $getID->idimage;
            $arrImg = array();
            foreach ($imagesID as $imageID){
                $allimages = Newsimage::select('*')->where('id', $imageID->id_image)->get();
                array_push($arrImg, $allimages);
            }
            if(count($arrImg) > 0){
                return $this->returnData(200, 'there is all images', $arrImg);
            }else{
                return $this->returnError(422, 'sorry this is no image in this new');
            }

        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $newsimg = Newsimage::find($id);
            if($newsimg){
                //delete from file
            $image = Str::afterLast($newsimg->name, 'assets/');
            unlink($image); //delete photo from folder
            //delete from database
            $newsimg->delete();
            return $this->returnSuccess(200, 'This img successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}

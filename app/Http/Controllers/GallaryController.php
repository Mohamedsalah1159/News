<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Gallary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class GallaryController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'name.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
                'folder_id' => 'integer',
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
                    $src->move("gallary", $file_name);
                    Gallary::create([
                        'name' => $file_name,
                        'folder_id' => $request->folder_id

                    ]);
                    $lastImgAdded=count($request->name);
                    $lastImage = Gallary::select("id")->latest('id')->take($lastImgAdded)->get();
                }

                return $this->returnSuccess(200, 'photo is added succssfuly', $lastImage);
            }
            return $this->returnError('422', 'there is an error in uploading');




        } catch (\Exception $ex) {
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllGallary(){
        try{
            $gallary = Gallary::select('*')->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all gallary', $gallary);
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }

    }
    public function destroy($id){
        try{
            $gallary = Gallary::find($id);
            if($gallary){
                //delete from folder
            $image = Str::afterLast($gallary->name, 'assets/');
            unlink($image); //delete photo from folder
            //delete from database
            $gallary->delete();
            return $this->returnSuccess(200, 'This img successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');

        }


    }
}

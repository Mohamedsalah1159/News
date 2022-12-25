<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Founder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class FounderController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'desc' => 'required',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            $file_path = $this->saveFile($request->img, 'founders');
            Founder::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'img' => $file_path

            ]);
            $lastFounder = Founder::latest('id')->first();
            return $this->returnSuccess(200, 'this founder is added succssfuly', $lastFounder );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getAllFounder(){
        try{
            $founder = Founder::select('*')->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all founders', $founder);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function destroy($id){
        try{
            $founder = Founder::find($id);
            if($founder){
                //delete from file
            $image = Str::afterLast($founder->img, 'assets/');
            unlink($image); //delete photo from folder
            //delete from database
            $founder->delete();
            return $this->returnSuccess(200, 'This img successfuly Deleted');

            }
            return $this->returnError(422, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }


}

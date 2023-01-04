<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Source;
use Illuminate\Support\Facades\Validator;

class SourceController extends Controller
{
    use AllTrait;
        public function getAll($id){
        try{
            $source = Source::select("*")->where('news_s_id', $id)->get();
            if($source->count() >= 1){
                return $this->returnData(200, 'there is all sources', $source);
            }
            return $this->returnError(200, 'sorry this is not exists');

        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $source = Source::find($id);
            if($source){
            //delete from database
            $source->delete();
            return $this->returnSuccess(200, 'This source successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }

    }
}

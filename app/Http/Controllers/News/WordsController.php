<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Word;
use Illuminate\Support\Facades\Validator;

class WordsController extends Controller
{
    use AllTrait;

    public function getAll($id){
        try{
            $word = Word::select("*")->where('news_w_id', $id)->get();
            if($word->count() >= 1){
                return $this->returnData(200, 'there is all words', $word);
            }
            return $this->returnError(200, 'sorry this is not exists');

        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $word = Word::find($id);
            if($word){
            //delete from database
            $word->delete();
            return $this->returnSuccess(200, 'This word successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AnswerController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'question_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            Answer::create([
                'name' => $request->name,
                'question_id' => $request->question_id,
                
            ]);
            $lastAnswer = Answer::latest('id')->first();
            return $this->returnSuccess(200, 'this answer is added succssfuly', $lastAnswer );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function update(Request $request, $id){
        try{
            //find answer
            $answer = Answer::find($id);
            if(! $answer){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'question_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $answer->update([
                'name' => $request->name,
                'question_id' => $request->question_id,
            ]);

            return $this->returnSuccess(200, 'this answer is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function changeStatus($id){
        try{
            $status = Answer::find($id);
            if(!$status){
                return $this->returnError(200, 'sorry this is not exists');
            }
            $newstatus = $status->status == 0 ? 1 : 0;
            $status->update(['status' => $newstatus]);
            return $this->returnSuccess(200, 'this status is changed succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllAnswerswithquestion($id){
        try{
            $question = Question::find($id);
            if($question){
                $answer = answer::select('*')->where('question_id', $id)->get();
                return $this->returnData(200, 'there is all answer about this question', $answer);
            }else{
                return $this->returnError(200, 'sorry this is not exists');
            }

        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function destroy($id){
        try{
            $answer = Answer::find($id);
            if($answer){
            //delete from database
            $answer->delete();
            return $this->returnSuccess(200, 'This answer successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
}

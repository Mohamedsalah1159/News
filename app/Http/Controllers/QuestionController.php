<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'exam_id' => 'required|integer',
                'type' => 'required|in:text,choose,rightAndLong',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            Question::create([
                'name' => $request->name,
                'exam_id' => $request->exam_id,
                'type' => $request->type,              
            ]);
            $lastQuestion = Question::latest('id')->first();
            return $this->returnSuccess(200, 'this question is added succssfuly', $lastQuestion );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function update(Request $request, $id){
        try{
            //find question
            $question = Question::find($id);
            if(! $question){
                return $this->returnError(422, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'exam_id' => 'required|integer',
                'type' => 'required|in:text,choose,rightAndLong',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $question->update([
                'name' => $request->name,
                'exam_id' => $request->exam_id,
                'type' => $request->type,  
            ]);

            return $this->returnSuccess(200, 'this question is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllQuestions(){
        try{
            $questions = Question::paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all questions', $questions);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getOneQuestion($id){
        try{
            //find folder
            $question = Question::with('answer')->find($id);
            if(! $question){
                return $this->returnError(422, 'sorry this is not exists');
            }
            return $this->returnData(200, 'this is question with his answer', $question);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function destroy($id){
        try{
            $question = Question::find($id);
            if($question){
            //delete gallery in this folder 
            $question->answer()->delete();
            //delete folder from database
            $question->delete();
            return $this->returnSuccess(200, 'This question successfuly Deleted');

            }
            return $this->returnError(422, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}



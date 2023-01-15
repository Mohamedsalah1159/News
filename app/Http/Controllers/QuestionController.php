<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [

            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            foreach($request->toArray() as $oneRequest){
                Question::create([
                    'name' => $oneRequest['question'],
                    'exam_id' => $oneRequest['examId'],
                    'type' => $oneRequest['questionType'] ,              
                ]);
                $lastQuestion = Question::latest('id')->first();
                foreach($oneRequest['answer'] as $answer){
                    Answer::create([
                        'name' => $answer['name'],
                        'question_id' => $lastQuestion->id,
                        'status' => $answer['status']
                    ]);
                }
            }

            return $this->returnSuccess(200, 'this questions are added succssfuly');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function update(Request $request, $id){
        try{
            //find question
            $question = Question::find($id);
            if(! $question){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [

            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //update request in db
            foreach($request->toArray() as $oneRequest){
                $question->update([
                    'name' => $oneRequest['question'],
                    'exam_id' => $oneRequest['examId'],
                    'type' => $oneRequest['questionType'] ,              
                ]);
                foreach($oneRequest['answer'] as $answer){
                    Answer::create([
                        'name' => $answer['name'],
                        'question_id' => $question->id,
                        'status' => $answer['status']
                    ]);
                }
            }

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
                return $this->returnError(200, 'sorry this is not exists');
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
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}



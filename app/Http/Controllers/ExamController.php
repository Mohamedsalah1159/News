<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Exam;
use App\Models\ExamUser;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ExamController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'appointment_time' => 'required|date_format:"H:i',
                'appointment' => "required|after:date('Y-m-d')",
                'time' => 'required|integer|min:1|max:24',
                'user_id.*' => 'integer',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }

            Exam::create([
                'name' => $request->name,
                'appointment_time' =>  $request->appointment_time,
                'appointment' => $request->appointment,
                'time' => $request->time . " hours",
            ]);
            $lastNewExam = Exam::select('id')->latest('id')->first();
            $users = $request->user_id;
            foreach ($users as $user){
                ExamUser::create([
                    'user_id' => $user,
                    'exam_id' => $lastNewExam->id
                ]);
            }

            return $this->returnSuccess(200, 'this Exam is added succssfuly', $lastNewExam );

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function update(Request $request, $id){
        try{
            //find exam
            $exam = Exam::find($id);
            if(! $exam){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'appointment_time' => 'required|date_format:"H:i',
                'appointment' => "required|after:date('Y-m-d')",
                'time' => 'required|integer|min:1|max:24',

            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $exam->update([
                'name' => $request->name,
                'appointment_time' =>  $request->appointment_time,
                'appointment' => $request->appointment,
                'time' => $request->time . " hours",
            ]);
            $examUser = ExamUser::select('user_id')->where('exam_id', $exam->id)->get();
            $examUserAll = json_decode($examUser, true);
            foreach($examUserAll as $one){
                $users = $request->user_id;

                if (is_array($users) || is_object($users)){
                    foreach ($users as $user){
                        if(($user == $one['user_id'])){
                            return $this->returnError(200, 'sorry this is exists');
                        }
                        ExamUser::create([
                            'user_id' => $user,
                            'exam_id' => $id
                        ]);
    
                    }
                }
            }
            return $this->returnSuccess(200, 'this exam is updated succssfuly');

        }catch(\Exception $ex){
            return $ex;

            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllExams(){
        try{
            $exams = Exam::paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all exams', $exams);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getOneExam($id){
        try{
            //find exam
            $exam = Exam::with(['questions.answer'])->find($id);
            if(! $exam){
                return $this->returnError(200, 'sorry this is not exists');
            }
            return $this->returnData(200, 'there is exam with his all questions and answer', $exam);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getOneExamWithUsers($id){
        try{
            //find exam
            $exam = Exam::with(['examUsers' => function($q){
                $q->select('user_id', 'exam_id')->get();
            }])->find($id);
            if(! $exam){
                return $this->returnError(200, 'sorry this is not exists');
            }
            return $this->returnData(200, 'there is exam with his all users in this', $exam);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getUserWithHisExams($id){
        try{
            $user = User::with('userExams.examUsers')->find($id);
            if(! $user){
                return $this->returnError(200, 'sorry this is not exists');
            }
            return $this->returnData(200, 'there is user with his Exams', $user);

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $exam = Exam::find($id);
            if($exam){

            $exam->questions()->delete();
            //delete exam from database
            $exam->delete();
            return $this->returnSuccess(200, 'This exam successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\AllTrait;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamUser;

class ExamUserController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'exam_id' =>'required|integer',
                'user_id' => 'required',
                'user_id.*' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            $users = $request->user_id;
            foreach ($users as $user){
                ExamUser::create([
                    'user_id' => $user,
                    'exam_id' => $request->exam_id
                ]);
            }
            return $this->returnSuccess(200, 'this Exam assigned with users succssfuly');

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }

    }

}

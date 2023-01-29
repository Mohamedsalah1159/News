<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\User;

class UserController extends Controller
{
    use AllTrait;
    public function getAllUsers(){
        $users = User::where('status', 0)->paginate(PAGINATION_COUNT);
        return $this->returnData(200, 'there are all Users', $users);
    }
    public function getAllAdmins(){
        $admins = User::where('status', 1)->paginate(PAGINATION_COUNT);
        return $this->returnData(200, 'there are all Admins', $admins);
    }
    public function getUser($id){
        $user = User::find($id);
        if(! $user){
            return $this->returnError(200, 'sorry this user is not exists');
        }
        if($user->status == 'User'){
        return $this->returnData(200, 'this is User', $user);
        }else{
            return $this->returnError(200, 'sorry You can\'t see this data');
        }

    }
    /*public function changeUserStatus($id){
        $user = User::find($id);
        if(! $user){
            return $this->returnError(200, 'sorry this user is not exists');
        }else{
            if($user->status == 'User'){
                $user->update([
                    'status' => 1
                ]);
                return $this->returnSuccess(200, 'this user is changed status To Admin succssfully',  $user);
    
            }else{
                $user->update([
                    'status' => 0
                ]);
                return $this->returnSuccess(200, 'this user is changed status To User succssfully',  $user);
            }
        }

    }*/
}

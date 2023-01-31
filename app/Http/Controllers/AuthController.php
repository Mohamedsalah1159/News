<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\AllTrait;
use Validator;

class AuthController extends Controller
{
    use AllTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return $this->returnError(422, 'sorry Unauthorized');
        }
        return $this->createNewToken($token);
    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return $this->returnSuccess(200, 'User successfully registered','user', $user );
    }
    public function userProfile() {
        return response()->json(auth()->user());
    }
    public function logout() {
        auth()->logout();
        return $this->returnSuccess(200, 'User successfully signed out');
    }

    // Refresh a token.
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    // create new token
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 10000,
            'user' => auth()->user()
        ]);
    }
}

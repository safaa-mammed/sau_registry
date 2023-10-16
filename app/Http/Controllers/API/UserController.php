<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{
    public function registerUser(Request $request) {
        //validate request data
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'unique:users,email|required|email',
            'password'=>'required',
            'confirm_password'=>'required|same:password',
        ]);

        if($validator->fails()) {
            return response(['Error', $validator->errors()]);
        }

        //if validation is successful, insert new user to model
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('SAU')->accessToken;
        return response(['data'=> $user, 'token info'=>$success['token'], 'message'=>'Registration Successful'], 200);

    }

    public function loginUser(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if($validator->fails()) {
            return response(['Error', $validator->errors()]);
        }

        $user = User::whereEmail($request->email)->first();

        //if password does not match or email does not exist
        if ($user == null || !Hash::check($request->password, $user->password)) {
            return response(['Message'=>'The provided email/ password does not match our records.'],401);
        }
        else {
            $token = $user->createToken('SAU')->accessToken;
            return response(['status'=>200, 'token'=>$token],200);
        }
    }

    public function getUserDetail()
    {
        //checks if user is authorised
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            return response(['data'=> $user], 200);
        }
        return response(['data'=>'unauthorized'], 401);
    }

    public function userLogout()
    {
        //Revoke access token
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();
            \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked'=>true]);
            $accessToken->revoke();
            return response(['message'=> 'User Logout Successfully'], 200);
        }
        else
            return response(['data'=>'unauthorized'], 401);
    }

}

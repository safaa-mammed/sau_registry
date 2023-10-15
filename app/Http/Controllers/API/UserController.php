<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{
    public function registerUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'confirm_password'=>'required|same:password',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('SAU')->accessToken;
        return response(['data'=> $user, 'token info'=>$success['token'], 'message'=>'Registration Successful'], 401);

    }

    public function loginUser(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

//        $input = $request->all();
        $user = User::whereEmail($request->email)->first();
        $token = $user->createToken('SAU')->accessToken;
        return response(['status'=>200, 'token'=>$token],200);
    }

    public function getUserDetail()
    {
        //
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

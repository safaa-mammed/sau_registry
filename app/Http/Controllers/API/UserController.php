<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{
    public function loginUser(Request $request)
    {
        //
        $input = $request->all();
        $user = Auth::attempt($input);
        $user2 = User::whereEmail($request->email)->first();
        $token = $user2->createToken('example')->accessToken;
        return response(['status'=>200, 'token'=>$token],200);
    }

    public function getUserDetail()
    {
        //
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            return response(['date'=> $user], 401);
        }
        return response(['date'=>'unauthorized'], 401);
    }

    public function userLogout()
    {
        //
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();
            \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked'=>true]);
            $accessToken->revoke();
            return response(['date'=>'unauthorized', 'message'=> 'User Logout Successfully'], 401);
        }
        return response(['date'=>'unauthorized'], 401);
    }

}

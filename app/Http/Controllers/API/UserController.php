<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
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
//        $user = User::find(1);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('SAU')->accessToken;
        $success['name']= $user->name;
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

        $input = $request->all();
        $user = Auth::attempt($input);
        $user2 = User::whereEmail($request->email)->first();
        $token = $user2->createToken('SAU')->accessToken;
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

    /*
    public function index() {
        //check if user is logged in
        if(Auth::guard('api')->check()){
            $students = Student::orderBy('id', 'desc')->paginate(2,['name','address']);

            //return index page and pass through the student variable along with page number data
            return response(['Student Data'=>$students]);

        }
        else
            return response(['User'=>'unauthorized'], 401);
    }

    public function searchStudent(Request $request){

        //check if user is logged in
        if(Auth::guard('api')->check()){

            $email = $request->input('email');
            $name = $request->input('name');
            if($email == null && $name != null) {
                //search by name
                $students =  Student::where('name', 'like','%'. $name . '%')->get(['name','address']);;
            }
            else if($name == null && $email != null) {
                //search by email
                $students =  Student::where('email', $email)->get(['name','address']);;
            }
            else {
                return response(['Message'=>'Search by name OR email'], 401);
            }

            if(!$students->isEmpty()){
                return response(['Message'=>'Student Found.', 'Student Data'=>$students], 401);

            }
            else {
                return response(['Message'=>'Student NOT found.'], 401);
            }
        }
        else
            return response(['User'=>'unauthorized'], 401);

    }

    public function registerStudent(Request $request) {

        //check if user is logged in
        if(Auth::guard('api')->check()){
            //validate input using laravel validate
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'study_course' => 'required',
                'address' => 'required'
            ]);

            if($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            //register a student using student model
            Student::create($request->all());
            return response(['Status'=>'Successfully Registered.'], 200);
        }
        else
            return response(['User'=>'unauthorized'], 401);

    }*/


}

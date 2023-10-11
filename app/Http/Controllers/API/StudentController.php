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

class StudentController extends Controller
{
    public function index()
    {
        //check if user is logged in
        if (Auth::guard('api')->check()) {
            $students = Student::orderBy('id', 'desc')->paginate(2, ['name', 'address']);

            //return index page and pass through the student variable along with page number data
            return response(['Student Data' => $students]);

        } else
            return response(['User' => 'unauthorized'], 401);
    }

    public function searchStudent(Request $request)
    {

        //check if user is logged in
        if (Auth::guard('api')->check()) {

            $email = $request->input('email');
            $name = $request->input('name');
            if ($email == null && $name != null) {
                //search by name
                $students = Student::where('name', 'like', '%' . $name . '%')->get(['name', 'address']);;
            } else if ($name == null && $email != null) {
                //search by email
                $students = Student::where('email', $email)->get(['name', 'address']);;
            } else {
                return response(['Message' => 'Search by name OR email'], 401);
            }

            if (!$students->isEmpty()) {
                return response(['Message' => 'Student Found.', 'Student Data' => $students], 401);

            } else {
                return response(['Message' => 'Student NOT found.'], 401);
            }
        } else
            return response(['User' => 'unauthorized'], 401);

    }

    public function registerStudent(Request $request)
    {

        //check if user is logged in
        if (Auth::guard('api')->check()) {
            //validate input using laravel validate
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'study_course' => 'required',
                'address' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            //register a student using student model
            Student::create($request->all());
            return response(['Status' => 'Successfully Registered.'], 200);
        } else
            return response(['User' => 'unauthorized'], 401);

    }
}

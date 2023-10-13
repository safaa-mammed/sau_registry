<?php

namespace App\Http\Controllers\API;

use App\Imports\ImportStudent;
use App\Exports\ExportStudent;
use App\Models\User;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        //check if user is logged in
        if (Auth::guard('api')->check()) {
            $students = Student::orderBy('id', 'desc')->paginate(5, ['name', 'address']);

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

    //bulk operations
    //PUT for updating
    //DELETE for deleting
    //POST for creating

    //bulk create student data
    public function importStudentData(Request $request) {
//        try{
//            Excel::import(new ImportStudent, request()->file('files'));
//            return response(['Status'=> 'completed']);
//        }
//        catch (\Maatwebsite\Excel\Validators\ValidationException $e){
//            $failures = $e->failures();
//            return response()->with('error_message', $e->getMessage())->with('failures', $e->failures());
//            return response(['failure'=>$failures]);
//        }
        $import = new ImportStudent();
        $import->import($request->file('files'));
        foreach ($import->failures() as $failure) {
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failure->errors(); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
        }
        return response(['Status'=> 'completed without duplicate records']);

//        return response(['Status'=>'Import Success']);
    }
    public function exportStudentData() {
        return Excel::download(new ExportStudent, 'StudentData.xlsx');
    }

    //bulk update
    public function bulkUpdate() {

    }
}

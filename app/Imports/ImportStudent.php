<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
//use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
//use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
class ImportStudent implements ToModel,WithValidation,WithHeadingRow,SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable,SkipsFailures;
    public function model(array $row)
    {
        return new Student([
            //
            'name'=>$row['name'],
            'email'=>$row['email'],
            'address'=>$row['address'],
            'study_course'=>$row['course'],

        ]);
    }

    public function rules(): array
    {
        return [
            'email'=> 'unique:students,email',
            'name'=> 'required',
            'address'=>'required',
            'course'=>'required'
        ];
    }
    /*
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
        throw $e;
    }*/

}

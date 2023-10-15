<?php
namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
class ImportStudent implements ToModel, WithUpserts,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable;
    //if records already exist, the database record will be updated not inserted
    public function uniqueBy()
    {
        return 'email'; //identifies how a record is unique
    }

    public function model(array $row)
    {
        //if email is null then do not add row
        if (!isset($row['email'])) {
            return null;
        }
        //if action col says to delete then delete record
        else if($row['action'] == 'delete'){
            $student = Student::where('email',$row['email']);
            $student->delete();
            return null;
        }
        //else insert or update new/existing records
        else {
            return new Student([
                'name'=>$row['name'],
                'email'=>$row['email'],
                'address'=>$row['address'],
                'study_course'=>$row['course'],

            ]);
        }

    }

}

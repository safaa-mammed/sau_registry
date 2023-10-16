# South of Australia Student Registry
## Postman Collection
https://elements.getpostman.com/redirect?entityId=30397269-788982ad-5ac3-4329-a5c6-85c5784ef835&entityType=collection

## Presentation
[Presentation](https://drive.google.com/drive/folders/1ExedNcaLvbWTUeHn8xLw6Nr_jLjjkyMt?usp=sharing)

## About
The SAU Student Registry API is a RESTFUL API that allows university staff to login and conduct operations on student data such as creating, updating, deleting, and displaying student data 

## Main Files
* [User Model](app/Models/User.php)
* [Student Model](app/Models/Student.php)
* [Student Model](app/Models/Student.php)
* [Auth Config](config/auth.php)
* [API Routes](routes/api.php)
* [UserController](app/Http/Controllers/API/UserController.php)
* [StudentController](app/Http/Controllers/API/StudentController.php)
* [Import Class](app/Imports/ImportStudent.php)
* [Export Class](app/Exports/ExportStudent.php)

## API Features

### Setting up Laravel Passport
Laravel Passport is used as an authorization method to register, login and allow authorized users to use the API by using access tokens. This is done by installing Laravel Passport, configuring the auth.php config file for api and changing driver to "passport", as well as configuring the User model, and api routes to use auth:api middleware. 

### Setting up Models & Controllers
The User model is a default Model created in Laravel, while the Student Model was created for student data. Each of these has a controller whereby the UserController.php handles requests related to user registration and login, while the StudentController handles request to access student data.

### Setting up Routes
```
Route::controller(UserController::class)->group(function() {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser');
    Route::get('user', 'getUserDetail');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');

Route::controller(StudentController::class)->group(function() {
    Route::post('display', 'index');
    Route::post('search', 'searchStudent');
    Route::post('registerStudent', 'registerStudent');
    Route::post('import', 'importStudentData');
    Route::get('export', 'exportStudentData');
})->middleware('auth:api');
```

### University Staff Registration using Laravel Passport
University staff are under the User model to register and login with authorization tool "Laravel Passport". University staff can register using a POST method to pass form data of name, email, and password, the data is validated using Laravel Facades Validator. The response returns the user data along with the access token.

### University Staff Login & Authorization
The university staff can log in using their email and password. After successfully logging in, the response displays their access token which is used to access student data operations using ```Auth::guard('api')->check()``` to check if the user is authorized to access the API.

### University Staff Logout
After logging out, the access token will be revoked and the staff will need to log in and create a new access token to access the API.
```
$accessToken = Auth::guard('api')->user()->token();
\DB::table('oauth_refresh_tokens')
    ->where('access_token_id', $accessToken->id)
    ->update(['revoked'=>true]);
$accessToken->revoke();
```
### Register Students

### Display Student Data with Pagination
The index API displays all students data using pagination from the Student Controller. This is done by retrieving the student model and displaying 5 students name and address per page using ```Student::orderBy('id', 'desc')->paginate(5, ['name', 'address']);```. This is a POST method that requires form data to specify the page number, and utilizes the authorization guard to display the data.

### Search Student
The authorised staff can retrieve the specific student data by using the API and posting form data of student name or email. The staff can only search using one method or else they will be prompted.

### Create, Update, Delete Operations from CSV files
University staff can import a CSV file as form data to the API using Laravel Excel. This allows the staff to do bulk operations while taking care of duplicates, and validations. The ImportStudent class uses Laravel Excel functions that passes each CSV row as an array to add it to the Student Model. Using the "WithUpserts", it inserts and updates the Student Model by checking if the record already exists, hence staff can bulk create and update records. A specific column in the CSV file "action" has null or "delete" value to indicate whether the record should be deleted. The ImportStudent class first validates the CSV row for user 'email', and then checks the action to decide whether to delete, or create/update the Student Model. 
```
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
```
### Export Model as CSV
Using Laravel Excel, the authorised staff can export the Student Model to a CSV file.

## Sources
* https://laravel.com/docs/10.x/passport
* https://laravel.com/docs/10.x/facades#main-content
* https://laravel.com/docs/10.x/authorization#via-middleware
* https://docs.laravel-excel.com/3.1/getting-started/

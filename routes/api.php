<?php
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::resource('users', \App\Http\Controllers\UserController::class);


//requires authorisation
Route::controller(UserController::class)->group(function() {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser');
    Route::get('user', 'getUserDetail');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');

//requires authorisation
Route::controller(StudentController::class)->group(function() {
    Route::post('index', 'index');
    Route::post('search', 'searchStudent');
    Route::post('registerStudent', 'registerStudent');
    Route::post('import', 'importStudentData');
    Route::get('export', 'exportStudentData');
})->middleware('auth:api');


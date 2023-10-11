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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function() {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser');

});

//requires authorisation
Route::controller(UserController::class)->group(function() {
    Route::get('user', 'getUserDetail');
    Route::get('logout', 'userLogout');
    Route::post('display', 'viewStudent');
    Route::post('search', 'searchStudent');
    Route::post('create', 'createPage');
    Route::post('registerStudent', 'registerStudent');
})->middleware('auth:api');

Route::controller(StudentController::class)->group(function() {
    Route::post('display', 'index');
    Route::post('search', 'searchStudent');
    Route::post('create', 'createPage');
    Route::post('registerStudent', 'registerStudent');
})->middleware('auth:api');


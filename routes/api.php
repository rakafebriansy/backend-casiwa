<?php

use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('/users')->group(function(){
    Route::post('/',[UserController::class, 'register']);
});

Route::get('/universities',[UserDetailController::class, 'getUniversities']);
Route::get('/study-programs',[UserDetailController::class, 'getStudyPrograms']);

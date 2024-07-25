<?php

use App\Http\Controllers\DropdownController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailController;
use App\Http\Resources\LoginResource;
use App\Http\Utilities\CustomResponse;
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
Route::middleware('auth:sanctum')->prefix('/user')->group(function() {
    Route::get('/',function(Request $request) {
        return response()->json([
            'success' => true
        ]);
    });
    Route::post('/upload',[NotesController::class,'upload']);
    Route::get('/uploaded-notes',[NotesController::class,'getUploadedNotePreviews']);
    Route::get('/downloaded-notes',[NotesController::class,'getDownloadedNotePreviews']);
    Route::get('/is-bought',[UserController::class,'isBought']);
    Route::get('/payment-token',[PaymentController::class,'getPaymentToken']);
    Route::post('/payment',[PaymentController::class,'doPayment']);
    
    Route::get('/test', function() {
        return Auth::user()->id;
    });
});
Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);

Route::get('/universities',[UserDetailController::class, 'getUniversities']);
Route::get('/study-programs',[UserDetailController::class, 'getStudyPrograms']);

Route::get('/notes',[NotesController::class, 'getNotePreviews']);
Route::get('/note-details',[NotesController::class,'getSingleNote']);
Route::get('/note-preview',[NotesController::class,'getSingleNotePreview']);
Route::get('/total-notes',[NotesController::class, 'getNotePreviews']);

Route::get('/preview/{name}',[NotesController::class, 'loadImagePreview']);
Route::get('/document/{name}',[NotesController::class, 'loadDocument']);
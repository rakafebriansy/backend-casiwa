<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\UtilsController;
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
Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);
Route::middleware('auth:sanctum')->prefix('/user')->group(function() {
    Route::get('/',function(Request $request) {
        return response()->json([
            'success' => true
        ]);
    });
    Route::post('/upload',[NotesController::class,'upload']);
    Route::get('/uploaded-notes',[NotesController::class,'getUploadedNotePreviews']);
    Route::get('/downloaded-notes',[NotesController::class,'getDownloadedNotePreviews']);
    Route::get('/download/{name}',[NotesController::class,'download']);
    Route::get('/is-bought',[UserController::class,'isBought']);
    Route::get('/payment-token',[PaymentController::class,'getPaymentToken']);
    Route::get('/is-paid',[PaymentController::class,'isPaid']);
    Route::get('/redeem',[UserDetailController::class, 'getRedeemHistories']);
    Route::post('/redeem',[PaymentController::class, 'doRedeem']);
    Route::get('/profile',[UserController::class,'profile']);
    Route::post('/edit-profile',[UserController::class,'editProfile']);
    Route::get('/get-balance',[UserDetailController::class, 'getBalance']);
    Route::post('/free-download',[PaymentController::class, 'freeDownload']);
    Route::get('/edit-note',[NotesController::class, 'getEditedNote']);
    Route::post('/edit-note',[NotesController::class, 'editNote']);
    Route::post('/delete-note',[NotesController::class, 'deleteNote']);
    
    Route::get('/test', function() {
        return Auth::user()->id;
    });
});

Route::post('/admin/login',[AdminController::class, 'login']);
Route::middleware('auth:sanctum')->prefix('/admin')->group(function() {
    Route::get('/',function(Request $request) {
        return response()->json([
            'success' => true
        ]);
    });
    Route::prefix('/universities')->group(function() {
        Route::post('/store',[UserDetailController::class, 'storeUniversities']);
        Route::post('/edit',[UserDetailController::class, 'editUniversities']);
        Route::post('/delete',[UserDetailController::class, 'deleteUniversities']);
    });
    
    Route::prefix('/study-programs')->group(function() {
        Route::post('/store',[UserDetailController::class, 'storeStudyPrograms']);
        Route::post('/edit',[UserDetailController::class, 'editStudyPrograms']);
        Route::post('/delete',[UserDetailController::class, 'deleteStudyPrograms']);
    });
    
    Route::prefix('/banks')->group(function() {
        Route::post('/store',[UserDetailController::class, 'storeBanks']);
        Route::post('/edit',[UserDetailController::class, 'editBanks']);
        Route::post('/delete',[UserDetailController::class, 'deleteBanks']);
    });
    Route::prefix('/redeem')->group(function() {
        Route::post('/',[AdminController::class, 'redeemUser']);
        Route::get('/unpaid',[AdminController::class, 'getUnpaidRedeem']);
        Route::get('/history',[AdminController::class, 'getRedeemHistories']);
    });
    Route::post('/edit-password',[AdminController::class,'editPassword']);
    Route::post('/delete-note',[AdminController::class,'deleteNote']);
});

Route::get('/universities',[UserDetailController::class, 'getUniversities']);
Route::get('/study-programs',[UserDetailController::class, 'getStudyPrograms']);
Route::get('/banks',[UserDetailController::class, 'getBanks']);
Route::get('/faqs',[UtilsController::class, 'getFaqs']);

Route::get('/notes',[NotesController::class, 'getNotePreviews']);
Route::get('/note-details',[NotesController::class,'getSingleNote']);
Route::get('/note-preview',[NotesController::class,'getSingleNotePreview']);
Route::get('/total-notes',[NotesController::class, 'getNotePreviews']);

Route::get('/preview/{name}',[NotesController::class, 'loadImagePreview']);
Route::get('/document/{name}',[NotesController::class, 'loadDocument']);
Route::get('/ktp/{name}',[UserController::class,'loadKTP']);
Route::post('/payment',[PaymentController::class,'doPayment']);
Route::post('/forgot-password',[UserController::class,'forgotPassword']);
Route::get('/reset',[UserController::class,'getReset']);
Route::post('/reset-password',[UserController::class,'resetPassword']);
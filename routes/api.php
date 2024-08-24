<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

Route::controller(AuthenticationController::class)->group(function(){
    Route::post('login','login');
    Route::post('register','register');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile',[AuthenticationController::class,'getAuthUser']);
    Route::patch('profile',[AuthenticationController::class,'updateProfile']);
    Route::post('change-password',[AuthenticationController::class,'changePassword']);

    Route::resource('transactions', TransactionController::class);
    Route::get('user-transactions', [TransactionController::class,'getUserTransactions']);
});


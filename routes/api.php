<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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

Route::get('get-platform-wallet',[WalletController::class,'getAdminWallet']);


Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile',[AuthenticationController::class,'getAuthUser']);
    Route::patch('profile',[AuthenticationController::class,'updateProfile']);
    Route::post('change-password',[AuthenticationController::class,'changePassword']);
    
    Route::resource('transactions', TransactionController::class);
    Route::get('user-transactions', [TransactionController::class,'getUserTransactions']);
    Route::get('transactions-by-user-id/{id}', [TransactionController::class,'getTransactionsByUserId']);
    Route::patch('wallet/{wallet_id}',[WalletController::class,'update']);
    Route::get('overview',[WalletController::class,'overview']);
    
    Route::get('users',[AuthenticationController::class,'getUsers']);
    Route::get('users/{id}',[AuthenticationController::class,'getUserById']);

    Route::resource('kyc', KycController::class);

});


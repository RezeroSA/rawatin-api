<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServicesController;
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

// Route::middleware(['auth:sanctum'])->group(function () {
// });

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);
Route::post('resetPIN', [AuthController::class, 'resetPIN']);
Route::post('checkExistingNumber', [AuthController::class, 'checkExistingNumber']);
Route::post('sendRegisterOTP', [AuthController::class, 'sendRegisterOTP']);
Route::post('sendLoginOTP', [AuthController::class, 'sendLoginOTP']);
Route::post('sendRecoveryOTP', [AuthController::class, 'sendRecoveryOTP']);
Route::post('verifyRegistrationOTP', [AuthController::class, 'verifyRegistrationOTP']);
Route::post('verifyLoginOTP', [AuthController::class, 'verifyLoginOTP']);
Route::post('verifyRecoveryOTP', [AuthController::class, 'verifyRecoveryOTP']);
Route::post('getProfile', [AuthController::class, 'getProfile']);
Route::post('updateProfile', [AuthController::class, 'updateProfile']);

Route::get('services', [ServicesController::class, 'getServices']);
Route::post('addServices', [ServicesController::class, 'addServices']);

Route::get('orders', [OrderController::class, 'index']);
Route::post('insertOrder', [OrderController::class, 'insertOrder']);
Route::post('insertEmergencyOrder', [OrderController::class, 'insertEmergencyOrder']);
Route::post('getUserOrder', [OrderController::class, 'getOrderByUserId']);
Route::post('getLastPesananSelesai', [OrderController::class, 'getLastPesananSelesai']);
Route::post('getWaitingOrders', [OrderController::class, 'getWaitingOrders']);
Route::post('getCancelledOrders', [OrderController::class, 'getCancelledOrder']);
Route::post('getCompletedOrders', [OrderController::class, 'getCompletedOrder']);
Route::post('detailCompletedOrder', [OrderController::class, 'detailCompletedOrder']);
Route::post('detailCanceledOrder', [OrderController::class, 'detailCanceledOrder']);
Route::put('cancelOrder', [OrderController::class, 'cancelOrder']);
Route::put('submitReview', [OrderController::class, 'submitReview']);


Route::get('getAllOfficers', [OfficerController::class, 'getAllOfficers']);
Route::post('registerOfficer', [OfficerController::class, 'registerOfficer']);

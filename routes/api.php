<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ======================
// Auth Routes
// ======================
use App\Http\Controllers\Auth\AuthController;
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/find-account', [AuthController::class, 'findAccount']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/logout-device', [AuthController::class, 'logoutDevice']);
        Route::get('/devices', [AuthController::class, 'devices']);
    });
});


// ======================
// Vendor Routes
// ======================
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Auth\ProfileController;
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::put('/', [ProfileController::class, 'update']);
        Route::put('/password', [ProfileController::class, 'changePassword']);
    });

    Route::prefix('vendor')->group(function() {
        // get login user
        Route::get('/', [VendorController::class, 'getVendor']);
        Route::post('/edit-vendor/{id}', [VendorController::class, 'editVendor'])->where('id', '[0-9]+');
    });
});


// ======================
// Customer Routes
// ======================
use App\Http\Controllers\Customer\CustomerController;
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('customers')->group(function() {
        Route::get('/', [CustomerController::class, 'getCustomers']);
        Route::get('/get-visa-category', [CustomerController::class, 'getVisaCategory']);
        Route::get('/get-country', [CustomerController::class, 'getCountry']);
        Route::post('/create', [CustomerController::class, 'createCustomer']);
        Route::get('/{id}', [CustomerController::class, 'getCustomerById'])->where('id', '[0-9]+');
        Route::put('/update/{id}', [CustomerController::class, 'updateCustomer'])->where('id', '[0-9]+');
    });
});


// ======================
// Follow Up Routes
// ======================
use App\Http\Controllers\Followup\FollowupController;
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('follow-up')->group(function() {
        Route::get('/customers', [FollowupController::class, 'getCustomers']);
        Route::get('/customers/{id}/history', [FollowupController::class, 'getFollowupHistory']);
        Route::post('/store', [FollowupController::class, 'store']);
    });
});
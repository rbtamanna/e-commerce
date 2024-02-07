<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('backend.pages.dashboard');
//});


Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/', [DashboardController::class, 'index']);
Route::get('login', [AuthController::class, 'index'])->name('viewLogin');
Route::post('login', [AuthController::class, 'authenticate']);
Route::get('register', [AuthController::class, 'viewRegister']);
Route::post('register', [AuthController::class, 'register']);
Route::group(['middleware'=> 'auth'], function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('change_password', [AuthController::class, 'viewChangePassword']);
    Route::post('change_password', [AuthController::class, 'changePassword']);
    Route::post('change_password/validate_inputs', [AuthController::class, 'validatePasswords']);
    Route::get('product/{id}/purchase', [DashboardController::class, 'purchase']);
    Route::group(['middleware'=> 'adminUser'], function() {
        Route::prefix('product')->group(function() {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/get_product_data', [ProductController::class, 'fetchData']);
            Route::get('/create', [ProductController::class, 'create']);
            Route::post('/store', [ProductController::class, 'store']);
            Route::get('/{id}/edit', [ProductController::class, 'edit']);
            Route::post('/{id}/update', [ProductController::class, 'update']);
            Route::get('/{id}/destroy', [ProductController::class, 'delete']);
            Route::get('/import', [ProductController::class, 'viewImport']);
            Route::post('/import', [ProductController::class, 'import']);
            Route::get('/export', [ProductController::class, 'export']);
        });
        Route::prefix('category')->group(function() {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/get_category_data', [CategoryController::class, 'fetchData']);
            Route::get('/create', [CategoryController::class, 'create']);
            Route::post('/validate_inputs', [CategoryController::class, 'validate_inputs']);
            Route::post('/store', [CategoryController::class, 'store']);
            Route::get('/{id}/edit', [CategoryController::class, 'edit']);
            Route::post('/{id}/update', [CategoryController::class, 'update']);
            Route::post('/{id}/validate_name',[CategoryController::class, 'validate_name']);
            Route::get('/{id}/delete', [CategoryController::class, 'delete']);
        });
    });
});


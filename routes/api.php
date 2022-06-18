<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarCategoryController;
use App\Http\Controllers\CarController;


// Authentication and registration routes
 
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register']);
Route::group(['prefix'=>'user','middleware' => ['auth.verify']], function() {
    Route::get('logout', [AuthController::class, 'logout']);
});

// Category CRUD Routes

Route::group(['prefix'=>'categories','middleware' => ['auth.verify']], function() {
    Route::get('get-list', [CarCategoryController::class, 'index']);
    Route::get('get/{id}', [CarCategoryController::class, 'find']);
    Route::post('create', [CarCategoryController::class, 'create']);
    Route::post('update',  [CarCategoryController::class, 'update']);
    Route::delete('delete/{id}',  [CarCategoryController::class, 'destroy']);
});

// Car CRUD Routes

Route::group(['prefix'=>'cars','middleware' => ['auth.verify']], function() {
    Route::get('get-list', [CarController::class, 'index']);
    Route::get('get/{id}', [CarController::class, 'find']);
    Route::post('create', [CarController::class, 'create']);
    Route::post('update',  [CarController::class, 'update']);
    Route::delete('delete/{id}',  [CarController::class, 'destroy']);
});
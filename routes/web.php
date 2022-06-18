<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarCategoryController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home route
Route::get('/', [WebController::class, 'index'])->middleware('auth')->name('home');


// Authentication and registration routes

Route::get('login', [WebController::class, 'login'])->name('login');
Route::get('register', [WebController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'webLogin'])->name('login.post');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::group(['prefix'=>'user','middleware' => ['auth']], function() {
    Route::get('logout', [AuthController::class, 'weblogout'])->name('logout');
});

// Category CRUD Routes

Route::group(['prefix'=>'categories','middleware' => ['auth']], function() {
    Route::get('create/', [WebController::class, 'addCategory'])->name('add.category');
    Route::get('edit/{id}', [WebController::class, 'editCategory'])->name('edit.category');
    Route::get('get-list', [CarCategoryController::class, 'index'])->name('categories_list');
    Route::post('create', [CarCategoryController::class, 'create'])->name('add.category.post');
    Route::post('update',  [CarCategoryController::class, 'update'])->name('edit.category.post');
    Route::get('delete/{id}',  [CarCategoryController::class, 'destroy']);
});

// Car CRUD Routes

Route::group(['prefix'=>'cars','middleware' => ['auth']], function() {
    Route::get('create/', [WebController::class, 'addCar'])->name('add.car');
    Route::get('edit/{id}', [WebController::class, 'editCar'])->name('edit.car');
    Route::post('create', [CarController::class, 'create'])->name('add.car.post');
    Route::post('update',  [CarController::class, 'update'])->name('edit.car.post');
    Route::get('delete/{id}',  [CarController::class, 'destroy']);
});

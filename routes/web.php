<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('gettodo',[App\Http\Controllers\HomeController::class,'gettodo'])->name('gettodo'); 
Route::get('gettodoworking',[App\Http\Controllers\HomeController::class,'gettodoworking'])->name('gettodoworking'); 
Route::get('gettododone',[App\Http\Controllers\HomeController::class,'gettododone'])->name('gettododone'); 

Route::post('/addtodo',[App\Http\Controllers\HomeController::class,'addtodo'])->name('addtodo'); 
Route::post('/updatetodo',[App\Http\Controllers\HomeController::class,'updatetodo'])->name('updatetodo');
Route::get('/deletetodo',[App\Http\Controllers\HomeController::class,'deletetodo'])->name('deletetodo');

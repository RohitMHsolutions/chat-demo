<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HomeController;

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

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');


Route::get('get-csrf-token', [HomeController::class, 'getCSRFToken']);


Route::middleware('auth')->group(function() {
    Route::get('users', [UserController::class,'getRecords'])->name('users');
    Route::get('messages/{sender_id}', [MessageController::class, 'load'])->name('messages');
    Route::get('check-messages', [MessageController::class, 'check'])->name('messages.check');
    Route::post('send-message', [MessageController::class, 'send'])->name('messages.send');
    Route::post('delete-message/{id}', [MessageController::class, 'delete'])->name('messages.delete');
    Route::post('read-message/{sender_id}', [MessageController::class, 'readMessage'])->name('messages.read');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
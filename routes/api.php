<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function(){
    return view('welcome');
});
Route::post(
    '/user',
    [\App\Http\Controllers\UserController::class, 'create']
)->name('pp.user.post');

Route::post(
    '/transfer',
    [\App\Http\Controllers\PaymentController::class, 'create']
)->name('pp.transfer.post');

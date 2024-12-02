<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/history', [\App\Http\Controllers\ApiController::class, 'getAttendanceHistory'])->name('attendance.history');
Route::post('/absen', [App\Http\Controllers\ApiController::class, 'absen']);
Route::post('/testabsen', [App\Http\Controllers\ApiController::class, 'testabsen']);
Route::post('/login', [App\Http\Controllers\ApiController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\ApiController::class, 'logout']);
Route::get('/test', function () {
    return "hallo";
});
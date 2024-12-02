<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\BarcodeScan;
use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Attendance;
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

Route::get('/', function () {
    $today = Carbon::now()->toDateString();
    $attendances = Attendance::whereDate('created_at', $today)->get();
    $barcode = BarcodeScan::latest()->first();
    Carbon::setLocale('id');
    $today = Carbon::now()->isoFormat('dddd');
    $shift = Shift::where('day', $today)->first();
    // dd($shift);
    // $data = '';
    return view('welcome', [
        'attendances' => $attendances,
        'barcode' => $barcode,
        'shift' => $shift,
    ]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::post('/export-to-excel', [\App\Http\Controllers\PresensiController::class, 'exportToExcel'])->name('export.excel');
Route::get('/users/{id}/toggle-active', [\App\Http\Controllers\UserController::class, 'toggleActiveStatus'])->name('users.toggle-active');
Route::get('/users/{id}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');
Route::resource('users', \App\Http\Controllers\UserController::class)->middleware('auth');
Route::resource('admins', \App\Http\Controllers\AdminController::class)->middleware('auth');
Route::resource('shifts', \App\Http\Controllers\ShiftController::class)->middleware('auth');
Route::resource('presensi', \App\Http\Controllers\PresensiController::class)->middleware('auth');
Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware('auth');

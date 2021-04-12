<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuisinierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ValidateController;


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

Route::get('/home-dashboard-admin', function () {
    return view('home-dashboard-admin');
});

Route::get('/home-dashboard-cuisinier', function () {
    return view('home-dashboard-cuisinier');
});

Route::get('/admins', function () {
    return view('Admins');
});

Route::resource('cuisiniers', CuisinierController::class);
Route::resource('admins', AdminController::class);

Route::get('/IsAccept', function()
{
    return view('IsAccept');
});
Route::get('/IsRegister', function()
{
    return view('IsRegister');
});

Route::get('/cuisiniers/validate/{id}', [App\Http\Controllers\ValidateController::class, 'accept']);

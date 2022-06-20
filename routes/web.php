<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlsController;
use App\Http\Controllers\AuthController;
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

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/user/register', [AuthController::class, 'registration'])->name('user.register');

Route::post('/user/login', [AuthController::class, 'login' ])->name('user.login');
Route::match(['get', 'post'],'/user/logout', [AuthController::class, 'logout'])->name('user.logout');

Route::group(['middleware' => 'auth:web'], function () {

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('generate-shorten-link', [ShortUrlsController::class, 'index'])->name('generate.url');  
Route::post('generate-shorten-link',  [ShortUrlsController::class, 'store'])->name('generate.shorten.link.post');  
Route::get('{url}',  [ShortUrlsController::class, 'shortenLink'])->name('shorten.link');  

});
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
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

Route::post('/user/register', [AuthController::class, 'registration' ])->name('user.register');
Route::post('/user/login', [AuthController::class, 'authenticate' ])->name('user.login');

Route::get('generate-shorten-link', [ShortUrlsController::class, 'index'])->name('generate.url');  
Route::post('generate-shorten-link',  [ShortUrlsController::class, 'store'])->name('generate.shorten.link.post');  
     
Route::get('{url}',  [ShortUrlsController::class, 'shortenLink'])->name('shorten.link');  
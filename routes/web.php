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
// Route::get('/home', 'HomeController@index')->name('home');

// Đăng ký 
Route::get('/register', 'UserController@index')->name('register');
Route::post('/register', 'UserController@storeRegister')->name('register.submit');


// Đăng nhập
Route::post('/login', 'UserController@storeLogin')->name('login.submit');
Route::get('/login', 'UserController@login')->name('login');

//Đăng xuất
Route::get('/logout', 'UserController@Logout')->name('logout');

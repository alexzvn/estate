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

Route::get('/', 'PublicController@index');

Auth::routes();

Route::group(['middleware' => 'verified'], function ()
{
    Route::get('/home', 'Customer\HomeController@index')->name('home');
    Route::get('/post/{id}/view', 'Customer\HomeController@viewPost')->name('home.post.view');
});



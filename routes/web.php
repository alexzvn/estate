<?php

use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => ['auth', 'customer'], 'namespace' => 'Customer'], function ()
{
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/online', 'Post\PostController@online')->name('post.online');
    Route::get('/fee', 'Post\PostController@fee')->name('post.fee');
    Route::get('/market', 'Post\PostController@market')->name('post.market');
    Route::get('/price', 'HomeController@price')->name('post.price');
    Route::get('/post/{id}/view', 'Post\PostController@view')->name('post.view');
    Route::post('/post/store', 'Post\PostController@store')->name('post.store');

    Route::get('/post/{id}/action/blacklist', 'Post\ActionController@blacklist')->name('post.action.blacklist');
    Route::get('/post/{id}/action/save', 'Post\ActionController@save')->name('post.action.save');
    Route::get('/post/{id}/action/report', 'Post\ActionController@report')->name('post.action.report');

    Route::group(['prefix' => 'me'], function () {

        Route::get('/account', 'Customer@me')->name('customer.self.account');
        Route::get('/history', 'Customer@history')->name('customer.self.history');
        Route::get('/orders', 'Customer@orders')->name('customer.self.orders');
        Route::get('/subscriptions', 'Customer@subscriptions')->name('customer.self.subscriptions');

        Route::get('plans', 'Customer@plans')->name('customer.self.plans');
        Route::post('/orders', 'Customer@registerOrder')->name('customer.self.orders.register');

        Route::post('/account/update', 'Customer@update')->name('customer.self.account.update');
        // Route::get('/update/avatar', 'Customer@me')->name('customer.self.account');


        Route::get('post/saved', 'Post\CustomerPost@saved')->name('customer.post.saved');
        Route::get('post/posted', 'Post\CustomerPost@posted')->name('customer.post.posted');
        Route::get('post/blacklist', 'Post\CustomerPost@blacklist')->name('customer.post.blacklist');
    });
});

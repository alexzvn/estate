<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin manager Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Manager\ManagerController@index')->name('manager');


Route::group(['prefix' => 'role', 'namespace' => 'Manager\Role'], function () {
    Route::get('/', 'RoleController@index')->name('manager.role');
    Route::get('/create', 'RoleController@create')->name('manager.role.create');
    Route::post('/store', 'RoleController@store')->name('manager.role.store');
    Route::get('/{id}/view', 'RoleController@view')->name('manager.role.view');
    Route::post('/{id}/update', 'RoleController@update')->name('manager.role.update');
    Route::post('/{id}/delete', 'RoleController@delete')->name('manager.role.delete');
});

Route::group(['prefix' => 'post', 'namespace' => 'Manager\Post'], function () {
    Route::get('/', 'PostController@index')->name('manager.post');
    Route::get('/create', 'PostController@create')->name('manager.post.create');
    Route::post('/store', 'PostController@store')->name('manager.post.store');
    Route::get('/trashed', 'PostController@trashed')->name('manager.post.trashed');
    Route::get('/{id}/view', 'PostController@view')->name('manager.post.view');
    Route::post('/{id}/update', 'PostController@update')->name('manager.post.update');
    Route::post('/{id}/delete', 'PostController@delete')->name('manager.post.delete');
    Route::post('/{id}/delete/force', 'PostController@forceDelete')->name('manager.post.delete.force');
});

Route::group(['prefix' => 'category', 'namespace' => 'Manager\Category'], function () {
    Route::get('/', 'CategoryController@index')->name('manager.category');
    Route::get('/create', 'CategoryController@create')->name('manager.category.create');
    Route::post('/store', 'CategoryController@store')->name('manager.category.store');
    Route::get('/{id}/view', 'CategoryController@view')->name('manager.category.view');
    Route::post('/{id}/update', 'CategoryController@update')->name('manager.category.update');
    Route::post('/{id}/delete', 'CategoryController@delete')->name('manager.category.delete');
});

Route::group(['prefix' => 'user', 'namespace' => 'Manager\User'], function () {
    Route::get('/', 'UserController@index')->name('manager.user');
    Route::get('/create', 'UserController@create')->name('manager.user.create');
    Route::get('/{id}', 'UserController@edit')->name('manager.user.view');
    Route::post('/{id}/update', 'UserController@update')->name('manager.user.update');
    Route::post('/{id}/delete', 'UserController@delete')->name('manager.user.delete');
    Route::get('/{id}/verify/phone', 'UserController@verifyPhone')->name('manager.user.verify.phone');
    Route::get('/{id}/unverified/phone', 'UserController@unverifiedPhone')->name('manager.user.unverified.phone');
});
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

Route::get('/', 'ManagerController@index')->name('manager');

/**
 * ROLES
 */
Route::group(['prefix' => 'role', 'namespace' => 'Role'], function () {
    Route::get('/', 'RoleController@index')->name('manager.role');
    Route::get('/create', 'RoleController@create')->name('manager.role.create');
    Route::post('/store', 'RoleController@store')->name('manager.role.store');
    Route::get('/{id}/view', 'RoleController@view')->name('manager.role.view');
    Route::post('/{id}/update', 'RoleController@update')->name('manager.role.update');
    Route::post('/{id}/delete', 'RoleController@delete')->name('manager.role.delete');
});

/**
 * POSTS
 */
Route::group(['prefix' => 'post', 'namespace' => 'Post'], function () {
    Route::get('/', 'PostController@index')->name('manager.post');
    Route::get('/create', 'PostController@create')->name('manager.post.create');
    Route::post('/store', 'PostController@store')->name('manager.post.store');
    Route::get('/trashed', 'PostController@trashed')->name('manager.post.trashed');
    Route::get('/{id}/view', 'PostController@view')->name('manager.post.view');
    Route::post('/{id}/update', 'PostController@update')->name('manager.post.update');
    Route::post('/{id}/delete', 'PostController@delete')->name('manager.post.delete');
    Route::post('/{id}/delete/force', 'PostController@forceDelete')->name('manager.post.delete.force');
    Route::post('/delete/many', 'PostController@deleteMany')->name('manager.post.delete.many');

    Route::get('/pending', 'PostPending@index')->name('manager.post.pending');
});

/**
 * PLANS
 */
Route::group(['prefix' => 'plan', 'namespace' => 'Plan'], function () {
    Route::get('/', 'PlanController@index')->name('manager.plan');
    Route::post('/store', 'PlanController@store')->name('manager.plan.store');
    Route::post('/{id}/update', 'PlanController@update')->name('manager.plan.update');
    Route::get('/{id}/view', 'PlanController@view')->name('manager.plan.view');
    Route::post('/{id}/delete', 'PlanController@delete')->name('manager.plan.delete');
});

/**
 * CATEGORYS
 */
Route::group(['prefix' => 'category', 'namespace' => 'Category'], function () {
    Route::get('/', 'CategoryController@index')->name('manager.category');
    Route::get('/create', 'CategoryController@create')->name('manager.category.create');
    Route::post('/store', 'CategoryController@store')->name('manager.category.store');
    Route::get('/{id}/view', 'CategoryController@view')->name('manager.category.view');
    Route::post('/{id}/update', 'CategoryController@update')->name('manager.category.update');
    Route::post('/{id}/delete', 'CategoryController@delete')->name('manager.category.delete');
});

/**
 * USERS
 */
Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
    Route::get('/', 'UserController@index')->name('manager.user');
    Route::get('/create', 'UserController@create')->name('manager.user.create');
    Route::get('/{id}', 'UserController@edit')->name('manager.user.view');
    Route::post('/{id}/update', 'UserController@update')->name('manager.user.update');
    Route::post('/{id}/delete', 'UserController@delete')->name('manager.user.delete');
});

/**
 * CUSTOMERS
 */
Route::group(['prefix' => 'customer', 'namespace' => 'Customer'], function () {
    Route::get('/', 'CustomerController@index')->name('manager.customer');
    Route::get('/create', 'CustomerController@create')->name('manager.customer.create');
    Route::post('/store', 'CustomerController@store')->name('manager.customer.store');
    Route::get('/{id}/view', 'CustomerController@view')->name('manager.customer.view');
    Route::post('/{id}/update', 'CustomerController@update')->name('manager.customer.update');
    Route::post('/{id}/delete', 'CustomerController@delete')->name('manager.customer.delete');
    Route::get('/{id}/verify/phone', 'CustomerController@verifyPhone')->name('manager.customer.verify.phone');
    Route::get('/{id}/unverified/phone', 'CustomerController@unverifiedPhone')->name('manager.customer.unverified.phone');

    Route::get('/{id}/ban', 'CustomerController@ban')->name('manager.customer.ban');
    Route::get('/{id}/pardon', 'CustomerController@pardon')->name('manager.customer.pardon');

    Route::group(['prefix' => '{id}/order'], function () {
        Route::get('/', 'OrderController@index')->name('manager.customer.order');
        Route::post('/store', 'CustomerController@storeOrder')->name('manager.customer.order.store');
    });

    Route::post('subscription/{id}/delete', 'CustomerController@deleteSubscription')->name('manager.customer.subscription.delete');
});

/**
 * ORDERS
 */
Route::group(['prefix' => 'order', 'namespace' => 'Order'], function () {
    Route::get('/', 'OrderController@index')->name('manager.order');
    Route::get('/create', 'OrderController@create')->name('manager.order.create');
    Route::post('/store', 'OrderController@store')->name('manager.order.store');
    Route::get('/{id}/view', 'OrderController@view')->name('manager.order.view');
    Route::post('/{id}/update', 'OrderController@update')->name('manager.order.update');
    Route::post('/{id}/delete', 'OrderController@delete')->name('manager.order.delete');
});

/**
 * SETTINGS
 */
Route::group(['prefix' => 'setting'], function () {
    Route::get('/', 'SettingController@index')->name('manager.setting');
    Route::post('/update', 'SettingController@update')->name('manager.setting.update');
});
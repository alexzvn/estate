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

    Route::group(['prefix' => 'online'], function ()
    {
        Route::get('/', 'OnlineController@index')->name('manager.post.online');
        Route::get('/{id}/view', 'OnlineController@view')->name('manager.post.online.view');
        Route::get('/{id}/fetch', 'OnlineController@fetch')->name('manager.post.fetch');
        Route::get('/create', 'OnlineController@create')->name('manager.post.online.create');
        Route::post('/store', 'OnlineController@store')->name('manager.post.online.store');
        Route::get('/trashed', 'OnlineController@trashed')->name('manager.post.online.trashed');
        Route::post('/{id}/update', 'OnlineController@update')->name('manager.post.online.update');
        Route::post('/{id}/delete', 'OnlineController@delete')->name('manager.post.online.delete');
        Route::post('/{id}/delete/force', 'OnlineController@forceDelete')->name('manager.post.online.delete.force');
        Route::post('/{id}/clone/origin/save', 'OnlineController@cloneSaveOrigin')->name('manager.post.online.clone.origin.save');
        Route::post('/{id}/clone/origin/delete', 'OnlineController@cloneDeleteOrigin')->name('manager.post.online.clone.origin.delete');
        Route::post('/delete/many', 'OnlineController@deleteMany')->name('manager.post.online.delete.many');
        Route::post('/delete/many/force', 'OnlineController@forceDeleteMany')->name('manager.post.online.delete.many.force');
        Route::post('/reverse/many', 'OnlineController@reverseMany')->name('manager.post.online.reverse.many');
    });

    Route::group(['prefix' => 'fee'], function ()
    {
        Route::get('/', 'FeeController@index')->name('manager.post.fee');
        Route::get('/{id}/view', 'FeeController@view')->name('manager.post.fee.view');
        Route::get('/{id}/fetch', 'FeeController@fetch')->name('manager.post.fee.fetch');
        Route::get('/create', 'FeeController@create')->name('manager.post.fee.create');
        Route::get('/trashed', 'FeeController@trashed')->name('manager.post.fee.trashed');
        Route::post('/{id}/update', 'FeeController@update')->name('manager.post.fee.update');
        Route::post('/store', 'FeeController@store')->name('manager.post.fee.store');
        Route::post('/delete/many', 'FeeController@deleteMany')->name('manager.post.fee.delete.many');
        Route::post('/delete/many/force', 'FeeController@forceDeleteMany')->name('manager.post.fee.delete.many.force');
        Route::post('/reverse/many', 'FeeController@reverseMany')->name('manager.post.fee.reverse.many');
    });

    Route::group(['prefix' => 'market'], function ()
    {
        Route::get('/', 'MarketController@index')->name('manager.post.market');
        Route::post('/{id}/update', 'MarketController@update')->name('manager.post.market.update');
        Route::post('/store', 'MarketController@store')->name('manager.post.market.store');
        Route::get('/{id}/delete', 'MarketController@delete')->name('manager.post.market.delete');
        Route::get('/{id}/fetch', 'MarketController@fetch')->name('manager.post.market.fetch');
    });

});

/**
 * Censorship
 */
Route::group(['prefix' => 'censorship', 'namespace' => 'Censorship'], function () {
    Route::get('/', 'PostController@index')->name('manager.censorship');
    Route::post('/blacklist/add', 'PostController@addToBlacklist')->name('manager.censorship.blacklist.add');
    Route::post('/Whitelist/add', 'PostController@addToWhitelist')->name('manager.censorship.whitelist.add');
});

/**
 * REPORTS
 */
Route::group(['prefix' => 'report', 'namespace' => 'Report'], function () {
    Route::get('/', 'ReportController@index')->name('manager.report.view');
    Route::get('/{id}/delete', 'ReportController@delete')->name('manager.report.delete');
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

Route::group(['prefix' => 'note', 'namespace' => 'Note'], function ()
{
    Route::get('/user', 'NoteController@indexUser')->name('manager.note.user');
});

/**
 * CUSTOMERS
 */
Route::group(['prefix' => 'customer', 'namespace' => 'Customer'], function () {
    Route::get('/', 'CustomerController@index')->name('manager.customer');
    Route::get('/create', 'CustomerController@create')->name('manager.customer.create');
    Route::post('/store', 'CustomerController@store')->name('manager.customer.store');
    Route::post('/store/exit', 'CustomerController@storeAndExit')->name('manager.customer.store.exit');
    Route::get('/{id}/view', 'CustomerController@view')->name('manager.customer.view');
    Route::post('/{id}/update', 'CustomerController@update')->name('manager.customer.update');
    Route::post('/{id}/update/exit', 'CustomerController@updateAndExit')->name('manager.customer.update.exit');
    Route::post('/{id}/delete', 'CustomerController@delete')->name('manager.customer.delete');
    Route::get('/{id}/verify/phone', 'CustomerController@verifyPhone')->name('manager.customer.verify.phone');
    Route::get('/{id}/unverified/phone', 'CustomerController@unverifiedPhone')->name('manager.customer.unverified.phone');

    Route::get('/{id}/ban', 'CustomerController@ban')->name('manager.customer.ban');
    Route::get('/{id}/pardon', 'CustomerController@pardon')->name('manager.customer.pardon');
    Route::get('/{id}/logout', 'CustomerController@logout')->name('manager.customer.logout');
    Route::get('/{id}/take', 'CustomerController@take')->name('manager.customer.take');
    Route::get('/{id}/untake', 'CustomerController@untake')->name('manager.customer.untake');

    Route::group(['prefix' => '{id}/order'], function () {
        Route::get('/', 'OrderController@index')->name('manager.customer.order');
        Route::post('/store', 'CustomerController@storeOrder')->name('manager.customer.order.store');
    });

    Route::post('subscription/delete/many', 'SubscriptionController@deleteMany')->name('manager.customer.subscription.delete.many');
    Route::get('subscription/{id}/lock/toggle', 'SubscriptionController@lockToggle')->name('manager.customer.subscription.lock.toggle');
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
    Route::get('/{id}/activate', 'OrderController@activate')->name('manager.order.activate');
    Route::get('/{id}/verify', 'OrderController@verify')->name('manager.order.verify');
    Route::post('/{id}/delete', 'OrderController@delete')->name('manager.order.delete');
});

/**
 * BLACKLIST
 */
Route::group(['prefix' => 'blacklist'], function () {
    Route::group(['prefix' => 'phone', 'namespace' => 'Blacklist'], function () {
        Route::get('/', 'BlacklistController@index')->name('manager.blacklist.phone');
        Route::post('/store', 'BlacklistController@store')->name('manager.blacklist.phone.store');
        Route::post('/{id}/update', 'BlacklistController@update')->name('manager.blacklist.phone.update');
        Route::post('/{id}/delete', 'BlacklistController@delete')->name('manager.blacklist.phone.delete');
    });
});

/**
 * WHITELIST
 */
Route::group(['prefix' => 'whitelist'], function () {
    Route::group(['prefix' => 'phone', 'namespace' => 'Whitelist'], function () {
        Route::get('/', 'WhitelistController@index')->name('manager.whitelist.phone');
        Route::post('/store', 'WhitelistController@store')->name('manager.whitelist.phone.store');
        Route::post('/{id}/update', 'WhitelistController@update')->name('manager.whitelist.phone.update');
        Route::post('/{id}/delete', 'WhitelistController@delete')->name('manager.whitelist.phone.delete');
    });
});

/**
 * ACTIVITY
 */
Route::group(['prefix' => 'activity', 'namespace' => 'Activity'], function () {
    Route::get('/', 'ActivityController@index')->name('manager.log');
});

/**
 * AUDIT LOG
 */
Route::group(['prefix' => 'audit', 'namespace' => 'Audit'], function () {
    Route::get('/', 'AuditController@index')->name('manager.audit');
});

/**
 * SETTINGS
 */
Route::group(['prefix' => 'setting'], function () {
    Route::get('/', 'SettingController@index')->name('manager.setting');
    Route::post('/update', 'SettingController@update')->name('manager.setting.update');
});
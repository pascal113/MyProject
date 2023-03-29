<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Api (/api/v1)
Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'contact'], function () {
        Route::post('/feedback', 'Api\ContactController@postFeedback')->name('api.contact.feedback');
        Route::post('/mailing-list', 'Api\ContactController@postMailingList')->name('api.contact.mailing-list');
    });

    Route::get('/locations', 'Api\LocationController@index')->name('api.locations.index');
    Route::get('/locations/{siteNumber}', 'Api\LocationController@show')->name('api.locations.show');

    Route::get('/roles/{myRole?}', 'Api\RoleController@index')->middleware('api-trusted-clients');

    Route::get('/user/{email}/permissions', 'Api\UserController@getUserPermissions')->middleware('api-trusted-clients');
    Route::get('/user/{email}', 'Api\UserController@show')->middleware('api-trusted-clients');
    Route::post('/user/{email}', 'Api\UserController@update')->middleware('api-trusted-clients');
    Route::delete('/user/{email}', 'Api\UserController@destroy')->middleware('api-trusted-clients');
});

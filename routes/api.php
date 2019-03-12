<?php

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

Route::group([
    'as' => 'api.',
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\api'
], function () {
    Route::get('', ['as' => 'v1', 'uses' => 'ItemController@index']);

    Route::group([
        'as' => 'noticias.',
        'prefix' => 'noticias'
    ], function () {
        Route::get('', [
            'as' => 'index',
            'uses' => 'NewsController@index'
        ]);

        Route::post('', [
            'as' => 'create',
            'uses' => 'NewsController@update'
        ]);

        Route::put('', [
            'as' => 'update',
            'uses' => 'NewsController@update'
        ]);

        Route::get('{slug}', [
            'as' => 'show',
            'uses' => 'NewsController@show'
        ]);

        Route::delete('{id}', [
            'as' => 'delete',
            'uses' => 'NewsController@delete'
        ]);
    });

    Route::group([
        'as' => 'resolutions',
        'prefix' => 'resolutions'
    ], function () {
        Route::get('', [
            'as' => 'index',
            'uses' => 'ResolutionController@index'
        ]);

        Route::post('', [
            'as' => 'create',
            'uses' => 'ResolutionController@update'
        ]);
    });

    Route::group([
        'as' => 'legal_reports',
        'prefix' => 'legal_reports'
    ], function () {
        Route::get('', [
            'as' => 'index',
            'uses' => 'LegalReportsController@index'
        ]);

        Route::post('', [
            'as' => 'create',
            'uses' => 'LegalReportsController@update'
        ]);
    });

    Route::get('v1/xfind', ['as' => 'web.index', 'uses' => 'NutchController@index']);
});

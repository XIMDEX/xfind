<?php

use Illuminate\Http\Request;
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


Route::get('v1', ['as' => 'api', 'uses' => 'api\ItemController@index']);
Route::get('v1/noticias', ['as' => 'api.noticias', 'uses' => 'api\NewsController@index']);
//Route::post('v1/noticias/create', ['as' => 'api', 'uses' => 'api\NewsController@create']);
Route::post('v1/noticias', ['as' => 'api.noticias.create', 'uses' => 'api\NewsController@update']);
Route::put('v1/noticias', ['as' => 'api.noticias.update', 'uses' => 'api\NewsController@update']);
Route::get('v1/noticias/{slug}', ['as.noticias.show' => 'api', 'uses' => 'api\NewsController@show']);
Route::delete('v1/noticias/{id}', ['as.noticias.delete' => 'api', 'uses' => 'api\NewsController@delete']);

Route::get('v1/resolutions', ['as' => 'api.resolutions', 'uses' => 'api\resolutionController@index']);
Route::post('v1/resolutions', ['as' => 'api.resolutions.create', 'uses' => 'api\resolutionController@update']);

Route::get('v1/legal_reports', ['as' => 'api.legal_reports', 'uses' => 'api\LegalReportsController@index']);
Route::post('v1/legal_reports', ['as' => 'api.legal_reports.create', 'uses' => 'api\LegalReportsController@update']);

Route::get('v1/xfind', ['as' => 'api.web.index', 'uses' => 'api\NutchController@index']);

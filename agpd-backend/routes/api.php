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
Route::get('v1/noticias', ['as' => 'api', 'uses' => 'api\NewsController@index']);
//Route::post('v1/noticias/create', ['as' => 'api', 'uses' => 'api\NewsController@create']);
Route::put('v1/noticias/update', ['as' => 'api', 'uses' => 'api\NewsController@update']);
Route::get('v1/noticias/{slug}', ['as' => 'api', 'uses' => 'api\NewsController@show']);
Route::delete('v1/noticias/{id}/delete', ['as' => 'api', 'uses' => 'api\NewsController@delete']);
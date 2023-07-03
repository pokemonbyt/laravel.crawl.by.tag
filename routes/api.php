<?php

use Illuminate\Http\Request;

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


Route::namespace('Api')->prefix('v1')->group(function () {

    Route::namespace('Crawl')->group(function () {
        Route::post('crawl/run', 'CrawlController@runAll')->name("crawl.runAll");
    });
});

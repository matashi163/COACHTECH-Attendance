<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ListController;

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

Route::get('/', function () {
    return redirect('/attendance');
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/attendance'], function () {
        Route::get('/', [RecordController::class, 'viewRecord']);
        Route::group(['prefix' => '/work'], function () {
            Route::get('/start', [RecordController::class, 'workStart']);
            Route::get('/finish', [RecordController::class, 'workFinish']);
        });
        Route::group(['prefix' => '/break'], function () {
            Route::get('/start', [RecordController::class, 'breakStart']);
            Route::get('/finish', [RecordController::class, 'breakFinish']);
        });
        Route::get('/list', [ListController::class, 'viewList']);
    });
});

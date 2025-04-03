<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\AttendanceListAdminController;
use App\Http\Controllers\StaffListController;
use App\Http\Controllers\CorrectionListController;

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
Route::get('/register', [AuthController::class, 'viewRegister']);
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::get('/admin/login', [AuthController::class, 'viewLoginAdmin'])->name('admin.login');

Route::group(['middleware' => 'auth:web'], function () {
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
        Route::get('/list', [AttendanceListController::class, 'viewAttendanceList']);
    });
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::group(['prefix' => '/admin'], function () {
        Route::group(['prefix' => '/attendance'], function () {
            Route::get('/list', [AttendanceListAdminController::class, 'viewAttendanceListAdmin']);
            Route::get('/staff/{userId}', [AttendanceListController::class, 'viewAttendanceList']);
        });
        Route::get('/staff/list', [StaffListController::class, 'viewStaffList']);
    });
});

Route::group(['middleware' => 'multi_auth'], function () {
    Route::get('/attendance/{userId}', [DetailController::class, 'viewDetail']);
    Route::post('/attendance/{userId}', [DetailController::class, 'correct']);
    Route::get('/stamp_correction_request/list', [CorrectionListController::class, 'viewCorrectionList']);
});

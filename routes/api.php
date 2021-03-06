<?php

use App\Http\Controllers\API\DisbursmentController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'disbursments', 'as' => 'disbursments.', 'middleware' => ['jsonResponse']], function () {
    Route::get('/', [DisbursmentController::class, 'index'])->name('index');
    Route::post('create', [DisbursmentController::class, 'store'])->name('store');
    Route::get('{disbursmentID}', [DisbursmentController::class, 'show'])->name('show');
});
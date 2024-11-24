<?php

use App\Http\Controllers\ApiControllers\GroupController;
use App\Http\Controllers\ApiControllers\LinkController;
use App\Http\Controllers\ApiControllers\TagController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/all-tags', [TagController::class, 'getAllTags']);
    Route::get('/all-groups', [GroupController::class, 'getAllGroups']);

    Route::post('/links', [LinkController::class, 'store']);
});

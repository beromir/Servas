<?php

use App\Http\Controllers\BulkEditingController;
use App\Http\Controllers\LaterController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ViewControllers\HomeViewController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('links.index');
    })->name('home');

    Route::resource('links', LinkController::class)->except([
        'create', 'edit',
    ]);

    Route::resource('tags', TagController::class)->except([
        'create', 'edit',
    ]);

    Route::resource('groups', GroupController::class)->except([
        'create', 'edit',
    ]);

    Route::get('/all-tags', [TagController::class, 'getAllTags']);
    Route::get('/all-groups', [GroupController::class, 'getAllGroups']);

    Route::post('/bulk-edit-links', [BulkEditingController::class, 'editLinks']);

    Route::post('/search', [SearchController::class, 'search'])
        ->name('search');
});

Route::view('/swagger', 'swagger-ui')->middleware('only.local');

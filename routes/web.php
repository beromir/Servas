<?php

use App\Http\Controllers\BulkEditingController;
use App\Http\Controllers\CsrfController;
use App\Http\Controllers\DeleteUserDataController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PublicLinkController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
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
        return to_route('links.index');
    })->name('home');

    Route::resource('links', LinkController::class)->except([
        'create', 'edit',
    ]);

    Route::resource('tags', TagController::class)->except([
        'create', 'edit',
    ]);

    Route::resource('groups', GroupController::class)->except([
        'create', 'edit', 'index',
    ]);

    Route::resource('publicLinks', PublicLinkController::class)->except([
        'create', 'edit', 'update',
    ]);

    Route::get('/groups', function () {
        return to_route('links.index');
    });

    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');

    Route::get('/all-tags', [TagController::class, 'getAllTags']);
    Route::get('/all-groups', [GroupController::class, 'getAllGroups']);

    Route::post('/bulk-edit-links', [BulkEditingController::class, 'editLinks']);

    Route::post('/search', [SearchController::class, 'search'])
        ->name('search');

    Route::post('/export', [ExportController::class, 'export'])
        ->name('export');

    Route::post('/import', [ImportController::class, 'import'])
        ->name('import');

    Route::post('/delete-user-data', [DeleteUserDataController::class, 'delete'])
        ->name('delete-user-data');

    Route::get('/csrf-token', [CsrfController::class, 'getCsrfToken'])
        ->name('csrf-token');
});

Route::get('/share/{shareId}', [PublicLinkController::class, 'show'])->name('share');

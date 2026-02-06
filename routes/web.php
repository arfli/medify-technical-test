<?php

use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\MasterItemsController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index']);
Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search']);
Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView']);
Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit']);

Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView']);
Route::get('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete']);


Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData']);


// Route::get('/kategori-items', [App\Http\Controllers\CategoryItemController::class, 'index']);


// Resource route (creates all CRUD routes)
Route::resource('category-items', CategoryItemController::class);

// Additional search route
Route::get('category-items-search', [CategoryItemController::class, 'search'])->name('category-items.search');


Route::get('master-items/pdf/{kode}', [MasterItemsController::class, 'generatePdf'])->name('master-items.pdf');

// In routes/web.php
Route::get('master-items/export/excel', [MasterItemsController::class, 'exportExcel'])->name('master-items.export.excel');
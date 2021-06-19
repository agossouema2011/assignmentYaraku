<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
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

Route::middleware(['auth:sanctum', 'verified'])->group(function(){
	Route::get('/dashboard',[BooksController::class, 'index'])->name('dashboard');
	Route::post('/book',[BooksController::class, 'create']);
	Route::get('/book/{book}', [BooksController::class, 'edit']);
	Route::post('/book/{book}', [BooksController::class, 'update']);
	Route::get('/sortTitleASC', [BooksController::class, 'sortTitleASC']);
	Route::get('/sortTitleDESC', [BooksController::class, 'sortTitleDESC']);
	Route::get('/sortAuthorASC', [BooksController::class, 'sortAuthorASC']);
	Route::get('/sortAuthorDESC', [BooksController::class, 'sortAuthorDESC']);
	
	Route::get('/exportCSV/{type}', [BooksController::class, 'exportCSV']);//Parameter 'type' helps to identify what to export in CSV 
	Route::get('/exportXMLall', [BooksController::class, 'exportXMLall']);
	Route::get('/exportXMLtitles', [BooksController::class, 'exportXMLtitles']);
	Route::get('/exportXMLauthors', [BooksController::class, 'exportXMLauthors']);
	
	Route::post('/sendforsearch', [BooksController::class, 'search']);
	//Route::get('/searchResult', [BooksController::class, 'searchResult']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PostController::class, 'index'])->name('post.index');
Route::get('/trash', [PostController::class, 'trash'])->name('post.trash');

Route::post('/create', [PostController::class, 'create']);
Route::post('/check/change', [PostController::class, 'checkedChange']);
Route::post('/softdelete', [PostController::class, 'goToTrash']);
Route::post('/restore', [PostController::class, 'restore']);
Route::post('/delete', [PostController::class, 'delete']);

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

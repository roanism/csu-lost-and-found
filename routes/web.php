<?php

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

use App\Http\Controllers\PostController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;

// Home and browsing
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Posting lost/found items
Route::get('/post/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/post/store', [PostController::class, 'store'])->name('posts.store');

// Mark as claimed/returned
Route::post('/claim/{post_id}', [ClaimController::class, 'store'])->name('claims.store');

// Report inappropriate post
Route::post('/report/{post_id}', [ReportController::class, 'store'])->name('reports.store');

// Admin panel (basic, will expand later)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin actions
Route::post('/admin/posts/{id}/delete', [AdminController::class, 'deletePost'])->name('admin.posts.delete');
Route::post('/admin/posts/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.posts.status');

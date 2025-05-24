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
use App\Http\Controllers\ContactController;

// Home and browsing
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/history', [PostController::class, 'history'])->name('posts.history');
Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Contact Admin
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Posting lost/found items
Route::get('/post/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/post/store', [PostController::class, 'store'])->name('posts.store');

// Mark as claimed/returned
Route::post('/claim/{post_id}', [ClaimController::class, 'store'])->name('claims.store');

// Report inappropriate post
Route::post('/report/{post_id}', [ReportController::class, 'store'])->name('reports.store');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication routes
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    
    // Protected admin routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
        
        // Post management routes
        Route::get('posts/{id}', [AdminController::class, 'showPost'])->name('posts.show');
        Route::get('posts/{id}/edit', [AdminController::class, 'editPost'])->name('posts.edit');
        Route::put('posts/{id}', [AdminController::class, 'updatePost'])->name('posts.update');
        Route::delete('posts/{id}', [AdminController::class, 'deletePost'])->name('posts.destroy');
        Route::post('posts/{id}/approve-claim', [AdminController::class, 'approveClaim'])->name('approve-claim');
        Route::delete('posts/bulk-delete', [AdminController::class, 'bulkDelete'])->name('posts.bulk-delete');
        
        // Message management routes
        Route::get('messages', [AdminController::class, 'messages'])->name('messages');
        Route::get('messages/{id}', [AdminController::class, 'showMessage'])->name('messages.show');
        Route::post('messages/{id}/mark-read', [AdminController::class, 'markMessageAsRead'])->name('messages.mark-read');
        Route::delete('messages/{id}', [AdminController::class, 'deleteMessage'])->name('messages.destroy');
        Route::delete('messages/bulk-delete', [AdminController::class, 'bulkDeleteMessages'])->name('messages.bulk-delete');

        // Reports management routes
        Route::get('reports', [AdminController::class, 'reports'])->name('reports');
        Route::post('reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
        Route::delete('reports/{report}', [AdminController::class, 'destroyReport'])->name('reports.destroy');
        Route::delete('reports/bulk-delete', [AdminController::class, 'bulkDeleteReports'])->name('reports.bulk-delete');
    });
});

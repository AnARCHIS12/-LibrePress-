<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('front.home');
Route::get('/blog', [FrontController::class, 'blog'])->name('front.blog');
Route::get('/search', SearchController::class)->name('front.search');
Route::get('/feed.xml', [FrontController::class, 'rss'])->name('front.rss');
Route::get('/@{username}', [FrontController::class, 'activityActor'])->name('activity.actor');
Route::post('/contents/{content}/comments', [CommentController::class, 'store'])
    ->middleware('throttle:comments')
    ->name('comments.store');

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', 'admin'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('pages', PageController::class)->except(['show']);
    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);
    Route::get('comments', [CommentModerationController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/approve', [CommentModerationController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/reject', [CommentModerationController::class, 'reject'])->name('comments.reject');
    Route::delete('comments/{comment}', [CommentModerationController::class, 'destroy'])->name('comments.destroy');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('modules/{slug}/enable', [ModuleController::class, 'enable'])->name('modules.enable');
    Route::post('modules/{slug}/disable', [ModuleController::class, 'disable'])->name('modules.disable');
    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::post('themes/{slug}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
});

require __DIR__.'/auth.php';

Route::get('/{slug}', [FrontController::class, 'show'])
    ->where('slug', '^(?!admin|api|storage|login|logout|search).+')
    ->name('front.show');

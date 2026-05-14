<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\Admin\ContentPreviewController;
use App\Http\Controllers\Admin\ContentRevisionController;
use App\Http\Controllers\Admin\ContentTranslationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ActivityPubController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('front.home');
Route::get('/blog', [FrontController::class, 'blog'])->name('front.blog');
Route::get('/search', SearchController::class)->name('front.search');
Route::get('/feed.xml', [FrontController::class, 'rss'])->name('front.rss');
Route::get('/atom.xml', [FrontController::class, 'atom'])->name('front.atom');
Route::get('/sitemap.xml', [FrontController::class, 'sitemap'])->name('front.sitemap');
Route::get('/robots.txt', [FrontController::class, 'robots'])->name('front.robots');
Route::get('/.well-known/webfinger', [ActivityPubController::class, 'webfinger'])->name('activity.webfinger');
Route::get('/@{username}', [ActivityPubController::class, 'actor'])->name('activity.actor');
Route::post('/contents/{content}/comments', [CommentController::class, 'store'])
    ->middleware('throttle:comments')
    ->name('comments.store');
Route::post('/comments/{comment}/report', [CommentController::class, 'report'])
    ->middleware('throttle:comments')
    ->name('comments.report');

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', 'admin'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('pages', PageController::class)->except(['show']);
    Route::get('pages/{content}/preview', ContentPreviewController::class)->name('pages.preview');
    Route::get('pages/{content}/revisions', [ContentRevisionController::class, 'index'])->name('pages.revisions');
    Route::post('pages/{content}/revisions/{revision}/restore', [ContentRevisionController::class, 'restore'])->name('pages.revisions.restore');
    Route::post('pages/{content}/translations', [ContentTranslationController::class, 'store'])->name('pages.translations.store');
    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);
    Route::get('comments', [CommentModerationController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/approve', [CommentModerationController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/reject', [CommentModerationController::class, 'reject'])->name('comments.reject');
    Route::delete('comments/{comment}', [CommentModerationController::class, 'destroy'])->name('comments.destroy');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('backups', [BackupController::class, 'backup'])->name('backups.create');
    Route::post('exports', [BackupController::class, 'export'])->name('exports.create');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('taxonomies', [TaxonomyController::class, 'index'])->name('taxonomies.index');
    Route::post('taxonomies', [TaxonomyController::class, 'store'])->name('taxonomies.store');
    Route::post('taxonomies/{taxonomy}/terms', [TermController::class, 'store'])->name('terms.store');
    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
    Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
    Route::get('modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('modules/{slug}/enable', [ModuleController::class, 'enable'])->name('modules.enable');
    Route::post('modules/{slug}/disable', [ModuleController::class, 'disable'])->name('modules.disable');
    Route::delete('modules/{slug}', [ModuleController::class, 'uninstall'])->name('modules.uninstall');
    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('themes/{slug}/preview', [ThemeController::class, 'preview'])->name('themes.preview');
    Route::post('themes/{slug}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
});

require __DIR__.'/auth.php';

Route::get('/{slug}', [FrontController::class, 'show'])
    ->where('slug', '^(?!admin|api|storage|login|logout|search|sitemap.xml|robots.txt|atom.xml).+')
    ->name('front.show');

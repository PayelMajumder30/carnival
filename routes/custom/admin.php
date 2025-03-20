<?php
namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\PageContentController;

Route::name('admin.')->group(function() {
    // login
    Route::middleware('guest:admin', 'PreventBackHistory')->group(function() {
        Route::view('/login', 'admin.auth.login')->name('login');
        Route::post('/check', [AuthController::class, 'check'])->name('check');
    });

    // profile
    Route::middleware('auth:admin', 'PreventBackHistory')->group(function() {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

       
        // settings
        Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [ContentController::class, 'settingsUpdate'])->name('settings.update');
         // Social Media
         Route::prefix('admin-management')->group(function() {
            Route::get('/', [UserManagementController::class, 'index'])->name('user_management.list.all');
            Route::get('/create', [UserManagementController::class, 'create'])->name('user_management.create');
            Route::post('/store', [UserManagementController::class, 'store'])->name('user_management.store');
            Route::get('/edit/{id}', [UserManagementController::class, 'edit'])->name('user_management.edit');
            Route::post('/update', [UserManagementController::class, 'update'])->name('user_management.update');
            Route::get('/delete/{id}', [UserManagementController::class, 'delete'])->name('user_management.delete');
            Route::get('/permissions/{id}', [UserManagementController::class, 'Permissions'])->name('user_management.permissions');
            Route::post('/permissions/update', [UserManagementController::class, 'PermissionsUpdate'])->name('user_management.permissions.update');
        });
        Route::prefix('master-module')->group(function() {
            // Social Media
           Route::prefix('social-media')->group(function() {
               Route::get('/', [SocialMediaController::class, 'index'])->name('social_media.list.all');
               Route::get('/create', [SocialMediaController::class, 'create'])->name('social_media.create');
               Route::post('/store', [SocialMediaController::class, 'store'])->name('social_media.store');
               Route::get('/edit/{id}', [SocialMediaController::class, 'edit'])->name('social_media.edit');
               Route::post('/update', [SocialMediaController::class, 'update'])->name('social_media.update');
               Route::get('/delete/{id}', [SocialMediaController::class, 'delete'])->name('social_media.delete');
           });
           Route::prefix('blog')->group(function() {
                Route::get('/', [BlogController::class, 'index'])->name('blog.list.all');
                Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
                Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
                Route::get('/show/{id}', [BlogController::class, 'show'])->name('blog.show');
                Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
                Route::post('/update/{id}', [BlogController::class, 'update'])->name('blog.update');
                Route::get('/status/{id}', [BlogController::class, 'BlogStatus'])->name('blog.status'); 
                Route::get('/delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');
            });
            Route::prefix('partners')->group(function() {
                Route::get('/', [PartnerController::class, 'index'])->name('partners.list.all');
                Route::get('/create', [PartnerController::class, 'create'])->name('partners.create');
                Route::post('/store', [PartnerController::class, 'store'])->name('partners.store');
                Route::get('/edit/{id}', [PartnerController::class, 'edit'])->name('partners.edit');
                Route::post('/update/{id}', [PartnerController::class, 'update'])->name('partners.update');
                Route::get('/delete/{id}', [PartnerController::class, 'delete'])->name('partners.delete');
            });
        });
        // Route::resource('article', ArticleController::class);

        Route::prefix('article')->group(function() {
            Route::get('/', [ArticleController::class, 'index'])->name('article.list.all');
            Route::get('/create', [ArticleController::class, 'create'])->name('article.create');
            Route::post('/store', [ArticleController::class, 'store'])->name('article.store');
            Route::get('/show/{id}', [ArticleController::class, 'show'])->name('article.show');
            Route::get('/edit/{id}', [ArticleController::class, 'edit'])->name('article.edit');
            Route::post('/update/{id}', [ArticleController::class, 'update'])->name('article.update');
            Route::get('/status/{id}', [ArticleController::class, 'ArticleStatus'])->name('article.status'); 
            Route::get('/delete/{id}', [ArticleController::class, 'delete'])->name('article.delete');
        });

        Route::prefix('content')->group(function() {

            Route::prefix('page-content')->group(function() {
               Route::get('/', [PageContentController::class, 'PageContentIndex'])->name('page_content.list.all');
               Route::get('/create', [PageContentController::class, 'PageContentCreate'])->name('page_content.create');
               Route::post('/store',[PageContentController::class, 'PageContentStore' ])->name('page_content.store');
               Route::get('/detail/{id}', [PageContentController::class, 'PageContentDetail'])->name('page_content.detail');
               Route::get('/edit/{id}', [PageContentController::class, 'PageContentEdit'])->name('page_content.edit');
               Route::post('/update/{id}', [PageContentController::class, 'PageContentUpdate'])->name('page_content.update');
               Route::get('/delete/{id}', [PageContentController::class, 'PageContentDelete'])->name('page_content.delete');

           });
         
       });
       
    });

    // ckeditor custom upload adapter path
    Route::post('/ckeditor/upload', [UploadAdapterController::class, 'upload']);
});

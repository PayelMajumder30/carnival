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
         // user management
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
               Route::post('/update/{id}', [SocialMediaController::class, 'update'])->name('social_media.update');
               Route::get('/delete/{id}', [SocialMediaController::class, 'delete'])->name('social_media.delete');
           });
           //blogs
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
            //partners
            Route::prefix('partners')->group(function() {
                Route::get('/', [PartnerController::class, 'index'])->name('partners.list.all');
                Route::get('/create', [PartnerController::class, 'create'])->name('partners.create');
                Route::post('/store', [PartnerController::class, 'store'])->name('partners.store');
                Route::get('/edit/{id}', [PartnerController::class, 'edit'])->name('partners.edit');
                Route::post('/update/{id}', [PartnerController::class, 'update'])->name('partners.update');
                Route::get('/delete/{id}', [PartnerController::class, 'delete'])->name('partners.delete');
            });
            //banners
            Route::prefix('banner')->group(function() {
                Route::get('/', [BannerController::class, 'index'])->name('banner.list.all');
                Route::get('/create', [BannerController::class, 'create'])->name('banner.create');
                Route::post('/store', [BannerController::class, 'store'])->name('banner.store');
                Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
                Route::post('/update/{id}', [BannerController::class, 'update'])->name('banner.update');
                Route::get('/delete/{id}', [BannerController::class, 'delete'])->name('banner.delete');
            });
            //why choose us
            Route::prefix('whychooseus')->group(function() {
                Route::get('/', [WhyChooseController::class, 'index'])->name('whychooseus.list.all');
                Route::get('/create', [WhyChooseController::class, 'create'])->name('whychooseus.create');
                Route::post('/store', [WhyChooseController::class, 'store'])->name('whychooseus.store');
                Route::get('/status/{id}', [WhyChooseController::class, 'status'])->name('whychooseus.status');
                Route::get('/edit/{id}', [WhyChooseController::class, 'edit'])->name('whychooseus.edit');
                Route::post('/update/{id}', [WhyChooseController::class, 'update'])->name('whychooseus.update');
                Route::get('/delete/{id}', [WhyChooseController::class, 'delete'])->name('whychooseus.delete');
                Route::post('/sort', [WhyChooseController::class, 'sort'])->name('whychooseus.sort');
            });

            //trip categories
            Route::prefix('tripcategory')->group(function() {
                Route::get('/', [TripcategoryController::class, 'index'])->name('tripcategory.list.all');
                Route::get('/create', [TripcategoryController::class, 'create'])->name('tripcategory.create');
                Route::post('/store', [TripcategoryController::class, 'store'])->name('tripcategory.store');
                Route::get('/edit/{id}', [TripcategoryController::class, 'edit'])->name('tripcategory.edit');
                Route::post('/update/{id}', [TripcategoryController::class, 'update'])->name('tripcategory.update');
                Route::get('/status/{id}', [TripcategoryController::class, 'status'])->name('tripcategory.status'); 
                Route::get('/delete/{id}', [TripcategoryController::class, 'delete'])->name('tripcategory.delete');
                Route::post('/sort', [TripcategoryController::class, 'sort'])->name('tripcategory.sort');

                //trip category banner
                Route::get('/banner/{trip_cat_id}', [TripcategoryController::class, 'bannerIndex'])->name('tripcategorybanner.list.all');
                Route::get('banner/create/{trip_cat_id}', [TripcategoryController::class, 'bannerCreate'])->name('tripcategory.bannercreate');
                Route::post('banner/store', [TripcategoryController::class, 'bannerStore'])->name('tripcategory.bannerstore');
                Route::get('banner/edit/{id}', [TripcategoryController::class, 'bannerEdit'])->name('tripcategory.banneredit');
                Route::post('banner/update', [TripcategoryController::class, 'bannerUpdate'])->name('tripcategory.bannerupdate');
                Route::get('banner/status/{id}', [TripcategoryController::class, 'bannerStatus'])->name('tripcategory.bannerstatus'); 
                Route::get('banner/delete/{id}', [TripcategoryController::class, 'bannerDelete'])->name('tripcategory.bannerdelete');
            });

            //trip category banner
            // Route::prefix('tripcategorybanner')->group(function() {
            //     Route::get('/', [TripcategoryController::class, 'index'])->name('tripcategorybanner.list.all');
            //     Route::get('/create', [TripcategoryController::class, 'create'])->name('tripcategorybanner.create');
            //     Route::post('/store', [TripcategoryController::class, 'store'])->name('tripcategorybanner.store');
            //     Route::get('/edit/{id}', [TripcategoryController::class, 'edit'])->name('tripcategorybanner.edit');
            //     Route::post('/update/{id}', [TripcategoryController::class, 'update'])->name('tripcategorybanner.update');
            //     Route::get('/status/{id}', [TripcategoryController::class, 'status'])->name('tripcategorybanner.status'); 
            //     Route::get('/delete/{id}', [TripcategoryController::class, 'delete'])->name('tripcategorybanner.delete');
            // });

             //offer list
             Route::prefix('offers')->group(function() {
                Route::get('/', [OfferController::class, 'index'])->name('offers.list.all');
                Route::get('/create', [OfferController::class, 'create'])->name('offers.create');
                Route::post('/store', [OfferController::class, 'store'])->name('offers.store');
                Route::get('/edit/{id}', [OfferController::class, 'edit'])->name('offers.edit');
                Route::post('/update', [OfferController::class, 'update'])->name('offers.update');
                Route::get('/status/{id}', [OfferController::class, 'status'])->name('offers.status'); 
                Route::get('/delete/{id}', [OfferController::class, 'delete'])->name('offers.delete');
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

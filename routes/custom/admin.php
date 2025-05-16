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
        Route::get('/password/edit',[AuthController::class, 'passwordEdit'])->name('dashboard.changePassword');
        Route::post('password/update',[AuthController::class, 'passwordUpdate'])->name('dashboard.updatePassword');

        Route::get('/profile-edit',[AuthController::class, 'profileEdit'])->name('dashboard.edit');
        Route::post('/profile-update',[AuthController::class, 'profileUpdate'])->name('dashboard.update');

       
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
               Route::post('/delete', [SocialMediaController::class, 'delete'])->name('social_media.delete');
           });
           //blogs
            Route::prefix('blog')->group(function() {
                Route::get('/', [BlogController::class, 'index'])->name('blog.list.all');
                Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
                Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
                Route::get('/show/{id}', [BlogController::class, 'show'])->name('blog.show');
                Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
                Route::post('/update', [BlogController::class, 'update'])->name('blog.update');
                Route::get('/status/{id}', [BlogController::class, 'BlogStatus'])->name('blog.status'); 
                Route::post('/delete', [BlogController::class, 'delete'])->name('blog.delete');
            });
            //partners
            Route::prefix('partners')->group(function() {
                Route::get('/', [PartnerController::class, 'index'])->name('partners.list.all');
                Route::get('/create', [PartnerController::class, 'create'])->name('partners.create');
                Route::post('/store', [PartnerController::class, 'store'])->name('partners.store');
                Route::get('/edit/{id}', [PartnerController::class, 'edit'])->name('partners.edit');
                Route::post('/update/{id}', [PartnerController::class, 'update'])->name('partners.update');
                Route::post('/delete', [PartnerController::class, 'delete'])->name('partners.delete');
            });
            //banners
            Route::prefix('banner')->group(function() {
                Route::get('/', [BannerController::class, 'index'])->name('banner.list.all');
                Route::get('/create', [BannerController::class, 'create'])->name('banner.create');
                Route::post('/store', [BannerController::class, 'store'])->name('banner.store');
                Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
                Route::post('/update/{id}', [BannerController::class, 'update'])->name('banner.update');
                Route::post('/delete', [BannerController::class, 'delete'])->name('banner.delete');
            });
            //why choose us
            Route::prefix('whychooseus')->group(function() {
                Route::get('/', [WhyChooseController::class, 'index'])->name('whychooseus.list.all');
                Route::get('/create', [WhyChooseController::class, 'create'])->name('whychooseus.create');
                Route::post('/store', [WhyChooseController::class, 'store'])->name('whychooseus.store');
                Route::get('/edit/{id}', [WhyChooseController::class, 'edit'])->name('whychooseus.edit');
                Route::post('/update/{id}', [WhyChooseController::class, 'update'])->name('whychooseus.update');
                Route::get('/status/{id}', [WhyChooseController::class, 'status'])->name('whychooseus.status');
                Route::post('/delete', [WhyChooseController::class, 'delete'])->name('whychooseus.delete');
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
                //Route::get('/isHighlight/{id}', [TripcategoryController::class, 'isHighlight'])->name('tripcategory.isHighlight');
                Route::post('/highlight/update', [TripcategoryController::class, 'updateHighlights'])->name('tripcategory.updateHighlights');
                Route::post('/delete', [TripcategoryController::class, 'delete'])->name('tripcategory.delete');
                Route::post('/sort', [TripcategoryController::class, 'sort'])->name('tripcategory.sort');

                //trip category banner
                Route::get('/banner/{trip_cat_id}', [TripcategoryController::class, 'bannerIndex'])->name('tripcategorybanner.list.all');
                Route::get('banner/create/{trip_cat_id}', [TripcategoryController::class, 'bannerCreate'])->name('tripcategory.bannerCreate');
                Route::post('banner/store', [TripcategoryController::class, 'bannerStore'])->name('tripcategory.bannerStore');
                Route::get('banner/edit/{id}', [TripcategoryController::class, 'bannerEdit'])->name('tripcategory.bannerEdit');
                Route::post('banner/update', [TripcategoryController::class, 'bannerUpdate'])->name('tripcategory.bannerUpdate');
                Route::get('banner/status/{id}', [TripcategoryController::class, 'bannerStatus'])->name('tripcategory.bannerStatus'); 
                Route::post('banner/delete', [TripcategoryController::class, 'bannerDelete'])->name('tripcategory.bannerDelete');

                //trip category destination
                Route::get('/destination/{trip_cat_id}', [TripcategoryController::class, 'destinationIndex'])->name('tripcategorydestination.list.all');
                Route::get('destination/by-country/{country_id}/{trip_cat_id}', [TripcategoryController::class, 'getDestinationsByCountry'])->name('tripcategorydestination.getDestination');
                Route::post('destination/add', [TripcategoryController::class, 'destinationAdd'])->name('tripcategorydestination.destinationAdd');
                Route::post('destination/update-price', [TripcategoryController::class, 'updatePrice'])->name('tripcategory.updatePrice');
                Route::post('destination/delete', [TripcategoryController::class, 'destinationDelete'])->name('tripcategory.destinationDelete');

                //trip category activities
                Route::get('/activities/{trip_cat_id}', [TripcategoryController::class, 'activitiesIndex'])->name('tripcategoryactivities.list.all');
                Route::get('activities/by-destination/{country_id}/{trip_cat_id}', [TripcategoryController::class, 'getActivitiesByDestination'])->name('tripcategorydestination.getActivities');
                Route::post('activities/add', [TripcategoryController::class, 'activityAdd'])->name('tripcategorydestination.activityAdd');
                Route::post('activities/update', [TripcategoryController::class, 'updateActivities'])->name('tripcategory.updateActivities');
                Route::get('activities/status/{id}',[TripcategoryController::class, 'activitiesStatus'])->name('tripcategory.activitiesStatus');
                Route::post('activities/delete', [TripcategoryController::class, 'activitiesDelete'])->name('tripcategory.activitiesDelete');
            });

            //offer list
            Route::prefix('offers')->group(function() {
                Route::get('/', [OfferController::class, 'index'])->name('offers.list.all');
                Route::get('/create', [OfferController::class, 'create'])->name('offers.create');
                Route::post('/store', [OfferController::class, 'store'])->name('offers.store');
                Route::get('/edit/{id}', [OfferController::class, 'edit'])->name('offers.edit');
                Route::post('/update', [OfferController::class, 'update'])->name('offers.update');
                Route::get('/status/{id}', [OfferController::class, 'status'])->name('offers.status'); 
                Route::post('/delete', [OfferController::class, 'delete'])->name('offers.delete');
            });

            //Master modeule/destination
            Route::prefix('country/destinations')->group(function(){
                Route::get('/',[DestinationController::class, 'index'])->name('destination.list.all');
                Route::get('/country_show',[DestinationController::class, 'show'])->name('destination.show');
                Route::post('/country/add',[DestinationController::class, 'countryAdd'])->name('country.add');
                Route::get('/country/status/{id}', [DestinationController::class, 'countryStatus'])->name('country.status'); 
                Route::post('/destination/add',[DestinationController::class, 'destinationAdd'])->name('destination.add'); 
                Route::post('/destination/create-image', [DestinationController::class, 'createDestImage'])->name('destination.createImage'); 
                Route::get('/destination/status/{id}', [DestinationController::class, 'destinationStatus'])->name('destination.status');
                Route::get('/destination/package-category/{id}', [DestinationController::class, 'packageCategoryIndex'])->name('country/destinations.packageCategory');
                Route::get('/destination/package-category/create/{id}', [DestinationController::class, 'packageCategoryCreate'])->name('country/destinations.packageCategoryCreate');
                Route::post('/destination/package-category/store', [DestinationController::class, 'packageCategoryStore'])->name('country/destinations.packageCategoryStore');
                Route::post('/destination/package-category/update', [DestinationController::class, 'packageCategoryUpdate'])->name('country/destinations.packageCategoryUpdate');
                Route::post('/delete', [DestinationController::class, 'destinationDelete'])->name('destination.delete');
                
            });

             //Master modeule/support
            Route::prefix('support')->group(function(){
                Route::get('/',[SupportController::class, 'index'])->name('support.list.all');
                Route::get('/create',[SupportController::class, 'create'])->name('support.create');
                Route::post('/store',[SupportController::class, 'store'])->name('support.store');
                Route::get('/edit/{id}', [SupportController::class, 'edit'])->name('support.edit');
                Route::post('/update', [SupportController::class, 'update'])->name('support.update');
                Route::get('/status/{id}', [SupportController::class, 'status'])->name('support.status');               
                Route::post('/delete',[SupportController::class, 'delete'])->name('support.delete');
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

        //Itenaries
        Route::prefix('itinaries')->group(function() {

            //upcoming events  
            Route::prefix('upcoming-events')->group(function() {
                Route::get('/', [itenariesController::class, 'index'])->name('upcomingevents.list.all');
                
            });

            //itenary list
            Route::prefix('itinary-list')->group(function() {
                Route::get('/', [ItenaryListController::class, 'index'])->name('itenaries.list.all');
                Route::get('/create', [ItenaryListController::class, 'create'])->name('itenaries.create');
                Route::post('/store', [ItenaryListController::class, 'store'])->name('itenaries.store');
                Route::get('/edit/{id}', [ItenaryListController::class, 'edit'])->name('itenaries.edit');
                Route::post('/update/{id}', [ItenaryListController::class, 'update'])->name('itenaries.update');
                Route::get('/status/{id}', [ItenaryListController::class, 'toggleStatus'])->name('itenaries.status');
                Route::delete('/delete/{id}', [ItenaryListController::class, 'delete'])->name('itenaries.delete');
            });

        });
        
    });

    // ckeditor custom upload adapter path
    Route::post('/ckeditor/upload', [UploadAdapterController::class, 'upload']);
});

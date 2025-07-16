<?php
namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\PageContentController;

Route::prefix('admin/')->name('admin.')->group(function() {
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

            // Master module/Tags
            Route::prefix('tags')->group(function(){
                Route::get('/',[TagController::class, 'index'])->name('tag.list.all');
                Route::get('/create',[TagController::class, 'create'])->name('tag.create');
                Route::post('/store',[TagController::class, 'store'])->name('tag.store');
                Route::post('/update',[TagController::class, 'update'])->name('tag.update');
                Route::get('status/{id}',[TagController::class, 'status'])->name('tag.status');
                Route::post('delete',[TagController::class, 'delete'])->name('tag.delete');
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
               Route::get('/status/{id}', [PageContentController::class, 'PageContentStatus'])->name('page_content.status');
               Route::get('/delete/{id}', [PageContentController::class, 'PageContentDelete'])->name('page_content.delete');
            });
        });

        //itineraries
        Route::prefix('itineraries')->group(function() {

            //popular packages  
            Route::prefix('popular-packages')->group(function() {
                Route::get('/', [PopularpackagesController::class, 'index'])->name('popularpackages.list.all');
                Route::get('/destinations/{id}/get-itineraries', [PopularpackagesController::class, 'fetchItineraries'])->name('popularpackages.fetch');
                Route::post('/assign-itineraries', [PopularpackagesController::class, 'storeAssign'])->name('popularpackages.assign');
                Route::post('/update-status', [PopularpackagesController::class, 'updateStatus'])->name('popularpackages.updateStatus');
                Route::post('/popular-itinerary-delete',[PopularpackagesController::class, 'delete'])->name('popularpackages.delete');
                Route::post('/assign-tags', [PopularpackagesController::class, 'assignTags'])->name('popularpackages.assign.tags');


            });

            //itenary list
            Route::prefix('itinerary-list')->group(function() {
                Route::get('/', [ItenaryListController::class, 'index'])->name('itineraries.list.all');
                Route::get('/builder/{id}/{tab}', [ItenaryListController::class, 'ItineraryBuilder'])->name('itinerary.builder');
                Route::get('/create', [ItenaryListController::class, 'create'])->name('itineraries.create');
                Route::post('/itinerary/save-highlight', [ItenaryListController::class, 'SaveHighlight'])->name('itinerary.save-highlight');
                Route::post('/itinerary/delete-highlight', [ItenaryListController::class, 'DeleteHighlight'])->name('itinerary.delete-highlight');
                Route::post('/activity/create', [ItenaryListController::class, 'ActivityCreate'])->name('itinerary.activity.create');
                Route::post('/activity/delete', [ItenaryListController::class, 'ActivityDelete'])->name('itinerary.activity.delete');
                Route::get('/fetch-cabs/{division_id}', [ItenaryListController::class, 'FetchCabs'])->name('itinerary.fetch.cabs');
                Route::post('/store-cab', [ItenaryListController::class, 'storeCab'])->name('itinerary.store.cab');
                Route::post('/cab/delete', [ItenaryListController::class, 'cabDelete'])->name('itinerary.cab.delete');

                Route::get('/get-itineraries-from-crm', [ItenaryListController::class, 'get_itineraries_from_crm'])->name('itineraries.get_itineraries_from_crm');
                Route::post('/store', [ItenaryListController::class, 'store'])->name('itineraries.store');
                Route::get('/edit/{id}', [ItenaryListController::class, 'edit'])->name('itineraries.edit');
                Route::post('/update/{id}', [ItenaryListController::class, 'update'])->name('itineraries.update');
                Route::get('/status/{id}', [ItenaryListController::class, 'toggleStatus'])->name('itineraries.status');
                Route::post('/delete/{id}', [ItenaryListController::class, 'delete'])->name('itineraries.delete');

                Route::post('/assign-tag', [ItenaryListController::class, 'assignTagToItenary'])->name('itineraries.assignTagToItenary');

                //itineararies/ assign destination & package category
                Route::post('/assign-itinerary', [ItenaryListController::class, 'assignedItinerary'])->name('itineraries.assignedItinerary');
                Route::post('/toggle-package-status', [ItenaryListController::class, 'togglePackageStatus'])->name('itineraries.togglePackageStatus');
                Route::post('/package-itinerary-delete',[ItenaryListController::class, 'packageItineraryDelete'])->name('itineraries.packageItineraryDelete');

                //itineraries/ gallery for selecting multiple images
                Route::get('/galleries/{itinerary_id}', [ItenaryListController::class, 'galleryIndex'])->name('itineraries.galleries.list');
                Route::get('galleries/create/{itinerary_id}', [ItenaryListController::class, 'galleryCreate'])->name('itineraries.galleryCreate');
                Route::post('galleries/store', [ItenaryListController::class, 'galleryStore'])->name('itineraries.galleryStore');
                Route::get('galleries/edit/{id}', [ItenaryListController::class, 'galleryEdit'])->name('itineraries.galleryEdit');
                Route::post('galleries/update', [ItenaryListController::class, 'galleryUpdate'])->name('itineraries.galleryUpdate');
                Route::post('galleries/delete', [ItenaryListController::class, 'galleryDelete'])->name('itineraries.galleryDelete');
            });

            //Master modeule/destination
            Route::prefix('destinations')->group(function(){
                Route::get('/',[DestinationController::class, 'index'])->name('destination.list.all');
                Route::get('/fetch-data-from-crm',[DestinationController::class, 'FetchDataFromCRM'])->name('destination.fetch-data-from-crm');
                Route::get('/country_show',[DestinationController::class, 'show'])->name('destination.show');
                Route::post('/country/add',[DestinationController::class, 'countryAdd'])->name('country.add');
                Route::get('/country/status/{id}', [DestinationController::class, 'countryStatus'])->name('country.status'); 
                Route::post('/destination/add',[DestinationController::class, 'destinationAdd'])->name('destination.add'); 
                Route::post('/destination/create-image', [DestinationController::class, 'createDestImage'])->name('destination.createImage'); 
                Route::get('/destination/status/{id}', [DestinationController::class, 'destinationStatus'])->name('destination.status');
                Route::post('/delete', [DestinationController::class, 'destinationDelete'])->name('destination.delete');

                // Routes of itineraries associated with destinations
                Route::get('/{destiation_id}/itineraries', [DestinationController::class, 'destinationItineraryIndex'])->name('destination.itineraryList');
                Route::post('/{destiation_id}/assign-itinerary', [DestinationController::class, 'assignItineraryToDestination'])->name('destination.assignItinerary');
                Route::post('/delete-itinerary', [DestinationController::class, 'deleteItinerary'])->name('destination.deleteItinerary');    
                
                //About destination
                Route::get('/about-destination/{destination_id}', [DestinationController::class, 'aboutDestiIndex'])->name('destination.aboutDestination.list');
                Route::get('about-destination/create/{destination_id}', [DestinationController::class, 'aboutDestiCreate'])->name('destination.aboutDestiCreate');
                Route::post('about-destination/store', [DestinationController::class, 'aboutDestiStore'])->name('destination.aboutDestiStore');
                Route::get('about-destination/edit/{id}', [DestinationController::class, 'aboutDestiEdit'])->name('destination.aboutDestiEdit');
                Route::post('about-destination/update', [DestinationController::class, 'aboutDestiUpdate'])->name('destination.aboutDestiUpdate');
                Route::post('about-destination/delete', [DestinationController::class, 'aboutDestiDelete'])->name('destination.aboutDestiDelete');
            });
            
            //Master Module/ Packages from top cities
            Route::prefix('packages-from-top-cities')->group(function(){
                Route::get('/', [PackageFromCityController::class, 'index'])->name('assignCitytoPackage.index');
                Route::post('/store', [PackageFromCityController::class, 'store'])->name('assignCitytoPackage.store');
                Route::get('/get-available-cities', [PackageFromCityController::class, 'getAvailableCities'])->name('assignCitytoPackage.getAvailableCities');
                Route::get('/status/{id}', [PackageFromCityController::class, 'status'])->name('assignCitytoPackage.status'); 
                Route::post('/delete', [PackageFromCityController::class, 'delete'])->name('assignCitytoPackage.delete');
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
            //Master modeule/package categoryPackageController
            Route::prefix('package-category')->group(function() {
                Route::get('/', [PackageController::class, 'index'])->name('packageCategory.list.all');
                Route::get('/create', [PackageController::class, 'create'])->name('packageCategory.create');
                Route::post('/store', [PackageController::class, 'store'])->name('packageCategory.store');
                Route::post('/update', [PackageController::class, 'update'])->name('packageCategory.update');   
                Route::get('/status/{id}', [PackageController::class, 'status'])->name('packageCategory.status');
                Route::post('/delete', [PackageController::class, 'delete'])->name('packageCategory.delete');

            });

        });
        
    });

    // ckeditor custom upload adapter path
    Route::post('/ckeditor/upload', [UploadAdapterController::class, 'upload']);
});

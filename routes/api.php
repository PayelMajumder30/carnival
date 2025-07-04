<?php

namespace App\Http\Controllers\Api;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiController;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/



    //master module /Blog

    Route::prefix('blogs')->group(function (){

        Route::get('/',[ApiController::class, 'blogIndex']);

        Route::get('/{slug}',[ApiController::class, 'blogShow']);

    });



    //master module/ social media

    Route::prefix('social-media')->group(function () {

        Route::get('/', [ApiController::class, 'socialmediaIndex']);

        Route::get('/{id}', [ApiController::class, 'socialmediaShow']);

    });



    //master module/ partner

    Route::prefix('partners')->group(function (){

        Route::get('/', [ApiController::class, 'partnerIndex']);

        Route::get('/{id}', [ApiController::class, 'partnerShow']);

    });



    //master module/ Banner

    Route::prefix('pageBanner')->group(function () {

        Route::get('/', [ApiController::class, 'pageBannerIndex']);

        Route::get('/{id}', [ApiController::class, 'pageBannerShow']);

    });



    //master module / why choose us

    Route::prefix('why-choose-us')->group(function () {

        Route::get('/', [ApiController::class, 'whyChooseUsIndex']);

        Route::get('/{id}', [ApiController::class, 'whyChooseUsShow']);

    });



    //master module/ trip category

    Route::prefix('trip-category')->group(function () {

        Route::get('/', [ApiController::class, 'tripIndex']);

        Route::get('/{id}', [ApiController::class, 'tripShow']);

        //Route::get('/{trip_cat_id}/destinations', [ApiController::class, 'getDestinationsByTripCategory']);

    });

     Route::get('tripcategory/{slug}', [ApiController::class, 'trip_category_wise_destination']);

    



    //Offers

    Route::prefix('offers')->group(function () {

        Route::get('/', [ApiController::class, 'offerIndex']);

        Route::get('/{id}', [ApiController::class, 'offerShow']);

    });



    //website settings 

    Route::prefix('website-settings')->group(function (){

        Route::get('/', [ApiController::class, 'settingIndex']);

        Route::get('/{id}',[ApiController::class, 'settingShow']);

    });



    //Page content

    Route::prefix('pageContent')->group(function (){

        Route::get('/', [ApiController::class, 'contentIndex']);

        Route::get('/{id}',[ApiController::class, 'contentShow']);

    });



    //Itineraries / itinerary list

    Route::prefix('itineraries')->group(function (){

        Route::get('/destination/{destination_slug}/packages-itineraries', [ApiController::class, 'getDestinationPackagesWithItineraries']);

        Route::get('/destination/{package_from_city_slug}/packages-from-city', [ApiController::class, 'getDestinationPackagesFromCity']);

        //Itineraries//galley

        Route::get('/gallery', [ApiController::class, 'itinerariesWithGallery']);

        Route::get('/gallery/{id}', [ApiController::class, 'itinerariesWithGalleryByid']);

    });



    //Detail page of 

    Route::get('/destination/details/{itinerary_slug}', [ApiController::class, 'getDestinationDetails']);

    //Route::get('/destination/details/{destination_id}', [ApiController::class, 'getDestinationDetails']);



     //search by keyword (home page)

    Route::get('/search-destinations', [ApiController::class, 'search']);

   



    //for lead genrate

    Route::post('/lead-generate', [ApiController::class, 'leadStore']);

    //for newsletter
    Route::post('/news-letter', [ApiController::class, 'newsletter']);






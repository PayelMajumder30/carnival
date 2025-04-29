<?php
// namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
//     // return view('welcome');
//     return view('front.index');
// });

Auth::routes();
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return "Cache cleared successfully!";
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Front
// Route::name('front.')->group(function() {
//     Route::prefix('user')->name('user.')->group(function() {
//         // Route::middleware('guest:web', 'PreventBackHistory')->group(function() {
//         //     // Route::view('/login', 'front.auth.login')->name('login');
//         //     // Route::view('/register', 'front.auth.register')->name('register');
//         //     Route::get('/login', [AuthController::class, 'login'])->name('login');
//         //     Route::get('/register', [AuthController::class, 'register'])->name('register');
//         //     Route::post('/create', [AuthController::class, 'create'])->name('create');
//         //     Route::post('/check', [AuthController::class, 'check'])->name('check');
//         //     // Route::post('/check/mobile', [AuthController::class, 'checkMobile'])->name('check.mobile');
//         // });

//         // Route::middleware('auth:web', 'PreventBackHistory')->group(function() {
//         //     // profile
//         //     Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
//         //     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//         //     Route::view('/profile/edit', 'front.profile.edit')->name('profile.edit');
//         //     Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

//         //     // password
//         //     Route::view('/password/edit', 'front.profile.password')->name('password.edit');
//         //     Route::post('/password/update', [ProfileController::class, 'update'])->name('password.update');

//         //     // order
//         //     Route::get('/order', [ProfileController::class, 'order'])->name('order');
//         //     Route::get('/order/{id}', [ProfileController::class, 'orderDetail'])->name('order.detail');

//         //     // address
//         //     Route::get('/address', [ProfileController::class, 'address'])->name('address');
//         //     Route::post('/address/store', [ProfileController::class, 'addressStore'])->name('address.store');
//         //     Route::get('/address/edit/{id}', [ProfileController::class, 'addressedit'])->name('address.edit');
//         //     Route::get('/address/update/{id}', [ProfileController::class, 'addressUpdate'])->name('address.update');
//         //     Route::get('/address/delete/{id}', [ProfileController::class, 'addressdelete'])->name('address.delete');

//         //     Route::get('/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
//         // });
//     });

//     Route::get('/', [IndexController::class, 'index'])->name('home');
//     Route::get('/wace',[IndexController::class, 'wace'])->name('wace');
//     Route::get('/academics', [IndexController::class, 'academics'])->name('academic.index');
//     Route::get('/founder-message', [IndexController::class, 'founderMessage'])->name('founder-message.index');
//     Route::post('/admission-form', [IndexController::class, 'AdmissionFormSubmit'])->name('admission.form.submit');
//     Route::post('/send-otp', [IndexController::class, 'TIGSendOTP'])->name('admission.send.otp');
//     Route::post('/check-otp', [IndexController::class, 'TIGCheckOTP'])->name('admission.check.otp');
//     Route::get('/extra-curricular', [IndexController::class, 'extra_curricular'])->name('extra_curricular.index');
//     Route::get('/faculty', [IndexController::class, 'faculty'])->name('faculty.index');
//     Route::get('/facility', [IndexController::class, 'facility'])->name('facility.index');
//     Route::get('/schedule', [IndexController::class, 'schedule'])->name('schedule.index');
//     Route::get('/thank-you', [IndexController::class, 'thankyou'])->name('thankyou');
//     Route::get('/facility/{slug}', [IndexController::class, 'facilityBySlug'])->name('facility.details');

//     Route::prefix('epc')->name('epc.')->group(function() {
//         Route::get('/', [ContentController::class, 'epc'])->name('index');
//     });
//     // event
//     Route::prefix('event')->group(function() {
//         Route::get('', [ContentController::class, 'EventData'])->name('event.list');
//         Route::get('/{slug}', [ContentController::class, 'EventBySlug'])->name('event.details');
//     });
//     // about
//     Route::prefix('about')->name('about.')->group(function() {
//         Route::get('/', [ContentController::class, 'about'])->name('index');
//         Route::get('/email', [ContentController::class, 'send'])->name('email.send');
//     });

//     // about
//     Route::prefix('career')->name('career.')->group(function() {
//         Route::get('/', [ContentController::class, 'career'])->name('index');
//         Route::get('/confirmation', [ContentController::class, 'confirmation'])->name('confirmation');
//         Route::get('/{slug}', [ContentController::class, 'CareerApplicationForm'])->name('application.form');
//         Route::get('/register/email/verification', [ContentController::class, 'RegisterEmailVerification'])->name('register.email.verification');
//         Route::post('/register/application/submit', [ContentController::class, 'RegisterFinalSubmit'])->name('application.form.submit');
        
//     });

//     Route::get('/products/{slug}', [ContentController::class, 'WireRodIndex'])->name('wireindex');
//     Route::get('category/{cat}/{slug}', [ContentController::class, 'ProductDetails'])->name('wire.product.details');

//     // contact
//     Route::prefix('contact')->name('contact.')->group(function() {
//         Route::get('/', [ContentController::class, 'contact'])->name('index');
//         Route::post('/enquiry', [ContentController::class, 'contactEnquiry'])->name('enquiry');
//     });

//     // category
//     Route::prefix('category')->name('category.')->group(function() {
//         Route::get('{slug}', [CategoryController::class, 'level1detail'])->name('level1.detail');
//         Route::get('{level1slug}/{level2slug}', [CategoryController::class, 'level2detail'])->name('level2.detail');
//         Route::get('{level1slug}/{level2slug}/{level3slug}', [CategoryController::class, 'level3detail'])->name('level3.detail');
//     });

//     // product
//     Route::prefix('product')->name('product.')->group(function() {
//     // Route::name('product.')->group(function() {
//         Route::get('{slug}', [ProductController::class, 'detail'])->name('detail');
//         Route::post('/enquiry', [ProductController::class, 'enquiry'])->name('enquiry');
//     });

//     // blog
//     Route::prefix('blog')->group(function() {
//         Route::get('/', [BlogController::class, 'index'])->name('blog.index');
//         Route::get('{slug}', [BlogController::class, 'detail'])->name('blog.detail');
//     });

//     // service
//     Route::prefix('service')->name('service.')->group(function() {
//         Route::get('/', [ServiceController::class, 'index'])->name('index');
//         Route::get('{slug}', [ServiceController::class, 'detail'])->name('detail');
//     });

//     // search
//     Route::prefix('search')->name('search')->group(function() {
//         Route::post('/', [SearchController::class, 'index']);
//     });
//     Route::prefix('client')->group(function(){
//         Route::get('/', [ContentController::class, 'clientList'])->name('client.list');
//     });
//     Route::prefix('certificate')->group(function(){
//         Route::get('/', [ContentController::class, 'CertificateList'])->name('certificate.list');
//     });

//       // glossary
//     Route::prefix('glossary')->name('glossary.')->group(function() {
//         Route::get('/', [GlossaryController::class, 'index'])->name('index');
//     });
// });

// Admin
Route::prefix('admin')->group(function() {
    require 'custom/admin.php';
});
// Route::get('/{slug}', [IndexController::class, 'dynamicPage'])->name('dynamicPage');

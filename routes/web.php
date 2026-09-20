<?php

use App\Http\Controllers\Admin\AdminAttractionController;
use App\Http\Controllers\Admin\AdminBusinessController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Owner\OwnerBusinessController;
use App\Http\Controllers\Owner\OwnerEventController;
use App\Http\Controllers\Owner\OwnerReviewController;
use App\Http\Controllers\Owner\OwnerProfileController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/attractions', [App\Http\Controllers\AttractionController::class, 'publicIndex'])->name('attractions.index');
Route::get('/attractions/{attraction}', [App\Http\Controllers\AttractionController::class, 'show'])->name('attractions.show');
Route::get('/explore', [HomeController::class, 'exploreRedirect'])->name('explore.redirect');
Route::get('/events', [EventController::class, 'publicIndex'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');

Route::middleware('auth')->group(function () {
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/my-profile', [ProfileController::class, 'show'])->name('my_profile.show');
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::post('/recommendations/preferences', [RecommendationController::class, 'updatePreferences'])->name('recommendations.preferences.update');
});


Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::get('/profile', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::resource('users', AdminUserController::class)->only(['index', 'destroy'])->names('admin.users');
        Route::get('businesses/{business}', [AdminBusinessController::class, 'show'])->name('admin.businesses.show');
        Route::resource('businesses', AdminBusinessController::class)->only(['index', 'destroy'])->names('admin.businesses');
        Route::post('businesses/{business}/approve', [AdminBusinessController::class, 'approve'])->name('admin.businesses.approve');
        Route::post('businesses/{business}/reject', [AdminBusinessController::class, 'reject'])->name('admin.businesses.reject');

        Route::resource('attractions', AdminAttractionController::class)->names('admin.attractions');


        Route::resource('events', AdminEventController::class)->names('admin.events');
        Route::post('events/{event}/approve', [AdminEventController::class, 'approve'])->name('admin.events.approve');
        Route::post('events/{event}/reject', [AdminEventController::class, 'reject'])->name('admin.events.reject');

        Route::resource('reviews', AdminReviewController::class)->only(['index', 'destroy'])->names('admin.reviews');
        Route::get('recommendations', [App\Http\Controllers\Admin\AdminRecommendationController::class, 'index'])->name('admin.recommendations.index');
    });

    Route::prefix('owner')->middleware('role:business_owner')->group(function () {
        Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
        Route::get('profile', [OwnerProfileController::class, 'edit'])->name('owner.profile.edit');
        Route::put('profile', [OwnerProfileController::class, 'update'])->name('owner.profile.update');
        Route::get('reviews', [OwnerReviewController::class, 'index'])->name('owner.reviews.index');
        Route::get('/business', [OwnerBusinessController::class, 'show'])->name('owner.business.show');
        Route::get('/business/edit', [OwnerBusinessController::class, 'edit'])->name('owner.business.edit');
        Route::put('/business/update', [OwnerBusinessController::class, 'update'])->name('owner.business.update');
        Route::resource('events', OwnerEventController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('owner.events');
    });
});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Api\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\FaqController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\PageSectionController;
use App\Http\Controllers\Api\Admin\SeoSettingController;
use App\Http\Controllers\Api\Admin\SiteSettingController;
use App\Http\Controllers\Api\Admin\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — no authentication required
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('auth/login', [AuthController::class, 'login']);

    // Public content (consumed by Lovable frontend)
    Route::get('homepage',              [PublicContentController::class, 'homepage']);
    Route::get('seo/{pageKey}',         [PublicContentController::class, 'pageSeo']);
    Route::get('testimonials',          [PublicContentController::class, 'testimonials']);
    Route::get('faqs',                  [PublicContentController::class, 'faqs']);
    Route::get('settings',              [PublicContentController::class, 'settings']);

    // Public form submissions
    Route::post('bookings',             [BookingController::class, 'store']);
    Route::post('pre-intake',           [BookingController::class, 'storePreIntake']);
    Route::post('contact',              [ContactController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes — requires valid Sanctum token
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        /*
        |----------------------------------------------------------------------
        | Admin Routes — requires auth + is_admin
        |----------------------------------------------------------------------
        */
        Route::middleware('admin')->prefix('admin')->group(function () {

            // Dashboard stats
            Route::get('dashboard/stats', [DashboardController::class, 'stats']);

            // Content — Page Sections
            Route::get('sections',             [PageSectionController::class, 'index']);
            Route::post('sections',            [PageSectionController::class, 'store']);
            Route::get('sections/{pageSection}', [PageSectionController::class, 'show']);
            Route::put('sections/{pageSection}', [PageSectionController::class, 'update']);
            Route::delete('sections/{pageSection}', [PageSectionController::class, 'destroy']);
            Route::post('sections/reorder',    [PageSectionController::class, 'reorder']);

            // SEO Settings
            Route::get('seo',             [SeoSettingController::class, 'index']);
            Route::put('seo/{pageKey}',   [SeoSettingController::class, 'upsert']);

            // Site Settings
            Route::get('settings',        [SiteSettingController::class, 'index']);
            Route::post('settings',       [SiteSettingController::class, 'upsert']);

            // Testimonials
            Route::get('testimonials',                   [TestimonialController::class, 'index']);
            Route::post('testimonials',                  [TestimonialController::class, 'store']);
            Route::put('testimonials/{testimonial}',     [TestimonialController::class, 'update']);
            Route::delete('testimonials/{testimonial}',  [TestimonialController::class, 'destroy']);
            Route::post('testimonials/reorder',          [TestimonialController::class, 'reorder']);

            // Bookings
            Route::get('bookings',                       [AdminBookingController::class, 'index']);
            Route::get('bookings/{booking}',             [AdminBookingController::class, 'show']);
            Route::patch('bookings/{booking}/status',    [AdminBookingController::class, 'updateStatus']);
            Route::delete('bookings/{booking}',          [AdminBookingController::class, 'destroy']);

            // Pre-intake responses
            Route::get('pre-intake',                                        [AdminBookingController::class, 'preIntakeIndex']);
            Route::get('pre-intake/{preIntakeResponse}',                    [AdminBookingController::class, 'preIntakeShow']);
            Route::patch('pre-intake/{preIntakeResponse}/status',           [AdminBookingController::class, 'preIntakeUpdateStatus']);

            // Contact submissions
            Route::get('contacts',                                          [AdminContactController::class, 'index']);
            Route::get('contacts/{contactSubmission}',                      [AdminContactController::class, 'show']);
            Route::patch('contacts/{contactSubmission}/status',             [AdminContactController::class, 'updateStatus']);
            Route::delete('contacts/{contactSubmission}',                   [AdminContactController::class, 'destroy']);

            // Contact notes
            Route::post('contacts/{contactSubmission}/notes',               [AdminContactController::class, 'storeNote']);
            Route::delete('contacts/{contactSubmission}/notes/{contactNote}', [AdminContactController::class, 'destroyNote']);

            // FAQs
            Route::get('faqs',              [FaqController::class, 'index']);
            Route::post('faqs',             [FaqController::class, 'store']);
            Route::put('faqs/{faq}',        [FaqController::class, 'update']);
            Route::delete('faqs/{faq}',     [FaqController::class, 'destroy']);
            Route::post('faqs/reorder',     [FaqController::class, 'reorder']);

            // Media
            Route::get('media',             [MediaController::class, 'index']);
            Route::post('media/upload',     [MediaController::class, 'upload']);
            Route::delete('media',          [MediaController::class, 'destroy']);
        });
    });
});

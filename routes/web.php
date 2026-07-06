<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ContactWebController;
use App\Http\Controllers\BookingSubmitController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminPageSectionController;
use App\Http\Controllers\Admin\AdminSeoController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminSiteSettingController;
use App\Http\Controllers\Admin\AdminEmailSettingController;
use App\Http\Controllers\Admin\AdminAvailabilityController;
use App\Http\Controllers\Admin\AdminGoogleCalendarController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\AdminUiTranslationController;
use App\Http\Controllers\BookingAvailabilityApiController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Locale redirect — root "/" redirects to detected locale
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $locale = app()->getLocale();
    return redirect("/{$locale}");
})->name('root');

/*
|--------------------------------------------------------------------------
| Locale switch endpoint
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function (string $locale) {
    $supported = config('app.supported_locales', ['en', 'nl']);
    if (!in_array($locale, $supported, true)) {
        abort(404);
    }
    session()->put('locale', $locale);
    app()->setLocale($locale);

    // Redirect back to the referring page with the new locale
    $referer = request()->header('referer');
    if ($referer) {
        $path = ltrim(parse_url($referer, PHP_URL_PATH) ?? '/', '/');
        $segments = explode('/', $path);
        if (in_array($segments[0] ?? '', $supported, true)) {
            $segments[0] = $locale;
        } else {
            array_unshift($segments, $locale);
        }
        return redirect('/' . implode('/', $segments));
    }

    return redirect("/{$locale}");
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Public site routes (locale-prefixed)
|--------------------------------------------------------------------------
*/

Route::prefix('{locale}')
    ->where(['locale' => 'en|nl'])
    ->group(function () {
        Route::get('/', [FrontendController::class, 'home'])->name('home');
        Route::get('/about', [FrontendController::class, 'about'])->name('about');
        Route::get('/trauma-approach', [FrontendController::class, 'approach'])->name('approach');
        Route::get('/clinical-training', [FrontendController::class, 'training'])->name('training');
        Route::get('/testimonials', [FrontendController::class, 'testimonials'])->name('testimonials');
        Route::get('/fees-process', [FrontendController::class, 'fees'])->name('fees');
        Route::get('/faq', [FrontendController::class, 'faq'])->name('faq');
        Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
        Route::get('/booking', [FrontendController::class, 'booking'])->name('booking');

        // Contact form POST (web)
        Route::post('/contact', [ContactWebController::class, 'submit'])->name('contact.submit');

        // Booking submission (public)
        Route::post('/booking', [BookingSubmitController::class, 'store'])->name('booking.store');
    });

// Booking availability API (public, no auth needed)
Route::get('/api/availability/slots', [BookingAvailabilityApiController::class, 'slots'])->name('api.availability.slots');
Route::get('/api/availability/schedule', [BookingAvailabilityApiController::class, 'schedule'])->name('api.availability.schedule');

// Dynamic XML sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Temporary debug route for translation diagnostics
Route::get('/_debug_translations', function () {
    $results = [];

    // 1. Check if ui_translations table exists and has nav records
    try {
        $navRecords = \App\Models\UiTranslation::where('group', 'nav')->get();
        $results['db_nav_count'] = $navRecords->count();
    } catch (\Throwable $e) {
        $results['db_error'] = $e->getMessage();
    }

    // 2. Check container binding
    $results['container_loader_class'] = get_class(app('translation.loader'));

    // 3. Check translator's internal loader
    try {
        $translator = app('translator');
        $translatorClass = get_class($translator);
        $results['translator_class'] = $translatorClass;

        $ref = new \ReflectionProperty($translator, 'loader');
        $ref->setAccessible(true);
        $internalLoader = $ref->getValue($translator);
        $results['translator_internal_loader_class'] = get_class($internalLoader);

        // Check internal loaded cache
        $loadedRef = new \ReflectionProperty($translator, 'loaded');
        $loadedRef->setAccessible(true);
        $loaded = $loadedRef->getValue($translator);
        $results['translator_loaded_keys'] = array_keys($loaded);
    } catch (\Throwable $e) {
        $results['translator_error'] = $e->getMessage();
    }

    // 4. Test DB loader manually
    try {
        $dbLoader = new \App\Translation\DatabaseTranslationLoader(
            app('files'),
            app()['path.lang']
        );
        $nlNav = $dbLoader->load('nl', 'nav');
        $results['manual_db_loader_nl_nav'] = $nlNav;
    } catch (\Throwable $e) {
        $results['manual_db_loader_error'] = $e->getMessage();
    }

    // 5. Install DB loader manually and test
    try {
        $t = app('translator');
        $results['current_locale'] = app()->getLocale();
        $dbLoader = new \App\Translation\DatabaseTranslationLoader(app('files'), app()['path.lang']);

        // Direct test of what the loader returns
        $loaderResult = $dbLoader->load('nl', 'nav');
        $results['loader_returns_fees'] = $loaderResult['fees'] ?? 'NOT FOUND';

        // Set loader and clear cache
        $lRef = new \ReflectionProperty($t, 'loader');
        $lRef->setAccessible(true);
        $lRef->setValue($t, $dbLoader);
        $loadedRef = new \ReflectionProperty($t, 'loaded');
        $loadedRef->setAccessible(true);
        $loadedRef->setValue($t, []);

        // Now test __() - this should trigger the loader
        $results['after_swap_nav_fees'] = __('ui.nav.fees');

        // Check what got cached
        $loaded2 = $loadedRef->getValue($t);
        if (isset($loaded2['ui']['nav']['nl']['fees'])) {
            $results['cached_fees'] = $loaded2['ui']['nav']['nl']['fees'];
        } else {
            $results['cached_structure'] = [];
            foreach ($loaded2 as $ns => $groups) {
                foreach ($groups as $g => $locales) {
                    $results['cached_structure'][] = "$ns.$g." . implode(',', array_keys($locales));
                }
            }
        }
    } catch (\Throwable $e) {
        $results['after_swap_error'] = $e->getMessage() . ' at line ' . $e->getLine() . ': ' . $e->getTraceAsString();
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

/*
|--------------------------------------------------------------------------
| Admin authentication routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Forgot / Reset password (guest)
    Route::get('/forgot-password', [AdminForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [AdminForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AdminForgotPasswordController::class, 'reset'])->name('password.reset.submit');
});

/*
|--------------------------------------------------------------------------
| Admin panel routes (session-authenticated)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.web'])->group(function () {

    // Smart redirect: all users → dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard (all admin roles)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Bookings (all admin roles)
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::patch('/bookings/{booking}/schedule', [AdminBookingController::class, 'schedule'])->name('bookings.schedule');
    Route::patch('/bookings/{booking}/meeting-link', [AdminBookingController::class, 'updateMeetingLink'])->name('bookings.meeting-link');
    Route::post('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/bookings/bulk-delete', [AdminBookingController::class, 'bulkDelete'])->name('bookings.bulkDelete');

    // Site Settings (all admin roles)
    Route::get('/settings', [AdminSiteSettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');

    // Email & Notifications Settings (all admin roles)
    Route::get('/email-settings', [AdminEmailSettingController::class, 'index'])->name('email-settings.index');
    Route::patch('/email-settings', [AdminEmailSettingController::class, 'update'])->name('email-settings.update');
    Route::post('/email-settings/test', [AdminEmailSettingController::class, 'sendTest'])->name('email-settings.test');

    // Availability (all admin roles)
    Route::get('/availability', [AdminAvailabilityController::class, 'index'])->name('availability.index');
    Route::patch('/availability/config', [AdminAvailabilityController::class, 'updateConfig'])->name('availability.config');
    Route::patch('/availability/schedule', [AdminAvailabilityController::class, 'updateSchedule'])->name('availability.schedule');
    Route::post('/availability/blocked', [AdminAvailabilityController::class, 'storeBlockedDate'])->name('availability.blocked.store');
    Route::delete('/availability/blocked/{blockedDate}', [AdminAvailabilityController::class, 'destroyBlockedDate'])->name('availability.blocked.destroy');

    // Notifications AJAX (all admin roles)
    Route::get('/notifications/recent', [AdminNotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('/notifications/mark-read', [AdminNotificationController::class, 'markRead'])->name('notifications.markRead');

    // Google Calendar (all admin roles)
    Route::get('/google-calendar', [AdminGoogleCalendarController::class, 'index'])->name('google-calendar.index');
    Route::get('/google-calendar/connect', [AdminGoogleCalendarController::class, 'connect'])->name('google-calendar.connect');
    Route::get('/google-calendar/callback', [AdminGoogleCalendarController::class, 'callback'])->name('google-calendar.callback');
    Route::post('/google-calendar/disconnect', [AdminGoogleCalendarController::class, 'disconnect'])->name('google-calendar.disconnect');
    Route::patch('/google-calendar/settings', [AdminGoogleCalendarController::class, 'updateSettings'])->name('google-calendar.settings');
    Route::post('/google-calendar/credentials', [AdminGoogleCalendarController::class, 'saveCredentials'])->name('google-calendar.credentials');
    Route::post('/google-calendar/test-sync', [AdminGoogleCalendarController::class, 'testSync'])->name('google-calendar.test-sync');

    // Change Password (all admin roles)
    Route::get('/password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::patch('/password', [AdminPasswordController::class, 'update'])->name('password.update');

    // Profile (all admin roles)
    Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');

    // ── Super admin only routes ──
    Route::middleware('superadmin')->group(function () {

        // Contacts
        Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::patch('/contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.status');
        Route::post('/contacts/{contact}/notes', [AdminContactController::class, 'addNote'])->name('contacts.notes.store');

        // Testimonials
        Route::resource('testimonials', AdminTestimonialController::class);

        // FAQs
        Route::resource('faqs', AdminFaqController::class);

        // Page sections
        Route::get('/pages', [AdminPageSectionController::class, 'pages'])->name('pages.index');
        Route::get('/pages/{page}/sections', [AdminPageSectionController::class, 'index'])->name('sections.index');
        Route::get('/sections/{section}/edit', [AdminPageSectionController::class, 'edit'])->name('sections.edit');
        Route::patch('/sections/{section}', [AdminPageSectionController::class, 'update'])->name('sections.update');

        // SEO settings
        Route::get('/seo', [AdminSeoController::class, 'index'])->name('seo.index');
        Route::get('/seo/{pageKey}/edit', [AdminSeoController::class, 'edit'])->name('seo.edit');
        Route::patch('/seo/{pageKey}', [AdminSeoController::class, 'update'])->name('seo.update');

        // Media
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('/media/upload', [AdminMediaController::class, 'upload'])->name('media.upload');
        Route::post('/media/bulk-delete', [AdminMediaController::class, 'bulkDestroy'])->name('media.bulkDelete');
        Route::get('/media/{filename}/details', [AdminMediaController::class, 'details'])->name('media.details');
        Route::delete('/media/{filename}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

        // UI Translations
        Route::get('/ui-translations', [AdminUiTranslationController::class, 'index'])->name('ui-translations.index');
        Route::get('/ui-translations/{group}/edit', [AdminUiTranslationController::class, 'edit'])->name('ui-translations.edit');
        Route::patch('/ui-translations/{group}', [AdminUiTranslationController::class, 'update'])->name('ui-translations.update');
    });
});

/*
|--------------------------------------------------------------------------
| Catch-all fallback — redirect unprefixed URLs to /{locale}/...
| e.g. /trauma-approach → /en/trauma-approach
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    $path = request()->path();

    // Don't redirect API, admin, or asset paths
    if (preg_match('#^(api|admin|lang|sitemap\.xml)#', $path)) {
        abort(404);
    }

    // Detect locale from session or default
    $locale = session('locale', config('app.locale', 'en'));
    $supported = config('app.supported_locales', ['en', 'nl']);
    if (!in_array($locale, $supported, true)) {
        $locale = 'en';
    }

    // Redirect to locale-prefixed path
    return redirect("/{$locale}/{$path}", 301);
});

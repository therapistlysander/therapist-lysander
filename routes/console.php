<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Google Calendar Cache Warming
|--------------------------------------------------------------------------
|
| Warm the Google Calendar busy slots cache every 5 minutes so that
| availability checks remain fast for visitors.
|
*/
Schedule::job(new \App\Jobs\WarmCalendarCacheJob())
    ->everyFiveMinutes()
    ->when(fn () => \App\Models\GoogleCalendarToken::where('is_active', true)->exists());

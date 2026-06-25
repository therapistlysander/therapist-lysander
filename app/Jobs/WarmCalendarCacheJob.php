<?php

namespace App\Jobs;

use App\Models\GoogleCalendarToken;
use App\Models\SiteSetting;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WarmCalendarCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(GoogleCalendarService $calendar): void
    {
        $token = GoogleCalendarToken::where('is_active', true)->first();

        if (!$token) {
            return;
        }

        $tzSetting = SiteSetting::where('key', 'timezone')->first();
        $timezone = $tzSetting?->value ?: config('app.timezone', 'Europe/Amsterdam');
        $cacheTtl = config('google-calendar.cache_ttl', 300);

        $start = Carbon::now($timezone)->startOfDay();
        $end = $start->copy()->addDays(60);

        try {
            $busySlots = $calendar->getBusySlots($start, $end, $token->calendar_id);

            // Cache per-date for efficient per-request lookups
            $slotsByDate = [];
            foreach ($busySlots as $slot) {
                $dateKey = $slot['start_dt']->toDateString();
                $slotsByDate[$dateKey][] = $slot;
            }

            foreach ($slotsByDate as $date => $slots) {
                Cache::put("gcal_busy_{$date}", $slots, now()->addSeconds($cacheTtl));
            }

            // Also cache the full range for bulk lookups
            Cache::put('google_calendar_busy_slots', $busySlots, now()->addSeconds($cacheTtl));

            $token->update(['last_synced_at' => now()]);

            Log::info('GoogleCalendar: Cache warmed successfully', [
                'dates_cached' => count($slotsByDate),
                'total_slots'  => count($busySlots),
            ]);
        } catch (\Throwable $e) {
            Log::warning("GoogleCalendar: Failed to warm cache: {$e->getMessage()}");
        }
    }
}

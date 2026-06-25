<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Calendar Integration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Calendar synchronization. When enabled, confirmed
    | bookings will automatically create Google Calendar events, and Google
    | Calendar busy times will be checked for slot availability.
    |
    */

    'enabled' => (bool) env('GOOGLE_CALENDAR_ENABLED', false),

    'client_id' => env('GOOGLE_CLIENT_ID'),

    'client_secret' => env('GOOGLE_CLIENT_SECRET'),

    'redirect_uri' => env('GOOGLE_CALENDAR_REDIRECT_URI'),

    'default_calendar_id' => env('GOOGLE_CALENDAR_ID', 'primary'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | How long to cache Google Calendar busy slot data per date. Prevents
    | excessive API calls while keeping availability data reasonably fresh.
    |
    */

    'cache_ttl' => (int) env('GOOGLE_CALENDAR_CACHE_TTL', 300),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Number of retry attempts for transient Google API failures, and the
    | base delay in seconds for exponential backoff between retries.
    |
    */

    'retry_attempts' => 3,

    'retry_delay' => 2,

];

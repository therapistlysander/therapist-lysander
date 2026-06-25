<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\GoogleCalendarToken;
use App\Services\GoogleCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncBookingToCalendarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job (exponential backoff).
     */
    public array $backoff = [10, 60, 300];

    /**
     * Create a new job instance.
     *
     * @param Booking $booking The booking to sync.
     * @param string $action One of: create, update, delete.
     */
    public function __construct(
        public Booking $booking,
        public string $action = 'create',
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GoogleCalendarService $calendar): void
    {
        $token = GoogleCalendarToken::where('is_active', true)->first();

        if (!$token) {
            Log::info("GoogleCalendar: No active connection, skipping sync for booking #{$this->booking->id}");
            return;
        }

        try {
            match ($this->action) {
                'create' => $this->createEvent($calendar, $token),
                'update' => $this->updateEvent($calendar, $token),
                'delete' => $this->deleteEvent($calendar, $token),
                default  => Log::warning("GoogleCalendar: Unknown action '{$this->action}' for booking #{$this->booking->id}"),
            };

            $token->update(['last_synced_at' => now(), 'last_error' => null]);

            Log::info("GoogleCalendar: Successfully {$this->action}d event for booking #{$this->booking->id}");
        } catch (\Throwable $e) {
            Log::error("GoogleCalendar: Failed to {$this->action} event for booking #{$this->booking->id}: {$e->getMessage()}", [
                'booking_id' => $this->booking->id,
                'action'     => $this->action,
                'exception'  => $e->getMessage(),
            ]);

            throw $e; // Re-throw so the queue can retry
        }
    }

    /**
     * Handle a job failure (after all retries exhausted).
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("GoogleCalendar: Job permanently failed for booking #{$this->booking->id}: {$exception->getMessage()}");

        $token = GoogleCalendarToken::where('is_active', true)->first();
        $token?->update(['last_error' => 'Sync failed: ' . $exception->getMessage()]);
    }

    // ─── Private helpers ─────────────────────────────────────────────────

    private function createEvent(GoogleCalendarService $calendar, GoogleCalendarToken $token): void
    {
        // Skip if already has a Google event ID (avoid duplicates)
        if ($this->booking->google_event_id) {
            $this->updateEvent($calendar, $token);
            return;
        }

        $eventId = $calendar->createEvent($this->booking, $token->calendar_id);

        $this->booking->update(['google_event_id' => $eventId]);
    }

    private function updateEvent(GoogleCalendarService $calendar, GoogleCalendarToken $token): void
    {
        if (!$this->booking->google_event_id) {
            // No existing event — create one instead
            $this->createEvent($calendar, $token);
            return;
        }

        $calendar->updateEvent($this->booking->google_event_id, $this->booking, $token->calendar_id);
    }

    private function deleteEvent(GoogleCalendarService $calendar, GoogleCalendarToken $token): void
    {
        if (!$this->booking->google_event_id) {
            Log::info("GoogleCalendar: No event ID to delete for booking #{$this->booking->id}");
            return;
        }

        $calendar->deleteEvent($this->booking->google_event_id, $token->calendar_id);

        $this->booking->update(['google_event_id' => null]);
    }
}

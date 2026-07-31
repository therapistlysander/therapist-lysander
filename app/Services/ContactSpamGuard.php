<?php

namespace App\Services;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Centralised anti-spam checks for the public contact form.
 *
 * Layers (in order of evaluation):
 *  1. Honeypot          — hidden "website" field real visitors never fill
 *  2. User-Agent        — every genuine browser sends one
 *  3. Time trap         — encrypted render timestamp; submissions faster than
 *                         a human can type are rejected
 *  4. Built-in captcha   — localized arithmetic check with an encrypted
 *                         answer token (skipped when Turnstile is active)
 *  5. Rate limiting     — max submissions per IP per hour
 *  6. Cloudflare Turnstile — server-side token verification (only when
 *                         TURNSTILE_SECRET_KEY is configured)
 *
 * Blocks are either "silent" (bot receives a fake success so it learns
 * nothing) or "visible" (genuine user may be affected, show a clear error).
 */
class ContactSpamGuard
{
    /** Reasons hidden from the sender — the request gets a fake success. */
    private const SILENT_REASONS = [
        'honeypot',
        'missing_user_agent',
        'invalid_form_token',
        'time_trap',
        'duplicate',
    ];

    /** Minimum seconds between form render and submit. */
    private const MIN_FILL_SECONDS = 3;

    /** Lifetime of an issued captcha challenge. */
    private const CAPTCHA_TTL_HOURS = 2;

    /** Rate limit: max submissions per IP within the decay window. */
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 3600;

    /**
     * Encrypted timestamp embedded in the form when it is rendered.
     * Bots cannot forge it, and it powers the time-trap check.
     */
    public function issueFormToken(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }

    /**
     * Random arithmetic challenge for the built-in captcha. The answer
     * travels back inside an encrypted, expiring token so no session or
     * database state is needed.
     */
    public function issueCaptcha(): array
    {
        $a = random_int(2, 9);
        $b = random_int(1, 9);

        return [
            'a'     => $a,
            'b'     => $b,
            'token' => Crypt::encryptString(json_encode([
                'answer'  => $a + $b,
                'expires' => now()->addHours(self::CAPTCHA_TTL_HOURS)->timestamp,
            ])),
        ];
    }

    /**
     * Inspect a submission. Returns a block reason string, or null when the
     * request looks legitimate. $requireFormToken is false for API clients
     * that never rendered the Blade form.
     */
    public function inspect(Request $request, bool $requireFormToken = true): ?string
    {
        // 1. Honeypot — hidden field genuine browsers leave empty
        if (filled($request->input('website'))) {
            return $this->block($request, 'honeypot');
        }

        // 2. Every real browser sends a User-Agent header
        if (blank($request->userAgent())) {
            return $this->block($request, 'missing_user_agent');
        }

        // 3. Time trap via the encrypted form token
        if ($requireFormToken && ($reason = $this->checkFormToken($request))) {
            return $this->block($request, $reason);
        }

        // 4. Built-in captcha (web form only; Turnstile supersedes it)
        if ($requireFormToken && ($reason = $this->checkCaptcha($request))) {
            return $this->block($request, $reason);
        }

        // 5. Per-IP rate limit
        if (RateLimiter::tooManyAttempts($this->rateLimitKey($request), self::MAX_ATTEMPTS)) {
            return $this->block($request, 'rate_limited');
        }

        // 6. Cloudflare Turnstile — enforced only when keys are configured
        if (config('services.turnstile.secret_key') && ! $this->passesTurnstile($request)) {
            return $this->block($request, 'turnstile_failed');
        }

        return null;
    }

    /**
     * True when an identical submission (same email + message) already exists
     * within the last hour — repeat delivery adds no value and doubles email.
     */
    public function isDuplicate(Request $request): bool
    {
        $duplicate = ContactSubmission::query()
            ->where('email', (string) $request->input('email'))
            ->where('message', (string) $request->input('message'))
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if ($duplicate) {
            $this->block($request, 'duplicate');
        }

        return $duplicate;
    }

    /** Count an accepted submission against the sender's rate limit. */
    public function recordSubmission(Request $request): void
    {
        RateLimiter::hit($this->rateLimitKey($request), self::DECAY_SECONDS);
    }

    /** Silent blocks return a fake success so bots cannot adapt. */
    public function isSilentBlock(string $reason): bool
    {
        return in_array($reason, self::SILENT_REASONS, true);
    }

    private function checkFormToken(Request $request): ?string
    {
        $token = $request->input('form_token');

        if (blank($token)) {
            return 'invalid_form_token';
        }

        try {
            $renderedAt = (int) Crypt::decryptString($token);
        } catch (\Throwable) {
            return 'invalid_form_token';
        }

        if (now()->timestamp - $renderedAt < self::MIN_FILL_SECONDS) {
            return 'time_trap';
        }

        return null;
    }

    private function checkCaptcha(Request $request): ?string
    {
        // Turnstile replaces the built-in captcha when configured
        if (config('services.turnstile.secret_key')) {
            return null;
        }

        $token  = $request->input('captcha_token');
        $answer = $request->input('captcha_answer');

        if (blank($token) || blank($answer)) {
            return 'captcha_failed';
        }

        try {
            $challenge = json_decode(Crypt::decryptString($token), true);
        } catch (\Throwable) {
            return 'captcha_failed';
        }

        if (! is_array($challenge) || now()->timestamp > ($challenge['expires'] ?? 0)) {
            return 'captcha_failed';
        }

        if ((int) trim((string) $answer) !== (int) ($challenge['answer'] ?? -1)) {
            return 'captcha_failed';
        }

        return null;
    }

    private function passesTurnstile(Request $request): bool
    {
        $token = $request->input('cf-turnstile-response');

        if (blank($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            return $response->json('success') === true;
        } catch (\Throwable $e) {
            // Fail open: never lock genuine users out because Cloudflare is
            // unreachable — the remaining layers still apply.
            Log::error('ContactSpamGuard: Turnstile verification unavailable', [
                'error' => $e->getMessage(),
            ]);

            return true;
        }
    }

    private function rateLimitKey(Request $request): string
    {
        return 'contact-form:'.$request->ip();
    }

    /** Log the block (no personal message content) and pass the reason through. */
    private function block(Request $request, string $reason): string
    {
        Log::warning('Contact form submission blocked', [
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            'path' => $request->path(),
        ]);

        return $reason;
    }
}

<?php

namespace Tests\Feature;

use App\Services\ContactSpamGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ContactSpamProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('contact-form:127.0.0.1');
    }

    /** A form token old enough to pass the time trap. */
    private function humanFormToken(): string
    {
        return Crypt::encryptString((string) now()->subSeconds(30)->timestamp);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'I would like to book an introduction call.',
            'website' => '',
            'form_token' => $this->humanFormToken(),
        ], $overrides);
    }

    public function test_valid_submission_is_stored_and_shows_success(): void
    {
        $response = $this->post('/en/contact', $this->validPayload());

        $response->assertRedirect('/en/contact');
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'jane@example.com',
            'source' => 'web_form',
        ]);
    }

    public function test_honeypot_filled_blocks_silently(): void
    {
        $response = $this->post('/en/contact', $this->validPayload([
            'website' => 'https://spam.example',
        ]));

        // Bot sees a normal success, but nothing is stored
        $response->assertRedirect('/en/contact');
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_missing_form_token_blocks_silently(): void
    {
        $payload = $this->validPayload();
        unset($payload['form_token']);

        $response = $this->post('/en/contact', $payload);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_forged_form_token_blocks_silently(): void
    {
        $response = $this->post('/en/contact', $this->validPayload([
            'form_token' => 'not-a-real-token',
        ]));

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_too_fast_submission_blocks_silently(): void
    {
        $response = $this->post('/en/contact', $this->validPayload([
            'form_token' => app(ContactSpamGuard::class)->issueFormToken(),
        ]));

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_missing_user_agent_blocks_silently(): void
    {
        $response = $this->post('/en/contact', $this->validPayload(), ['User-Agent' => '']);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_rate_limit_blocks_sixth_submission_with_visible_error(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->post('/en/contact', $this->validPayload([
                'email' => "visitor{$i}@example.com",
                'message' => "Unique message number {$i} for testing.",
            ]))->assertSessionHas('success');
        }

        $response = $this->post('/en/contact', $this->validPayload([
            'email' => 'visitor6@example.com',
            'message' => 'Sixth message should be rate limited.',
        ]));

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_submissions', 5);
    }

    public function test_duplicate_submission_is_not_stored_twice(): void
    {
        $this->post('/en/contact', $this->validPayload());
        $response = $this->post('/en/contact', $this->validPayload());

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public function test_dutch_form_works_the_same(): void
    {
        $response = $this->post('/nl/contact', $this->validPayload([
            'email' => 'jan@voorbeeld.nl',
            'message' => 'Ik wil graag een kennismakingsgesprek boeken.',
        ]));

        $response->assertRedirect('/nl/contact');
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public function test_api_contact_still_accepts_valid_submissions(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name' => 'API Client',
            'email' => 'api@example.com',
            'message' => 'Submitted through the JSON API.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public function test_api_contact_honeypot_blocks_silently(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name' => 'API Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam through the JSON API.',
            'website' => 'https://spam.example',
        ]);

        // Fake success, nothing stored
        $response->assertStatus(201);
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_api_contact_rate_limit_returns_429(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->postJson('/api/v1/contact', [
                'name' => 'API Client',
                'email' => "api{$i}@example.com",
                'message' => "Unique API message number {$i}.",
            ])->assertStatus(201);
        }

        $response = $this->postJson('/api/v1/contact', [
            'name' => 'API Client',
            'email' => 'api6@example.com',
            'message' => 'Sixth API message should be rate limited.',
        ]);

        $response->assertStatus(429);
        $this->assertDatabaseCount('contact_submissions', 5);
    }

    public function test_contact_page_renders_honeypot_and_form_token(): void
    {
        $response = $this->get('/en/contact');

        $response->assertOk();
        $response->assertSee('name="form_token"', false);
        $response->assertSee('name="website"', false);
    }

    public function test_booking_submission_remains_unaffected(): void
    {
        // The spam guard is scoped to the contact form; the booking page
        // must keep rendering normally
        $this->get('/en/booking')->assertOk();
    }
}

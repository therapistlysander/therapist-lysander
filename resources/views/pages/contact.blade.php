@extends('layouts.app')

@section('title', __('ui.page_title.contact'))
@section('meta_description', 'Get in touch with Lysander Verschuur, MSc. — psychologist and trauma therapist. Online sessions worldwide. Free and without commitment.')
@section('canonical', 'https://www.therapistlysander.com/contact-me/')

@php
  $hero    = $sections['contact_hero'] ?? null;
  $info    = $sections['contact_info'] ?? null;
  $booking = $sections['contact_booking'] ?? null;
@endphp

@section('page_styles')
<style>
  .whatsapp-cta {
    background: linear-gradient(135deg, #075e54, #128c7e);
    border-radius: var(--radius-md);
    padding: var(--space-6);
    color: white;
    text-align: center;
    margin-bottom: var(--space-6);
  }
  .whatsapp-cta h3 { color: white; margin-bottom: var(--space-3); }
  .whatsapp-cta p { color: rgba(255,255,255,0.8); font-size: var(--size-sm); margin: 0 auto var(--space-4); }

  .booking-cta-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: var(--space-6);
    text-align: center;
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow-md);
  }
  .booking-cta-card h3 { font-size: var(--size-xl); margin-bottom: var(--space-3); }
  .booking-cta-card p {
    font-size: var(--size-sm);
    color: var(--color-text-muted);
    margin-bottom: var(--space-4);
    max-width: 360px;
    margin-left: auto;
    margin-right: auto;
  }

  .inquiry-form {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: var(--space-6);
  }
  .inquiry-form h3 { font-size: var(--size-xl); margin-bottom: var(--space-2); }
  .inquiry-form > p { font-size: var(--size-sm); color: var(--color-text-muted); margin-bottom: var(--space-4); }

  @media (max-width: 640px) {
    .whatsapp-cta { padding: var(--space-6) var(--space-4); }
    .booking-cta-card { padding: var(--space-6) var(--space-4); }
    .inquiry-form { padding: var(--space-6) var(--space-4); }
    .contact-grid { gap: var(--space-6); }
    .faq-link { width: fit-content; max-width: 100%; padding: var(--space-4) var(--space-6); font-size: var(--size-sm); letter-spacing: 0.08em; white-space: normal; text-align: left; line-height: 1.3; justify-content: center; }
    .faq-link svg { width: 16px; height: 16px; }
  }
</style>
@endsection

@section('content')
<main id="main-content">

  <div class="page-hero">
    <div class="container--narrow">
      <span class="page-hero__eyebrow">{{ $hero?->content['subheading'] ?? 'Contact' }}</span>
      <h1 class="page-hero__title">{{ $hero?->content['heading'] ?? 'Contact' }}</h1>
      <div class="page-hero__text">{!! $hero?->content['body'] ?? '<p>You are welcome to schedule a free 30-minute introductory call to discuss your situation and explore whether we are a good fit to work together.</p>' !!}</div>
    </div>
  </div>

  <section class="section section--white" aria-labelledby="contact-heading">
    <div class="container">
      <div class="contact-grid">

        <!-- Contact info -->
        <div>
          <span class="section-label">{{ __('ui.contact.contact_label') }}</span>
          <h2 id="contact-heading" style="margin-bottom:var(--space-8);">{{ $info?->content['heading'] ?? __("ui.contact.contact_label") . ' Information' }}</h2>

          <!-- WhatsApp CTA -->
          @php $waNumber = $info?->content['whatsapp_number'] ?? '31641087913'; @endphp
          <div class="whatsapp-cta">
            <h3>{{ $info?->content['whatsapp_text'] ?? __('ui.contact.whatsapp_preferred') }}</h3>
            <p>{{ __('ui.contact.whatsapp_desc') }}</p>
            <a href="https://wa.me/{{ $waNumber }}?text=Hi%20Lysander%2C%20I'm%20interested%20in%20booking%20a%20session." target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp btn--lg" style="margin:0 auto;">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              WhatsApp: +{{ substr($waNumber, 0, 2) }} {{ substr($waNumber, 2, 1) }} {{ substr($waNumber, 3) }}
            </a>
          </div>

          <!-- Contact details -->
          @php $contactItems = [
            ['label'=>__('ui.contact.email_label'),'value'=>'contact@therapistlysander.com','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>'],
            ['label'=>__('ui.contact.online_sessions'),'value'=>__('ui.contact.online_sessions_value'),'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>'],
            ['label'=>__('ui.contact.session_duration'),'value'=>__('ui.contact.session_duration_value'),'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
            ['label'=>__('ui.contact.languages'),'value'=>__('ui.contact.languages_value'),'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>'],
          ]; @endphp
          @foreach($contactItems as $item)
          <div class="contact-info__item">
            <div class="contact-info__icon">
              {!! $item['icon'] !!}
            </div>
            <div>
              <p class="contact-info__label">{{ $item['label'] }}</p>
              <p class="contact-info__value">{{ $item['value'] }}</p>
            </div>
          </div>
          @endforeach

          <!-- FAQ link -->
          <div style="margin-top:var(--space-6);padding-top:var(--space-6);border-top:1px solid var(--color-border);">
            <p style="font-size:var(--size-sm);color:var(--color-text-muted);margin-bottom:var(--space-3);">{{ __('ui.contact.more_questions') }}</p>
            <a href="{{ route('faq') }}" class="btn btn--outline faq-link">
              {{ __('ui.contact.view_faqs') }}
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
          </div>

        </div>

        <!-- Right column: Booking CTA + Inquiry form -->
        <div>

          <!-- Prominent booking CTA -->
          <div class="booking-cta-card">
            <h3>{{ $booking?->content['heading'] ?? __('ui.contact.booking_card_heading') }}</h3>
            <p>{{ __('ui.contact.booking_desc') }}</p>
            <a href="{{ \App\Providers\AppServiceProvider::localizeUrl($booking?->content['cta_url'] ?? null) }}" class="btn btn--primary btn--lg" style="width:100%;justify-content:center;">
              {{ $booking?->content['cta_label'] ?? __('ui.contact.start_booking') }}
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
          </div>

          <!-- General inquiry form -->
          <div class="inquiry-form">
            <h3>{{ __('ui.contact.send_heading') }}</h3>
            <p>{{ __('ui.contact.send_desc') }}</p>

            @if(session('success'))
              <div style="background:var(--color-teal-light);border:1px solid var(--color-accent-light);border-radius:var(--radius);padding:var(--space-4);margin-bottom:var(--space-6);color:var(--color-teal);font-size:var(--size-sm);">
                {{ session('success') }}
              </div>
            @endif

            <form method="POST" action="{{ route('contact.submit') }}" id="contact-form">
              @csrf
              <input type="hidden" name="form_token" value="{{ app(\App\Services\ContactSpamGuard::class)->issueFormToken() }}">
              {{-- Spam protection: real visitors never see or fill this field --}}
              <div class="form-extra" aria-hidden="true">
                <label for="inquiry-website">Website</label>
                <input type="text" id="inquiry-website" name="website" tabindex="-1" autocomplete="off">
              </div>
              <div class="form-group">
                <label class="form-label" for="inquiry-name">{{ __('ui.contact.your_name') }}</label>
                <input type="text" class="form-input" id="inquiry-name" name="name" placeholder="{{ __('ui.contact.full_name_placeholder') }}" autocomplete="name" value="{{ old('name') }}" required>
                @error('name')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group" style="margin-top:var(--space-4);">
                <label class="form-label" for="inquiry-email">{{ __('ui.contact.email_address') }}</label>
                <input type="email" class="form-input" id="inquiry-email" name="email" placeholder="{{ __('ui.contact.email_placeholder') }}" autocomplete="email" value="{{ old('email') }}" required>
                @error('email')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group" style="margin-top:var(--space-4);">
                <label class="form-label" for="inquiry-message">{{ __('ui.contact.message_label') }}</label>
                <textarea class="form-textarea" id="inquiry-message" name="message" placeholder="{{ __('ui.contact.message_placeholder') }}" required>{{ old('message') }}</textarea>
                @error('message')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              @if(config('services.turnstile.site_key'))
                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="light" style="margin-top:var(--space-4);"></div>
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
              @else
                @php($captcha = app(\App\Services\ContactSpamGuard::class)->issueCaptcha())
                <div class="form-group" style="margin-top:var(--space-4);">
                  <label class="form-label" for="inquiry-captcha">{{ __('ui.contact.captcha_label', ['a' => $captcha['a'], 'b' => $captcha['b']]) }}</label>
                  <input type="text" class="form-input" id="inquiry-captcha" name="captcha_answer" inputmode="numeric" placeholder="{{ __('ui.contact.captcha_placeholder') }}" autocomplete="off" required>
                  <input type="hidden" name="captcha_token" value="{{ $captcha['token'] }}">
                  @error('captcha_answer')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
                </div>
              @endif
              <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:var(--space-4);">
                {{ __('ui.common.send_message') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@extends('layouts.app')

@section('title', 'Contact | Therapist Lysander')
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
    padding: var(--space-8);
    color: white;
    text-align: center;
    margin-bottom: var(--space-8);
  }
  .whatsapp-cta h3 { color: white; margin-bottom: var(--space-3); }
  .whatsapp-cta p { color: rgba(255,255,255,0.8); font-size: var(--size-sm); margin: 0 auto var(--space-6); }

  .booking-cta-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: var(--space-8);
    text-align: center;
    margin-bottom: var(--space-8);
    box-shadow: var(--shadow-md);
  }
  .booking-cta-card h3 { font-size: var(--size-xl); margin-bottom: var(--space-3); }
  .booking-cta-card p {
    font-size: var(--size-sm);
    color: var(--color-text-muted);
    margin-bottom: var(--space-6);
    max-width: 360px;
    margin-left: auto;
    margin-right: auto;
  }

  .inquiry-form {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: var(--space-8);
  }
  .inquiry-form h3 { font-size: var(--size-xl); margin-bottom: var(--space-2); }
  .inquiry-form > p { font-size: var(--size-sm); color: var(--color-text-muted); margin-bottom: var(--space-6); }
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
          <span class="section-label">Contact</span>
          <h2 id="contact-heading" style="margin-bottom:var(--space-8);">{{ $info?->content['heading'] ?? "Contact Information" }}</h2>

          <!-- WhatsApp CTA -->
          @php $waNumber = $info?->content['whatsapp_number'] ?? '66935309052'; @endphp
          <div class="whatsapp-cta">
            <h3>{{ $info?->content['whatsapp_text'] ?? 'Prefer a quick message?' }}</h3>
            <p>Feel free to send a WhatsApp message for brief questions or practical matters. Messages are answered during working days as availability allows.</p>
            <a href="https://wa.me/{{ $waNumber }}?text=Hi%20Lysander%2C%20I'm%20interested%20in%20booking%20a%20session." target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp btn--lg" style="margin:0 auto;">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              WhatsApp: +{{ substr($waNumber, 0, 2) }} {{ substr($waNumber, 2, 2) }} {{ substr($waNumber, 4, 3) }} {{ substr($waNumber, 7) }}
            </a>
          </div>

          <!-- Contact details -->
          @php $contactItems = $info?->content['items'] ?? [
            ['label'=>'Email','value'=>'therapistlysander@gmail.com'],
            ['label'=>'Online sessions','value'=>'Available worldwide via secure video call'],
            ['label'=>'Session duration','value'=>'60 minutes · Free introduction call (30 min)'],
            ['label'=>'Languages','value'=>'Dutch & English'],
          ]; @endphp
          @foreach($contactItems as $item)
          <div class="contact-info__item">
            <div class="contact-info__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <p class="contact-info__label">{{ $item['label'] }}</p>
              <p class="contact-info__value">{{ $item['value'] }}</p>
            </div>
          </div>
          @endforeach

          <!-- FAQ link -->
          <div style="margin-top:var(--space-10);padding-top:var(--space-8);border-top:1px solid var(--color-border);">
            <p style="font-size:var(--size-sm);color:var(--color-text-muted);margin-bottom:var(--space-4);">Have more questions before booking?</p>
            <a href="{{ route('faq') }}" class="btn btn--outline">
              View all FAQs
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
          </div>
        </div>

        <!-- Right column: Booking CTA + Inquiry form -->
        <div>

          <!-- Prominent booking CTA -->
          <div class="booking-cta-card">
            <h3>{{ $booking?->content['heading'] ?? 'Book a Free 30-Minute Intro Call' }}</h3>
            <p>{!! $booking?->content['body'] ?? "A free online introductory call to discuss your situation, ask questions, and explore whether we are a good fit to work together." !!}</p>
            <a href="{{ $booking?->content['cta_url'] ?? route('booking') }}" class="btn btn--primary btn--lg" style="width:100%;justify-content:center;">
              {{ $booking?->content['cta_label'] ?? 'Start booking' }}
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
          </div>

          <!-- General inquiry form -->
          <div class="inquiry-form">
            <h3>Send a message</h3>
            <p>Have a question or prefer to reach out first? Fill in the form below.</p>

            @if(session('success'))
              <div style="background:var(--color-teal-light);border:1px solid var(--color-accent-light);border-radius:var(--radius);padding:var(--space-4);margin-bottom:var(--space-6);color:var(--color-teal);font-size:var(--size-sm);">
                {{ session('success') }}
              </div>
            @endif

            <form method="POST" action="{{ route('contact.submit') }}" id="contact-form">
              @csrf
              <div class="form-group">
                <label class="form-label" for="inquiry-name">Your name</label>
                <input type="text" class="form-input" id="inquiry-name" name="name" placeholder="Full name" autocomplete="name" value="{{ old('name') }}" required>
                @error('name')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group" style="margin-top:var(--space-4);">
                <label class="form-label" for="inquiry-email">Email address</label>
                <input type="email" class="form-input" id="inquiry-email" name="email" placeholder="your@email.com" autocomplete="email" value="{{ old('email') }}" required>
                @error('email')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group" style="margin-top:var(--space-4);">
                <label class="form-label" for="inquiry-message">Message</label>
                <textarea class="form-textarea" id="inquiry-message" name="message" placeholder="How can I help you?" required>{{ old('message') }}</textarea>
                @error('message')<p style="color:#dc2626;font-size:var(--size-xs);margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:var(--space-4);">
                Send message
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

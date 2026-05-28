@extends('emails.layout')

@section('title', 'We received your message')

@section('content')
<h2>Thank you for reaching out, {{ $name }}.</h2>

<p>We have received your message and appreciate you taking the time to contact us. We will review your inquiry and get back to you within 1-2 business days.</p>

<div class="highlight-box">
    <strong>Your message:</strong><br>
    {{ $messageExcerpt }}
</div>

<p>If your matter is urgent, please don't hesitate to reach out via phone during office hours.</p>

<p>Warm regards,<br>Lysander Verschuur, MSc.</p>
@endsection

@extends('emails.layout')

@section('title', 'We received your message')

@section('content')
<h2>Thank you for reaching out, {{ $name }}.</h2>

<p>Your message has been received successfully. Thank you for taking the time to get in touch.</p>

<p>I will review your message and respond as soon as possible. I look forward to connecting with you.</p>

<p>Warm regards,<br>Lysander Verschuur, MSc.</p>
@endsection

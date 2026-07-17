@extends('emails.layout')

@section('title', 'We hebben je bericht ontvangen')

@section('content')
<h2>Bedankt voor je bericht, {{ $name }}.</h2>

<p>Je bericht is succesvol ontvangen. Bedankt dat je de tijd hebt genomen om contact op te nemen.</p>

<p>Ik zal je bericht zo snel mogelijk bekijken en beantwoorden. Ik kijk ernaar uit om met je in contact te komen.</p>

<p>Met vriendelijke groet,<br>Lysander Verschuur, MSc.</p>
@endsection

@section('footer')
Dit is een automatisch gegenereerd bericht van Therapist Lysander.<br>Reageer alstublieft niet rechtstreeks op deze e-mail.
@endsection

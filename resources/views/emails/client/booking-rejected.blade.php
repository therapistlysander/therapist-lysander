@extends('emails.layout')

@section('title', 'Regarding your booking request')

@section('content')
<h2>Dear {{ $firstName }},</h2>

<p>Thank you for reaching out and for your interest in starting therapy.</p>

<p>After careful consideration, I regret to inform you that I am unable to accommodate your booking request at this time.</p>

@if($rejectionReason)
<div class="highlight-box">
    {{ $rejectionReason }}
</div>
@endif

<p>I understand this may be disappointing. Your wellbeing remains important to me, and I encourage you to seek appropriate support.</p>

<p>You can, for example:</p>

<ul style="padding-left:20px;color:#374151;">
    <li style="margin-bottom:8px;">Reach out to your primary healthcare provider or another licensed mental health professional for a referral if needed.</li>
    <li style="margin-bottom:8px;">Contact local mental health services in your area for further support.</li>
    <li>If you are experiencing a mental health crisis or are in immediate danger, please contact your local emergency services or nearest crisis intervention service immediately.</li>
</ul>

<p>I sincerely wish you all the best and hope you find the support that is right for you.</p>

<p>Kind regards,<br>Lysander Verschuur, MSc.</p>
@endsection

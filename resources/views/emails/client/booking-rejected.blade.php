@extends('emails.layout')

@section('title', 'Regarding your booking request')

@section('content')
<h2>Dear {{ $firstName }},</h2>

<p>Thank you for reaching out and for your interest in starting therapy. After careful consideration, we regret to inform you that we are unable to accommodate your booking request at this time.</p>

@if($rejectionReason)
<div class="highlight-box">
    {{ $rejectionReason }}
</div>
@endif

<p>We understand this may be disappointing, and your wellbeing remains important to us. We encourage you to:</p>

<ul style="padding-left:20px;color:#374151;">
    <li style="margin-bottom:8px;">Reach out to your GP (huisarts) for a referral to another therapist</li>
    <li style="margin-bottom:8px;">Contact the <strong>GGZ</strong> for mental health services in your area</li>
    <li>Visit <strong>113 Zelfmoordpreventie</strong> (0900-0113) if you need immediate support</li>
</ul>

<p>We wish you all the best on your journey.</p>

<p>With kind regards,<br>Lysander Verschuur, MSc.</p>
@endsection
@extends('emails.layout')

@section('title', 'Regarding your booking request')

@section('content')
<h2>Dear {{ $firstName }},</h2>

<p>Thank you for reaching out and for your interest in starting therapy. After careful consideration, we regret to inform you that we are unable to accommodate your booking request at this time.</p>

@if($rejectionReason)
<div class="highlight-box">
    {{ $rejectionReason }}
</div>
@endif

<p>We understand this may be disappointing. Your wellbeing remains important to us and we encourage you to seek appropriate support.</p>

<p>You can for example:</p>

<ul style="padding-left:20px;color:#374151;">
    <li style="margin-bottom:8px;">Reach out to your primary healthcare provider or another licensed mental health professional for a referral if needed.</li>
    <li style="margin-bottom:8px;">Contact local mental health services in your area for further support.</li>
    <li>If you are experiencing a mental health crisis or are in immediate danger, please contact your local emergency services or nearest crisis intervention service immediately.</li>
</ul>

<p>I sincerely wish you all the best and hope you find the support that is right for you.</p>

<p>With kind regards,<br>Lysander Verschuur, MSc.</p>
@endsection
@extends('emails.layout')

@section('title', 'Regarding your booking request')

@section('content')
<h2>Dear {{ $firstName }},</h2>

<p>Thank you for reaching out and for your interest in starting therapy. After careful consideration, we regret to inform you that we are unable to accommodate your booking request at this time.</p>

@if($rejectionReason)
<div class="highlight-box">
    {{ $rejectionReason }}
</div>
@endif

<p>We understand this may be disappointing, and your wellbeing remains important to us. We encourage you to:</p>

<ul style="padding-left:20px;color:#374151;">
    <li style="margin-bottom:8px;">Reach out to your GP (huisarts) for a referral to another therapist</li>
    <li style="margin-bottom:8px;">Contact the <strong>GGZ</strong> for mental health services in your area</li>
    <li>Visit <strong>113 Zelfmoordpreventie</strong> (0900-0113) if you need immediate support</li>
</ul>

<p>We wish you all the best on your journey.</p>

<p>With kind regards,<br>Lysander Verschuur, MSc.</p>
@endsection

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

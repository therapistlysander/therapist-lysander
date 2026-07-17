@extends('emails.layout')

@section('title', 'Betreft je afspraakaanvraag')

@section('content')
<h2>Beste {{ $firstName }},</h2>

<p>Bedankt voor je interesse in een behandeling bij Therapist Lysander.</p>

<p>Na zorgvuldige overweging moet ik je helaas laten weten dat ik je aanvraag op dit moment niet kan aannemen.</p>

@if($rejectionReason)
<div class="highlight-box">
    {{ $rejectionReason }}
</div>
@endif

<p>Ik begrijp dat dit teleurstellend kan zijn. Jouw welzijn blijft belangrijk en ik moedig je aan om passende ondersteuning te zoeken.</p>

<p>Je kunt bijvoorbeeld:</p>

<ul style="padding-left:20px;color:#374151;">
    <li style="margin-bottom:8px;">contact opnemen met je huisarts voor een verwijzing;</li>
    <li style="margin-bottom:8px;">contact opnemen met een psycholoog of andere GGZ-aanbieder bij jou in de omgeving;</li>
    <li>bij een acute psychische crisis direct contact opnemen met 113 Zelfmoordpreventie (0800-0113) of, indien sprake is van direct gevaar, met de hulpdiensten.</li>
</ul>

<p>Ik wens je oprecht het allerbeste en hoop dat je de ondersteuning vindt die bij jou past.</p>

<p>Met vriendelijke groet,<br>Lysander Verschuur, MSc.</p>
@endsection

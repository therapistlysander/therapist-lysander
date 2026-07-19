@extends('emails.layout')

@section('title', 'Betreft je gemiste afspraak')

@section('content')
<h2>Beste {{ $firstName }},</h2>

@if($newStatus === 'no_show')
<p>Ik heb geconstateerd dat je helaas niet aanwezig was bij je geplande afspraak. Daarom is deze afspraak geregistreerd als een no-show.</p>

<p>Mocht je alsnog een nieuwe afspraak willen maken, neem dan gerust contact met mij op.</p>
@elseif($newStatus === 'confirmed')
<p>Je afspraak is <strong>bevestigd</strong>. We kijken ernaar uit je te zien!</p>
@elseif($newStatus === 'cancelled')
<p>We laten je weten dat je afspraak is <strong>geannuleerd</strong>.</p>
@elseif($newStatus === 'completed')
<p>Je sessie is gemarkeerd als <strong>voltooid</strong>. Bedankt voor je komst.</p>
@else
<p>De status van je afspraak is bijgewerkt naar: <strong>{{ ucfirst($newStatus) }}</strong>.</p>
@endif

@if($scheduledAt)
<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Gepland:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($scheduledAt)->locale('nl')->isoFormat('D MMMM YYYY [om] HH:mm') }}</td>
        </tr>
    </table>
</div>
@elseif($preferredDate)
<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Voorkeursdatum:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($preferredDate)->locale('nl')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>
</div>
@endif

<p>Heb je vragen of wil je een nieuwe afspraak inplannen? Neem dan gerust contact met mij op.</p>

<p>Met vriendelijke groet,<br>Lysander Verschuur, MSc.</p>
@endsection

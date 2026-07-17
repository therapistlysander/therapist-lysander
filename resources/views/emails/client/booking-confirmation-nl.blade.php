@extends('emails.layout')

@section('title', 'Je boekingsverzoek is ontvangen')

@section('content')
<h2>Bedankt voor je aanvraag, {{ $firstName }}.</h2>

<p>Fijn dat je een kennismakingsgesprek hebt aangevraagd.</p>

<p>Ik heb je aanvraag in goede orde ontvangen en bekijk deze zo spoedig mogelijk. Binnen 1 à 2 werkdagen ontvang je een bevestiging of, indien nodig, een voorstel voor een alternatief tijdstip.</p>

<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        @if($appointmentType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:150px;">Type afspraak:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ $appointmentType }}</td>
        </tr>
        @endif
        @if($sessionType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Sessievorm:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ ucfirst($sessionType) }}</td>
        </tr>
        @endif
        @if($displayDate)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Voorkeursdatum:</td>
            <td style="padding:4px 0;color:#1a2332;">
                {{ $displayDate }}
                @if(!empty($appointmentTimezone))
                    <span style="font-size:11px;color:#9ca3af;">({{ str_replace('_', ' ', $appointmentTimezone) }})</span>
                @endif
            </td>
        </tr>
        @endif
    </table>
</div>

<p>Ik kijk ernaar uit je binnenkort te spreken.</p>

<p>Met vriendelijke groet,<br>Lysander Verschuur</p>
@endsection

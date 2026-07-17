@extends('emails.layout')

@section('title', 'Je sessie is bevestigd')

@section('content')
<h2>Goed nieuws, {{ $firstName }}! Je sessie is bevestigd.</h2>

<p>Je afspraak is ingepland. Hieronder vind je de details:</p>

<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        @if($appointmentType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Soort afspraak:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ $appointmentType }}</td>
        </tr>
        @endif
        @if($sessionType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Type sessie:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ $sessionType === 'in-person' ? 'Op locatie' : ucfirst($sessionType) }}</td>
        </tr>
        @endif
        @if($displayScheduledAt)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Datum &amp; tijd:</td>
            <td style="padding:4px 0;color:#1a2332;">
                {{ $displayScheduledAt }}
                @if(!empty($appointmentTimezone))
                    <span style="font-size:11px;color:#9ca3af;">({{ str_replace('_', ' ', $appointmentTimezone) }})</span>
                @endif
            </td>
        </tr>
        @endif
        @if($meetingPlatform)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Platform:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ ucfirst(str_replace('_', ' ', $meetingPlatform)) }}</td>
        </tr>
        @endif
        @if($meetingLink)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Deelnamelink:</td>
            <td style="padding:4px 0;color:#1a2332;"><a href="{{ $meetingLink }}" style="color:#5a9e97;">Deelnemen aan sessie</a></td>
        </tr>
        @endif
    </table>
</div>

<p>Zorg ervoor dat je een paar minuten voor aanvang van de sessie beschikbaar bent. Als je de afspraak moet verzetten, neem dan zo snel mogelijk contact met me op.</p>

<p>Ik kijk uit naar onze sessie.<br>Lysander Verschuur, MSc.</p>
@endsection

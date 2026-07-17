@extends('emails.layout')

@section('title', 'Your booking request was received')

@section('content')
<h2>Thank you for your booking request, {{ $firstName }}.</h2>

<p>I have received your request and will review it shortly. You can expect to hear back from me within 1-2 business days.</p>

<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        @if($appointmentType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:150px;">Appointment type:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ $appointmentType }}</td>
        </tr>
        @endif
        @if($sessionType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Session type:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ ucfirst($sessionType) }}</td>
        </tr>
        @endif
        @if($displayDate)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Preferred date:</td>
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

<p>I will confirm your appointment or suggest an alternative time that works for both of us.</p>

<p>Warm regards,<br>Lysander Verschuur, MSc.</p>
@endsection

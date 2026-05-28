@extends('emails.layout')

@section('title', 'Your booking request was received')

@section('content')
<h2>Thank you for your booking request, {{ $firstName }}.</h2>

<p>We have received your request and will review it shortly. You can expect to hear back from us within 1-2 business days.</p>

<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        @if($sessionFormat)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Session format:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ ucfirst($sessionFormat) }}</td>
        </tr>
        @endif
        @if($sessionType)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Session type:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ ucfirst($sessionType) }}</td>
        </tr>
        @endif
        @if($preferredDate)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Preferred date:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($preferredDate)->format('l, j F Y \a\t H:i') }}</td>
        </tr>
        @endif
    </table>
</div>

<p>We will confirm your appointment or suggest an alternative time that works for both of us.</p>

<p>Warm regards,<br>Lysander Verschuur, MSc.</p>
@endsection

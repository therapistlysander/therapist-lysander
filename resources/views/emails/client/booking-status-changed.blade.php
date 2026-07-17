@extends('emails.layout')

@section('title', 'Booking status update')

@section('content')
<h2>Dear {{ $firstName }},</h2>

@if($newStatus === 'no_show')
<p>I noticed that you were unable to attend your scheduled session. Your booking has been marked as a no-show.</p>

<p>If you'd like to reschedule, please don't hesitate to get in touch.</p>
@elseif($newStatus === 'confirmed')
<p>Your booking has been <strong>confirmed</strong>. We look forward to seeing you!</p>
@elseif($newStatus === 'cancelled')
<p>We're writing to let you know that your booking has been <strong>cancelled</strong>.</p>
@elseif($newStatus === 'completed')
<p>Your session has been marked as <strong>completed</strong>. Thank you for attending.</p>
@else
<p>Your booking status has been updated to: <strong>{{ ucfirst($newStatus) }}</strong>.</p>
@endif

@if($scheduledAt)
<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Scheduled:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($scheduledAt)->format('l, j F Y \a\t H:i') }}</td>
        </tr>
    </table>
</div>
@elseif($preferredDate)
<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Preferred date:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($preferredDate)->format('l, j F Y') }}</td>
        </tr>
    </table>
</div>
@endif

<p>If you have any questions or would like to schedule a new appointment, please feel free to get in touch.</p>

<p>Kind regards,<br>Lysander Verschuur, MSc.</p>
@endsection
@extends('emails.layout')

@section('title', 'Booking status update')

@section('content')
<h2>Dear {{ $firstName }},</h2>

@if($newStatus === 'confirmed')
<p>Your booking has been <strong>confirmed</strong>. We look forward to seeing you!</p>
@elseif($newStatus === 'cancelled')
<p>We're writing to let you know that your booking has been <strong>cancelled</strong>.</p>
@elseif($newStatus === 'completed')
<p>Your session has been marked as <strong>completed</strong>. Thank you for attending.</p>
@elseif($newStatus === 'no_show')
<p>We noticed you were unable to attend your scheduled session. Your booking has been marked as a <strong>no-show</strong>.</p>
<p>If you'd like to reschedule, please don't hesitate to get in touch.</p>
@else
<p>Your booking status has been updated to: <strong>{{ ucfirst($newStatus) }}</strong>.</p>
@endif

@if($scheduledAt)
<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Scheduled:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($scheduledAt)->format('l, j F Y \a\t H:i') }}</td>
        </tr>
    </table>
</div>
@elseif($preferredDate)
<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Preferred date:</td>
            <td style="padding:4px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($preferredDate)->format('l, j F Y') }}</td>
        </tr>
    </table>
</div>
@endif

<p>If you have any questions or need to make changes, please feel free to reach out.</p>

<p>With kind regards,<br>Lysander Verschuur, MSc.</p>
@endsection

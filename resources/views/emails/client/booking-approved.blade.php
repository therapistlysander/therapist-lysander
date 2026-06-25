@extends('emails.layout')

@section('title', 'Your session is confirmed')

@section('content')
<h2>Good news, {{ $firstName }}! Your session is confirmed.</h2>

<p>Your appointment has been scheduled. Please see the details below:</p>

<div class="highlight-box">
    <table style="width:100%;border-collapse:collapse;">
        @if($scheduledAt)
        <tr>
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;width:130px;">Date & time:</td>
            <td style="padding:4px 0;color:#1a2332;">
                {{ \Carbon\Carbon::parse($scheduledAt)->format('l, j F Y \a\t H:i') }}
                <span style="font-size:11px;color:#9ca3af;">
                    ({{ $timezone }}
                    @if(!empty($clientTimezone))
                        / {{ $clientTimezone }}
                    @endif
                    )
                </span>
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
            <td style="padding:4px 0;font-weight:600;color:#6b7280;font-size:13px;">Meeting link:</td>
            <td style="padding:4px 0;color:#1a2332;"><a href="{{ $meetingLink }}" style="color:#5a9e97;">Join session</a></td>
        </tr>
        @endif
    </table>
</div>

<p>Please try to be available a few minutes before the session starts. If you need to reschedule, please contact us as soon as possible.</p>

<p>Looking forward to our session together.<br>Lysander Verschuur, MSc.</p>
@endsection

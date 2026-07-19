@extends('emails.layout')

@section('title', 'New booking request')

@section('content')
<h2>New Booking Request</h2>

<p>A new booking request has been submitted:</p>

<table style="width:100%;border-collapse:collapse;table-layout:fixed;">
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;width:140px;vertical-align:top;">Name:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $booking->first_name }} {{ $booking->last_name }}</td>
    </tr>
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Email:</td>
        <td style="padding:10px 0;color:#1a2332;"><a href="mailto:{{ $booking->email }}" style="color:#5a9e97;">{{ $booking->email }}</a></td>
    </tr>
    @if($booking->phone)
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Phone:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $booking->phone }}</td>
    </tr>
    @endif
    @if($booking->session_type)
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Session type:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ ucfirst($booking->session_type) }}</td>
    </tr>
    @endif
    @if($booking->session_format)
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Appointment type:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ match($booking->session_format) { 'intake' => 'Introductory Call', 'standard' => 'Standard Session', 'emdr' => 'EMDR Session', 'initial' => 'Initial Session', default => ucfirst($booking->session_format) } }}</td>
    </tr>
    @endif
    @if($booking->preferred_date)
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Preferred date:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('l, j F Y H:i') }}</td>
    </tr>
    @endif
    @if($booking->reason)
    <tr>
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Reason:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $booking->reason }}</td>
    </tr>
    @endif
</table>

<p style="margin-top:20px;font-size:12px;color:#9ca3af;word-break:break-word;overflow-wrap:break-word;">Source: {{ $booking->source }} | Status: {{ $booking->status }} | Received: {{ $booking->created_at->format('j M Y H:i') }}</p>
@endsection

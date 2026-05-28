@extends('emails.layout')

@section('title', 'New contact submission')

@section('content')
<h2>New Contact Submission</h2>

<p>A new contact form submission has been received:</p>

<table style="width:100%;border-collapse:collapse;">
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;width:120px;vertical-align:top;">Name:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $contact->name }}</td>
    </tr>
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Email:</td>
        <td style="padding:10px 0;color:#1a2332;"><a href="mailto:{{ $contact->email }}" style="color:#5a9e97;">{{ $contact->email }}</a></td>
    </tr>
    @if($contact->phone)
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Phone:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $contact->phone }}</td>
    </tr>
    @endif
    @if($contact->subject)
    <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Subject:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $contact->subject }}</td>
    </tr>
    @endif
    <tr>
        <td style="padding:10px 0;font-weight:600;color:#6b7280;font-size:13px;vertical-align:top;">Message:</td>
        <td style="padding:10px 0;color:#1a2332;">{{ $contact->message }}</td>
    </tr>
</table>

<p style="margin-top:20px;font-size:12px;color:#9ca3af;">Source: {{ $contact->source }} | IP: {{ $contact->ip_address }} | Received: {{ $contact->created_at->format('j M Y H:i') }}</p>
@endsection

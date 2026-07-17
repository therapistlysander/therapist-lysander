<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .email-wrapper { width: 100%; padding: 40px 20px; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; }
        .email-header { background-color: #5a9e97; padding: 28px 32px; text-align: center; }
        .email-header h1 { margin: 0; color: #ffffff; font-size: 20px; font-weight: 600; letter-spacing: 0.02em; }
        .email-body { padding: 32px; color: #374151; font-size: 15px; line-height: 1.6; }
        .email-body h2 { color: #1a2332; font-size: 18px; margin: 0 0 16px; }
        .email-body p { margin: 0 0 14px; }
        .email-body .detail-row { display: flex; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .email-body .detail-label { font-weight: 600; color: #6b7280; min-width: 140px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; }
        .email-body .detail-value { color: #1a2332; }
        .email-body .highlight-box { background: #f0fdf9; border-left: 3px solid #5a9e97; padding: 14px 18px; margin: 18px 0; border-radius: 0 6px 6px 0; }
        .email-footer { padding: 20px 32px; background-color: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; }
        .email-footer p { margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>Therapist Lysander</h1>
            </div>
            <div class="email-body">
                @yield('content')
            </div>
            <div class="email-footer">
                <p>{!! @yield('footer', 'This is an automated message from Therapist Lysander.<br>Please do not reply directly to this email.') !!}</p>
            </div>
        </div>
    </div>
</body>
</html>

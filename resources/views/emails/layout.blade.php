<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <!--[if mso]>
    <style>table,td,div,h1,h2,p{font-family:Arial,sans-serif!important}</style>
    <![endif]-->
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0; mso-table-rspace: 0; }
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 20px;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #5a9e97;
            padding: 28px 32px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .email-body {
            padding: 32px;
            color: #374151;
            font-size: 15px;
            line-height: 1.6;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .email-body h2 {
            color: #1a2332;
            font-size: 18px;
            margin: 0 0 16px;
            word-break: break-word;
        }
        .email-body p {
            margin: 0 0 14px;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .email-body ul {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .email-body .detail-row {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .email-body .detail-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 140px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .email-body .detail-value {
            color: #1a2332;
        }

        .email-body .highlight-box {
            background: #f0fdf9;
            border-left: 3px solid #5a9e97;
            padding: 14px 18px;
            margin: 18px 0;
            border-radius: 0 6px 6px 0;
            overflow: hidden;
            word-break: break-word;
        }

        .email-footer {
            padding: 20px 32px;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        .email-footer p {
            margin: 0;
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.5;
        }

        /* Responsive tables in email clients */
        .email-body table {
            width: 100% !important;
            table-layout: fixed;
        }
        .email-body table td {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* Links */
        .email-body a {
            color: #5a9e97;
            word-break: break-all;
        }

        /* Mobile */
        @media only screen and (max-width: 620px) {
            .email-wrapper { padding: 16px 8px; }
            .email-container { border-radius: 4px; }
            .email-header { padding: 20px 16px; }
            .email-body { padding: 20px 16px; }
            .email-footer { padding: 16px; }
        }
    </style>
</head>
<body style="margin:0;padding:0;width:100% !important;min-width:100%;background-color:#f5f5f5;">
    <div class="email-wrapper" style="width:100%;padding:40px 20px;background-color:#f5f5f5;">
        <table role="presentation" class="email-container" width="600" cellspacing="0" cellpadding="0" align="center" style="max-width:600px;width:100%;margin:0 auto;background-color:#ffffff;border-radius:8px;border-collapse:separate;">
            <tr>
                <td style="background-color:#5a9e97;padding:28px 32px;text-align:center;border-radius:8px 8px 0 0;">
                    <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:600;letter-spacing:0.02em;">Therapist Lysander</h1>
                </td>
            </tr>
            <tr>
                <td class="email-body" style="padding:32px;color:#374151;font-size:15px;line-height:1.6;">
                    @yield('content')
                </td>
            </tr>
            <tr>
                <td class="email-footer" style="padding:20px 32px;background-color:#f9fafb;border-top:1px solid #e5e7eb;text-align:center;border-radius:0 0 8px 8px;">
                    <p style="margin:0;font-size:12px;color:#9ca3af;line-height:1.5;">
                        @section('footer_line1')This is an automated message from Therapist Lysander.@show
                        <br>
                        @section('footer_line2')Please do not reply directly to this email.@show
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

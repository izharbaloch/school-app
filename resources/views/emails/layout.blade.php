<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', $schoolName ?? config('app.name'))</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f2f5;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f2f5;padding:40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" border="0"
                   style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                {{-- Header --}}
                <tr>
                    <td style="background-color:#1a56db;padding:28px 32px;">
                        <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;">
                            {{ $schoolName ?? config('app.name') }}
                        </h1>
                        @hasSection('header_sub')
                        <p style="margin:6px 0 0;color:#bfdbfe;font-size:13px;">@yield('header_sub')</p>
                        @endif
                    </td>
                </tr>
                {{-- Body --}}
                <tr>
                    <td style="padding:32px;">
                        @yield('body')
                    </td>
                </tr>
                {{-- Divider --}}
                <tr>
                    <td style="padding:0 32px;">
                        <hr style="border:none;border-top:1px solid #e5e7eb;margin:0;">
                    </td>
                </tr>
                {{-- Footer --}}
                <tr>
                    <td style="padding:20px 32px;text-align:center;background-color:#f9fafb;">
                        <p style="margin:0;color:#6b7280;font-size:12px;">
                            This is an automated message from <strong>{{ $schoolName ?? config('app.name') }}</strong>.<br>
                            Please do not reply to this email.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

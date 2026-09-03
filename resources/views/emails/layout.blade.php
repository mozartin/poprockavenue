<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>{{ $title ?? 'POP/ROCK AVENUE' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#08090D;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#08090D;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#11131A;border:1px solid rgba(255,255,255,0.08);border-radius:16px;overflow:hidden;">
                    {{-- Header / logo --}}
                    <tr>
                        <td style="padding:28px 32px 20px;border-bottom:1px solid rgba(255,255,255,0.06);background:linear-gradient(135deg,rgba(124,58,237,0.18),rgba(34,211,238,0.08));">
                            <a href="{{ url('/') }}" style="text-decoration:none;">
                                <span style="font-size:18px;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:#F8FAFC;">POP<span style="color:#22D3EE;">/</span>ROCK</span>
                                <span style="font-size:18px;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:#22D3EE;margin-left:8px;">AVENUE</span>
                            </a>
                            <div style="margin-top:10px;height:2px;width:72px;background:linear-gradient(90deg,#7C3AED,#22D3EE);border-radius:2px;"></div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px 32px 8px;color:#F8FAFC;">
                            {{ $slot }}
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px 28px;border-top:1px solid rgba(255,255,255,0.06);">
                            <p style="margin:0 0 6px;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#22D3EE;">
                                POP/ROCK AVENUE
                            </p>
                            <p style="margin:0;font-size:13px;line-height:1.5;color:#94A3B8;">
                                Live cover band · Netherlands<br>
                                <a href="{{ url('/') }}" style="color:#A78BFA;text-decoration:none;">poprockavenue.nl</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

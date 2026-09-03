<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark only">
    <meta name="supported-color-schemes" content="dark only">
    <title>{{ $title ?? 'POP/ROCK AVENUE' }}</title>
    <style type="text/css">
        :root { color-scheme: dark only; }
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
        img { border: 0; outline: none; text-decoration: none; }
        /* Force dark in clients that try to invert */
        u + .body .force-dark { background-color: #08090D !important; }
        .force-card { background-color: #11131A !important; }
        .force-ink { color: #F8FAFC !important; }
        .force-muted { color: #94A3B8 !important; }
        .force-cyan { color: #22D3EE !important; }
        .force-purple { color: #A78BFA !important; }
    </style>
    <!--[if mso]>
    <style type="text/css">
        body, table, td { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
</head>
<body class="body force-dark" bgcolor="#08090D" style="margin:0;padding:0;background-color:#08090D;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#08090D" class="force-dark" style="background-color:#08090D;width:100%;">
        <tr>
            <td align="center" bgcolor="#08090D" style="background-color:#08090D;padding:32px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#11131A" class="force-card" style="max-width:560px;width:100%;background-color:#11131A;border:1px solid #2A2D3A;">
                    {{-- Header / logo --}}
                    <tr>
                        <td bgcolor="#16132A" style="padding:28px 32px 20px;background-color:#16132A;border-bottom:1px solid #2A2D3A;">
                            <a href="{{ rtrim(config('app.url'), '/') }}/" style="text-decoration:none;">
                                <span style="font-size:18px;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:#F8FAFC;">POP<span style="color:#22D3EE;">/</span>ROCK</span>
                                <span style="font-size:18px;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:#22D3EE;">&nbsp;AVENUE</span>
                            </a>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top:12px;">
                                <tr>
                                    <td width="36" height="3" bgcolor="#7C3AED" style="font-size:0;line-height:0;background-color:#7C3AED;">&nbsp;</td>
                                    <td width="36" height="3" bgcolor="#22D3EE" style="font-size:0;line-height:0;background-color:#22D3EE;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td bgcolor="#11131A" class="force-card force-ink" style="padding:28px 32px 8px;background-color:#11131A;color:#F8FAFC;">
                            {{ $slot }}
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td bgcolor="#11131A" style="padding:20px 32px 28px;background-color:#11131A;border-top:1px solid #2A2D3A;">
                            <p class="force-cyan" style="margin:0 0 6px;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#22D3EE;">
                                POP/ROCK AVENUE
                            </p>
                            <p class="force-muted" style="margin:0;font-size:13px;line-height:1.5;color:#94A3B8;">
                                Live cover band · Netherlands<br>
                                <a href="{{ rtrim(config('app.url'), '/') }}/" class="force-purple" style="color:#A78BFA;text-decoration:none;">poprockavenue.nl</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

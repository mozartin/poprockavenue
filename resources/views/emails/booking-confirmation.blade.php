@component('emails.layout', ['title' => __('site.emails.confirmation_subject')])
    <p style="margin:0 0 8px;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;color:#A78BFA;">
        Booking request received
    </p>

    <h1 style="margin:0 0 16px;font-size:26px;line-height:1.25;font-weight:700;color:#F8FAFC;">
        Thank you, {{ $booking->name }}!
    </h1>

    <p style="margin:0 0 22px;font-size:15px;line-height:1.65;color:#94A3B8;">
        We’ve got your request and will check availability for your event.
        Expect a personal reply within <strong style="color:#F8FAFC;">24 hours</strong>.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;background-color:#08090D;border:1px solid rgba(34,211,238,0.2);border-radius:12px;">
        <tr>
            <td style="padding:18px 20px;">
                <p style="margin:0 0 12px;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:#22D3EE;">
                    Your event
                </p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.7;color:#F8FAFC;">
                    <tr>
                        <td style="padding:4px 0;color:#94A3B8;width:110px;">Type</td>
                        <td style="padding:4px 0;">{{ $booking->event_type }}</td>
                    </tr>
                    @if ($booking->event_date)
                        <tr>
                            <td style="padding:4px 0;color:#94A3B8;">Date</td>
                            <td style="padding:4px 0;">{{ $booking->event_date->format('d M Y') }}</td>
                        </tr>
                    @endif
                    @if ($booking->location)
                        <tr>
                            <td style="padding:4px 0;color:#94A3B8;">Location</td>
                            <td style="padding:4px 0;">{{ $booking->location }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 24px;font-size:15px;line-height:1.65;color:#94A3B8;">
        Urgent date? Call us and we’ll move faster.
        We can’t wait to help fill your dance floor.
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 8px;">
        <tr>
            <td style="border-radius:8px;background:linear-gradient(90deg,#7C3AED,#22D3EE);">
                <a href="{{ rtrim(config('app.url'), '/') }}/" style="display:inline-block;padding:14px 22px;font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;color:#FFFFFF;">
                    Visit the website
                </a>
            </td>
        </tr>
    </table>
@endcomponent

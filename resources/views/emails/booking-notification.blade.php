@component('emails.layout', ['title' => __('site.emails.notification_subject', ['name' => $booking->name])])
    <p style="margin:0 0 8px;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;color:#F43F5E;">
        New inquiry
    </p>

    <h1 class="force-ink" style="margin:0 0 16px;font-size:26px;line-height:1.25;font-weight:700;color:#F8FAFC;">
        New booking request
    </h1>

    <p class="force-muted" style="margin:0 0 22px;font-size:15px;line-height:1.65;color:#94A3B8;">
        A new booking inquiry just landed from
        <strong class="force-ink" style="color:#F8FAFC;">{{ $booking->name }}</strong>.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#08090D" style="margin:0 0 24px;background-color:#08090D;border:1px solid #3B2A66;">
        <tr>
            <td bgcolor="#08090D" style="padding:18px 20px;background-color:#08090D;">
                <p class="force-purple" style="margin:0 0 12px;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:#A78BFA;">
                    Contact &amp; event
                </p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px;line-height:1.7;">
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;width:110px;">Name</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">{{ $booking->name }}</td>
                    </tr>
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;">Email</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">
                            <a href="mailto:{{ $booking->email }}" class="force-cyan" style="color:#22D3EE;text-decoration:none;">{{ $booking->email }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;">Phone</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">
                            @if ($booking->phone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $booking->phone) }}" class="force-cyan" style="color:#22D3EE;text-decoration:none;">{{ $booking->phone }}</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;">Type</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">{{ $booking->event_type }}</td>
                    </tr>
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;">Date</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">{{ $booking->event_date?->format('d M Y') ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;">Location</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">{{ $booking->location ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="force-muted" style="padding:4px 0;color:#94A3B8;">Guests</td>
                        <td class="force-ink" style="padding:4px 0;color:#F8FAFC;">{{ $booking->guests ?: '—' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if ($booking->message)
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#08090D" style="margin:0 0 24px;background-color:#08090D;border:1px solid #1F4A55;">
            <tr>
                <td bgcolor="#08090D" style="padding:18px 20px;background-color:#08090D;">
                    <p class="force-cyan" style="margin:0 0 10px;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:#22D3EE;">
                        Message
                    </p>
                    <p class="force-ink" style="margin:0;font-size:14px;line-height:1.7;color:#F8FAFC;white-space:pre-wrap;">{{ $booking->message }}</p>
                </td>
            </tr>
        </table>
    @endif

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 8px;">
        <tr>
            <td bgcolor="#7C3AED" style="border-radius:8px;background-color:#7C3AED;">
                <a href="{{ url('/admin/bookings/'.$booking->id.'/edit') }}" style="display:inline-block;padding:14px 22px;font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;color:#FFFFFF;">
                    Open in admin
                </a>
            </td>
        </tr>
    </table>
@endcomponent

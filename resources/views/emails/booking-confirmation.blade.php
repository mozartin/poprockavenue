<x-mail::message>
# Thank you, {{ $booking->name }}!

We received your booking request and will check availability for your event.

**Event type:** {{ $booking->event_type }}  
@if ($booking->event_date)
**Event date:** {{ $booking->event_date->format('d M Y') }}  
@endif
@if ($booking->location)
**Location:** {{ $booking->location }}  
@endif

We aim to respond within **24 hours**. If your event is urgent, feel free to call us directly.

<x-mail::button :url="url('/contact')">
Visit Our Website
</x-mail::button>

We can't wait to help fill your dance floor.

Pop Rock Avenue
</x-mail::message>

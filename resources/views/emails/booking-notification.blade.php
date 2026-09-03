<x-mail::message>
# New Booking Request

A new booking inquiry has been submitted.

**Name:** {{ $booking->name }}  
**Email:** {{ $booking->email }}  
**Phone:** {{ $booking->phone ?: '—' }}  
**Event type:** {{ $booking->event_type }}  
**Event date:** {{ $booking->event_date?->format('d M Y') ?: '—' }}  
**Location:** {{ $booking->location ?: '—' }}  
**Guests:** {{ $booking->guests ?: '—' }}

@if ($booking->message)
**Message:**

{{ $booking->message }}
@endif

<x-mail::button :url="url('/admin/bookings/'.$booking->id.'/edit')">
View in Admin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingConfirmation;
use App\Mail\BookingNotification;
use App\Models\Booking;
use App\Services\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $key = 'booking:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->withInput()
                ->withErrors(['email' => __('site.validation.too_many_requests', ['seconds' => $seconds])]);
        }

        RateLimiter::hit($key, 3600);

        $booking = Booking::create([
            ...$request->safe()->except(['website']),
            'status' => BookingStatus::New,
            'ip_address' => $request->ip(),
        ]);

        $bookingEmail = SiteSettings::bookingEmail();

        Mail::to($bookingEmail)->send(new BookingNotification($booking));
        Mail::to($booking->email)->send(new BookingConfirmation($booking));

        return redirect()
            ->to(localized_route('contact'))
            ->with('success', site_t('contact.success'));
    }
}

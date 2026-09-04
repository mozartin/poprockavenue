<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use App\Models\LiveEvent;
use App\Models\MediaMoment;
use App\Models\Testimonial;
use App\Services\SiteSettings;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'eventTypes' => EventType::active()->featured()->ordered()->get(),
            'liveEvents' => LiveEvent::active()->featured()->upcoming()->ordered()->get(),
            'testimonials' => Testimonial::active()->featured()->ordered()->limit(3)->get(),
            'mediaMoments' => MediaMoment::active()->featured()->ordered()->limit(8)->get(),
            'settings' => SiteSettings::all(),
            'stats' => SiteSettings::stats(),
        ]);
    }
}

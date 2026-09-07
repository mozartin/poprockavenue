<?php

namespace App\Http\Controllers;

use App\Models\BandMember;
use App\Models\EventType;
use App\Models\MediaMoment;
use App\Models\RepertoireCategory;
use App\Models\Testimonial;
use App\Services\SiteSettings;

class PageController extends Controller
{
    public function band()
    {
        return view('pages.band', [
            'members' => BandMember::active()->ordered()->get(),
            'settings' => SiteSettings::all(),
        ]);
    }

    public function event()
    {
        // Do not type-hint $event: {locale} would be injected into it and look up slug "en".
        $slug = request()->route()->defaults['event'] ?? null;
        abort_unless(is_string($slug) && $slug !== '', 404);

        $event = EventType::query()->where('slug', $slug)->firstOrFail();
        abort_unless($event->is_active, 404);

        return view('pages.event', [
            'event' => $event,
            'settings' => SiteSettings::all(),
        ]);
    }

    public function repertoire()
    {
        return view('pages.repertoire', [
            'categories' => RepertoireCategory::active()
                ->ordered()
                ->with(['songs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
                ->get(),
            'settings' => SiteSettings::all(),
        ]);
    }

    public function media()
    {
        return view('pages.media', [
            'mediaMoments' => MediaMoment::active()->ordered()->get(),
            'settings' => SiteSettings::all(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'eventTypes' => EventType::active()->ordered()->get(),
            'settings' => SiteSettings::all(),
        ]);
    }

    public function testimonials()
    {
        return view('pages.testimonials', [
            'testimonials' => Testimonial::active()->ordered()->get(),
            'settings' => SiteSettings::all(),
        ]);
    }
}

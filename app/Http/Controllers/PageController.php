<?php

namespace App\Http\Controllers;

use App\Models\BandMember;
use App\Models\EventType;
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

    public function event(string $event)
    {
        $event = EventType::query()->where('slug', $event)->firstOrFail();
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

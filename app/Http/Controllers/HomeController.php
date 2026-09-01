<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use App\Models\RepertoireCategory;
use App\Models\Testimonial;
use App\Services\SiteSettings;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'eventTypes' => EventType::active()->featured()->ordered()->get(),
            'testimonials' => Testimonial::active()->featured()->ordered()->limit(3)->get(),
            'repertoireCategories' => RepertoireCategory::active()
                ->ordered()
                ->with(['songs' => fn ($q) => $q->where('is_active', true)->where('is_featured', true)->orderBy('sort_order')])
                ->get(),
            'settings' => SiteSettings::all(),
            'stats' => SiteSettings::stats(),
        ]);
    }
}

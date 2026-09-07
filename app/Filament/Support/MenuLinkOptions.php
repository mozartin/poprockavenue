<?php

namespace App\Filament\Support;

class MenuLinkOptions
{
    /**
     * @return array<string, string>
     */
    public static function routes(): array
    {
        return [
            'home' => 'Home',
            'about' => 'About Us',
            'media' => 'Live Moments',
            'repertoire' => 'Repertoire',
            'testimonials' => 'Testimonials',
            'contact' => 'Contact',
            'weddings' => 'Weddings page',
            'corporate' => 'Corporate page',
            'private-parties' => 'Private parties page',
            'christmas' => 'Christmas page',
        ];
    }
}

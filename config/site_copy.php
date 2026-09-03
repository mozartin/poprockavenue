<?php

/**
 * Website marketing copy editable from Filament.
 * Values are stored in site_settings as type=translatable, group=copy.
 * Keys: copy.{section}.{field}
 */
return [
    'sections' => [
        'hero' => [
            'label' => 'Hero (homepage)',
            'fields' => [
                'badge' => ['label' => 'Badge', 'type' => 'text'],
                'line_1' => ['label' => 'Headline line 1', 'type' => 'text'],
                'line_2' => ['label' => 'Headline line 2', 'type' => 'text'],
                'line_3' => ['label' => 'Headline line 3 (gradient)', 'type' => 'text'],
                'line_4' => ['label' => 'Headline line 4 (cyan)', 'type' => 'text'],
                'subtitle' => ['label' => 'Subtitle', 'type' => 'textarea'],
                'scroll' => ['label' => 'Scroll label', 'type' => 'text'],
                'image_alt' => ['label' => 'Image alt text', 'type' => 'text'],
            ],
        ],
        'nav' => [
            'label' => 'Navigation',
            'fields' => [
                'about' => ['label' => 'About', 'type' => 'text'],
                'events' => ['label' => 'Events', 'type' => 'text'],
                'media' => ['label' => 'Live Moments', 'type' => 'text'],
                'repertoire' => ['label' => 'Repertoire', 'type' => 'text'],
                'testimonials' => ['label' => 'Testimonials', 'type' => 'text'],
                'contact' => ['label' => 'Contact', 'type' => 'text'],
                'check_availability' => ['label' => 'Check availability CTA', 'type' => 'text'],
                'toggle_menu' => ['label' => 'Toggle menu (a11y)', 'type' => 'text'],
            ],
        ],
        'buttons' => [
            'label' => 'Buttons',
            'fields' => [
                'check_availability' => ['label' => 'Check availability', 'type' => 'text'],
                'watch_live' => ['label' => 'Watch live', 'type' => 'text'],
                'watch_showreel' => ['label' => 'Watch showreel', 'type' => 'text'],
                'meet_the_band' => ['label' => 'Meet the band', 'type' => 'text'],
                'view_full_repertoire' => ['label' => 'View full repertoire', 'type' => 'text'],
                'view_all_moments' => ['label' => 'View all moments', 'type' => 'text'],
                'request_custom_setlist' => ['label' => 'Request custom setlist', 'type' => 'text'],
            ],
        ],
        'marquee' => [
            'label' => 'Marquee strip',
            'fields' => [
                'festivals' => ['label' => 'Festivals', 'type' => 'text'],
                'private_parties' => ['label' => 'Private parties', 'type' => 'text'],
                'christmas' => ['label' => 'Christmas', 'type' => 'text'],
                'brand' => ['label' => 'Brand name', 'type' => 'text'],
                'live_band' => ['label' => 'Live band', 'type' => 'text'],
                'netherlands' => ['label' => 'Netherlands', 'type' => 'text'],
                'weddings' => ['label' => 'Weddings', 'type' => 'text'],
                'corporate' => ['label' => 'Corporate', 'type' => 'text'],
            ],
        ],
        'stats' => [
            'label' => 'Stats (labels)',
            'fields' => [
                'musicians' => ['label' => 'Musicians label', 'type' => 'text'],
                'events' => ['label' => 'Events label', 'type' => 'text'],
                'experience' => ['label' => 'Experience label', 'type' => 'text'],
                'guarantee' => ['label' => 'Guarantee label', 'type' => 'text'],
            ],
        ],
        'stats_values' => [
            'label' => 'Stats (numbers)',
            'fields' => [
                'musicians' => ['label' => 'Musicians value', 'type' => 'text'],
                'events' => ['label' => 'Events value', 'type' => 'text'],
                'experience' => ['label' => 'Experience value', 'type' => 'text'],
                'guarantee' => ['label' => 'Guarantee value', 'type' => 'text'],
            ],
        ],
        'live_experience' => [
            'label' => 'Live experience section',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'subtitle' => ['label' => 'Subtitle', 'type' => 'textarea'],
                'image_alt' => ['label' => 'Image alt', 'type' => 'text'],
                'play_label' => ['label' => 'Play button label', 'type' => 'text'],
            ],
        ],
        'live_moments' => [
            'label' => 'Live Moments section / page',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Home title', 'type' => 'text'],
                'subtitle' => ['label' => 'Home subtitle', 'type' => 'textarea'],
                'page_title' => ['label' => 'Page title', 'type' => 'text'],
                'page_subtitle' => ['label' => 'Page subtitle', 'type' => 'textarea'],
                'empty' => ['label' => 'Empty state', 'type' => 'text'],
            ],
        ],
        'about' => [
            'label' => 'About section / band page',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Home title', 'type' => 'text'],
                'image_alt' => ['label' => 'Image alt', 'type' => 'text'],
                'page_title' => ['label' => 'Band page title', 'type' => 'text'],
                'page_subtitle' => ['label' => 'Band page subtitle', 'type' => 'textarea'],
            ],
        ],
        'events_section' => [
            'label' => 'Events section',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
            ],
        ],
        'repertoire_section' => [
            'label' => 'Repertoire section / page',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'subtitle' => ['label' => 'Subtitle', 'type' => 'textarea'],
                'more' => ['label' => 'More songs label', 'type' => 'text'],
                'custom_title' => ['label' => 'Custom setlist title', 'type' => 'text'],
                'custom_text' => ['label' => 'Custom setlist text', 'type' => 'textarea'],
            ],
        ],
        'testimonials_section' => [
            'label' => 'Testimonials section / page',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'page_subtitle' => ['label' => 'Page subtitle', 'type' => 'textarea'],
            ],
        ],
        'booking_cta' => [
            'label' => 'Booking CTA (homepage)',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title_1' => ['label' => 'Title part 1', 'type' => 'text'],
                'title_2' => ['label' => 'Title part 2', 'type' => 'text'],
                'subtitle' => ['label' => 'Subtitle', 'type' => 'textarea'],
                'footer' => ['label' => 'Footer line', 'type' => 'text'],
            ],
        ],
        'event_page' => [
            'label' => 'Event type page CTA',
            'fields' => [
                'cta_title' => ['label' => 'CTA title', 'type' => 'text'],
                'cta_text' => ['label' => 'CTA text (use :event)', 'type' => 'textarea'],
            ],
        ],
        'contact' => [
            'label' => 'Contact page',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'subtitle' => ['label' => 'Subtitle', 'type' => 'textarea'],
                'success' => ['label' => 'Success message', 'type' => 'textarea'],
                'name' => ['label' => 'Name label', 'type' => 'text'],
                'email' => ['label' => 'Email label', 'type' => 'text'],
                'phone' => ['label' => 'Phone label', 'type' => 'text'],
                'event_type' => ['label' => 'Event type label', 'type' => 'text'],
                'event_date' => ['label' => 'Event date label', 'type' => 'text'],
                'location' => ['label' => 'Location label', 'type' => 'text'],
                'guests' => ['label' => 'Guests label', 'type' => 'text'],
                'budget' => ['label' => 'Budget label', 'type' => 'text'],
                'message' => ['label' => 'Message label', 'type' => 'text'],
                'select_event_type' => ['label' => 'Select event type', 'type' => 'text'],
                'select_budget' => ['label' => 'Select budget', 'type' => 'text'],
                'location_placeholder' => ['label' => 'Location placeholder', 'type' => 'text'],
                'message_placeholder' => ['label' => 'Message placeholder', 'type' => 'text'],
                'other' => ['label' => 'Other option', 'type' => 'text'],
                'aside_title' => ['label' => 'Aside title', 'type' => 'text'],
                'aside_note' => ['label' => 'Aside note (use :hours)', 'type' => 'textarea'],
                'honeypot' => ['label' => 'Honeypot label', 'type' => 'text'],
            ],
        ],
        'budget_options' => [
            'label' => 'Budget options',
            'fields' => [
                '1000_2000' => ['label' => '€1,000–2,000', 'type' => 'text'],
                '2000_3000' => ['label' => '€2,000–3,000', 'type' => 'text'],
                '3000_4000' => ['label' => '€3,000–4,000', 'type' => 'text'],
                '4000_plus' => ['label' => '€4,000+', 'type' => 'text'],
            ],
        ],
        'footer' => [
            'label' => 'Footer',
            'fields' => [
                'description' => ['label' => 'Description', 'type' => 'textarea'],
                'navigate' => ['label' => 'Navigate heading', 'type' => 'text'],
                'events' => ['label' => 'Events heading', 'type' => 'text'],
                'contact' => ['label' => 'Contact heading', 'type' => 'text'],
                'christmas_short' => ['label' => 'Christmas short link', 'type' => 'text'],
                'rights' => ['label' => 'Rights reserved', 'type' => 'text'],
                'based_in' => ['label' => 'Based in', 'type' => 'text'],
            ],
        ],
        'meta' => [
            'label' => 'SEO / page titles',
            'fields' => [
                'default_title' => ['label' => 'Default title', 'type' => 'text'],
                'default_description' => ['label' => 'Default description', 'type' => 'textarea'],
                'home_title' => ['label' => 'Home title', 'type' => 'text'],
                'band_title' => ['label' => 'Band title', 'type' => 'text'],
                'band_description' => ['label' => 'Band description', 'type' => 'textarea'],
                'repertoire_title' => ['label' => 'Repertoire title', 'type' => 'text'],
                'repertoire_description' => ['label' => 'Repertoire description', 'type' => 'textarea'],
                'testimonials_title' => ['label' => 'Testimonials title', 'type' => 'text'],
                'testimonials_description' => ['label' => 'Testimonials description', 'type' => 'textarea'],
                'contact_title' => ['label' => 'Contact title', 'type' => 'text'],
                'contact_description' => ['label' => 'Contact description', 'type' => 'textarea'],
            ],
        ],
    ],

    'stats_value_defaults' => [
        'musicians' => '7',
        'events' => '500+',
        'experience' => '15+',
        'guarantee' => '100%',
    ],
];

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
            'label' => 'Navigation menu',
            'description' => 'Labels for header and footer links — one item per menu entry.',
            'admin' => ['layout' => 'items'],
            'fields' => [
                'about' => ['label' => 'About', 'input_label' => 'Menu label', 'type' => 'text'],
                'services' => ['label' => 'Services', 'input_label' => 'Menu label', 'type' => 'text'],
                'events' => ['label' => 'Events', 'input_label' => 'Menu label', 'type' => 'text'],
                'media' => ['label' => 'Live Moments', 'input_label' => 'Menu label', 'type' => 'text'],
                'repertoire' => ['label' => 'Repertoire', 'input_label' => 'Menu label', 'type' => 'text'],
                'testimonials' => ['label' => 'Testimonials', 'input_label' => 'Menu label', 'type' => 'text'],
                'contact' => ['label' => 'Contact', 'input_label' => 'Menu label', 'type' => 'text'],
                'check_availability' => ['label' => 'Check availability button', 'input_label' => 'Button label', 'type' => 'text'],
                'toggle_menu' => ['label' => 'Mobile menu (accessibility)', 'input_label' => 'A11y label', 'type' => 'text'],
            ],
        ],
        'buttons' => [
            'label' => 'Buttons',
            'description' => 'CTA button labels used across the site.',
            'admin' => ['layout' => 'items'],
            'fields' => [
                'check_availability' => ['label' => 'Check availability', 'input_label' => 'Button text', 'type' => 'text'],
                'watch_live' => ['label' => 'Watch live', 'input_label' => 'Button text', 'type' => 'text'],
                'watch_showreel' => ['label' => 'Watch showreel', 'input_label' => 'Button text', 'type' => 'text'],
                'meet_the_band' => ['label' => 'Meet the band', 'input_label' => 'Button text', 'type' => 'text'],
                'view_full_repertoire' => ['label' => 'View full repertoire', 'input_label' => 'Button text', 'type' => 'text'],
                'view_all_moments' => ['label' => 'View all moments', 'input_label' => 'Button text', 'type' => 'text'],
                'request_custom_setlist' => ['label' => 'Request custom setlist', 'input_label' => 'Button text', 'type' => 'text'],
            ],
        ],
        'marquee' => [
            'label' => 'Marquee strip',
            'description' => 'Scrolling keywords under the hero.',
            'admin' => ['layout' => 'items'],
            'fields' => [
                'festivals' => ['label' => 'Festivals', 'input_label' => 'Text', 'type' => 'text'],
                'private_parties' => ['label' => 'Private parties', 'input_label' => 'Text', 'type' => 'text'],
                'christmas' => ['label' => 'Christmas', 'input_label' => 'Text', 'type' => 'text'],
                'brand' => ['label' => 'Brand name', 'input_label' => 'Text', 'type' => 'text'],
                'live_band' => ['label' => 'Live band', 'input_label' => 'Text', 'type' => 'text'],
                'netherlands' => ['label' => 'Netherlands', 'input_label' => 'Text', 'type' => 'text'],
                'weddings' => ['label' => 'Weddings', 'input_label' => 'Text', 'type' => 'text'],
                'corporate' => ['label' => 'Corporate', 'input_label' => 'Text', 'type' => 'text'],
            ],
        ],
        'stats' => [
            'label' => 'Stats (labels)',
            'admin' => ['hidden' => true],
            'fields' => [
                'musicians' => ['label' => 'Musicians label', 'type' => 'text'],
                'events' => ['label' => 'Events label', 'type' => 'text'],
                'experience' => ['label' => 'Experience label', 'type' => 'text'],
                'guarantee' => ['label' => 'Guarantee label', 'type' => 'text'],
            ],
        ],
        'stats_values' => [
            'label' => 'Stats (numbers)',
            'admin' => ['hidden' => true],
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
                'members_empty' => ['label' => 'Members empty state', 'type' => 'text'],
            ],
        ],
        'events_section' => [
            'label' => 'Events section',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'subtitle' => ['label' => 'Subtitle', 'type' => 'textarea'],
                'cta' => ['label' => 'CTA label', 'type' => 'text'],
                'empty' => ['label' => 'Empty state', 'type' => 'text'],
            ],
        ],
        'services_section' => [
            'label' => 'Services section',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'empty' => ['label' => 'Empty state', 'type' => 'text'],
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
                'empty' => ['label' => 'Empty state', 'type' => 'text'],
            ],
        ],
        'testimonials_section' => [
            'label' => 'Testimonials section / page',
            'fields' => [
                'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'page_subtitle' => ['label' => 'Page subtitle', 'type' => 'textarea'],
                'empty' => ['label' => 'Empty state', 'type' => 'text'],
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
            'description' => 'Choices shown in the booking form budget dropdown.',
            'admin' => ['layout' => 'items'],
            'fields' => [
                '1000_2000' => ['label' => '€1,000–2,000', 'input_label' => 'Option label', 'type' => 'text'],
                '2000_3000' => ['label' => '€2,000–3,000', 'input_label' => 'Option label', 'type' => 'text'],
                '3000_4000' => ['label' => '€3,000–4,000', 'input_label' => 'Option label', 'type' => 'text'],
                '4000_plus' => ['label' => '€4,000+', 'input_label' => 'Option label', 'type' => 'text'],
            ],
        ],
        'footer' => [
            'label' => 'Footer',
            'description' => 'Footer text and column headings.',
            'admin' => ['layout' => 'items'],
            'fields' => [
                'description' => ['label' => 'Band blurb', 'input_label' => 'Text', 'type' => 'textarea'],
                'navigate' => ['label' => 'Navigate column heading', 'input_label' => 'Heading', 'type' => 'text'],
                'events' => ['label' => 'Events column heading', 'input_label' => 'Heading', 'type' => 'text'],
                'services' => ['label' => 'Services column heading', 'input_label' => 'Heading', 'type' => 'text'],
                'contact' => ['label' => 'Contact column heading', 'input_label' => 'Heading', 'type' => 'text'],
                'christmas_short' => ['label' => 'Christmas short link', 'input_label' => 'Link text', 'type' => 'text'],
                'rights' => ['label' => 'Rights reserved', 'input_label' => 'Text', 'type' => 'text'],
                'based_in' => ['label' => 'Based in', 'input_label' => 'Text', 'type' => 'text'],
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
        'musicians' => '8',
        'events' => '500+',
        'experience' => '15+',
        'guarantee' => '100%',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin panels (composite items)
    |--------------------------------------------------------------------------
    | Shown first in Filament. "consumes" hides the raw sections from the
    | default list so value+label pairs appear as one card.
    */
    'admin_panels' => [
        [
            'label' => 'Homepage stats',
            'description' => 'Each card is one statistic: number + caption together (as on the homepage).',
            'expanded' => true,
            'consumes' => ['stats', 'stats_values'],
            'items' => [
                [
                    'label' => 'Musicians',
                    'active_key' => 'stats_active.musicians',
                    'parts' => [
                        ['section' => 'stats_values', 'field' => 'musicians', 'label' => 'Number', 'type' => 'text'],
                        ['section' => 'stats', 'field' => 'musicians', 'label' => 'Caption', 'type' => 'text'],
                    ],
                ],
                [
                    'label' => 'Events performed',
                    'active_key' => 'stats_active.events',
                    'parts' => [
                        ['section' => 'stats_values', 'field' => 'events', 'label' => 'Number', 'type' => 'text'],
                        ['section' => 'stats', 'field' => 'events', 'label' => 'Caption', 'type' => 'text'],
                    ],
                ],
                [
                    'label' => 'Years experience',
                    'active_key' => 'stats_active.experience',
                    'parts' => [
                        ['section' => 'stats_values', 'field' => 'experience', 'label' => 'Number', 'type' => 'text'],
                        ['section' => 'stats', 'field' => 'experience', 'label' => 'Caption', 'type' => 'text'],
                    ],
                ],
                [
                    'label' => 'Dance floor guarantee',
                    'active_key' => 'stats_active.guarantee',
                    'parts' => [
                        ['section' => 'stats_values', 'field' => 'guarantee', 'label' => 'Number', 'type' => 'text'],
                        ['section' => 'stats', 'field' => 'guarantee', 'label' => 'Caption', 'type' => 'text'],
                    ],
                ],
            ],
        ],
    ],
];

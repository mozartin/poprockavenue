<?php

namespace Database\Seeders;

use App\Models\BandMember;
use App\Models\EventType;
use App\Models\RepertoireCategory;
use App\Models\RepertoireSong;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\SiteCopy;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected function t(string $en, string $nl, string $uk, ?string $ru = null): array
    {
        return [
            'en' => $en,
            'nl' => $nl,
            'uk' => $uk,
            'ru' => $ru ?? $uk,
        ];
    }

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@poprockavenue.nl'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        $this->seedSiteSettings();
        SiteCopy::seedFromLang();
        $this->seedEventTypes();
        $this->seedTestimonials();
        $this->seedRepertoire();
        $this->seedBandMembers();
    }

    protected function seedSiteSettings(): void
    {
        $settings = [
            ['key' => 'booking_email', 'value' => 'booking@poprockavenue.nl', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'booking@poprockavenue.nl', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'phone', 'value' => '+31 6 12 345 678', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'location', 'value' => json_encode($this->t('Netherlands', 'Nederland', 'Нідерланди', 'Нидерланды')), 'type' => 'translatable', 'group' => 'contact'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/poprockavenue', 'type' => 'text', 'group' => 'social'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/poprockavenue', 'type' => 'text', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@poprockavenue', 'type' => 'text', 'group' => 'social'],
            ['key' => 'showreel_url', 'value' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'type' => 'text', 'group' => 'media'],
            ['key' => 'hero_image', 'value' => '/images/hero.jpg', 'type' => 'text', 'group' => 'media'],
            ['key' => 'about_image', 'value' => '/images/about.jpg', 'type' => 'text', 'group' => 'media'],
            ['key' => 'live_video_image', 'value' => '/images/live-video.jpg', 'type' => 'text', 'group' => 'media'],
            ['key' => 'cta_background_image', 'value' => '/images/cta-bg.jpg', 'type' => 'text', 'group' => 'media'],
            ['key' => 'kvk', 'value' => '12345678', 'type' => 'text', 'group' => 'legal'],
            ['key' => 'about_quote', 'value' => json_encode($this->t(
                "We don't just play music — we create the atmosphere where memories get made.",
                'Wij spelen niet alleen muziek — wij creëren de sfeer waarin herinneringen ontstaan.',
                'Ми не просто граємо музику — ми створюємо атмосферу, в якій народжуються спогади.'
            )), 'type' => 'translatable', 'group' => 'content'],
            ['key' => 'about_paragraph_1', 'value' => json_encode($this->t(
                'For over 15 years, Pop Rock Avenue has been the go-to live cover band for unforgettable events across the Netherlands. Seven seasoned musicians, one mission: fill your dance floor.',
                'Al meer dan 15 jaar is Pop Rock Avenue dé live coverband voor onvergetelijke events in heel Nederland. Zeven ervaren muzikanten, één missie: jouw dansvloer vullen.',
                'Понад 15 років Pop Rock Avenue — це live cover band для незабутніх подій у Нідерландах. Сім досвідчених музикантів, одна місія: заповнити ваш танцпол.'
            )), 'type' => 'translatable', 'group' => 'content'],
            ['key' => 'about_paragraph_2', 'value' => json_encode($this->t(
                'From intimate wedding receptions to large-scale corporate galas and festival stages, we bring the energy, professionalism and setlist versatility that keeps guests dancing all night.',
                'Van intieme bruiloften tot grootschalige galas en festivalpodia — wij brengen de energie, professionaliteit en veelzijdigheid die gasten de hele nacht laten dansen.',
                'Від камерних весіль до великих корпоративів і фестивалів — ми приносимо енергію, професіоналізм і універсальність сетлисту, що тримає гостей на танцполі всю ніч.'
            )), 'type' => 'translatable', 'group' => 'content'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    protected function seedEventTypes(): void
    {
        $events = [
            [
                'slug' => 'weddings',
                'name' => $this->t('Weddings', 'Bruiloften', 'Весілля'),
                'title' => $this->t('Weddings', 'Bruiloften', 'Весілля'),
                'subtitle' => $this->t(
                    'Making your first dance a moment forever remembered',
                    'Jouw eerste dans, een moment voor altijd',
                    'Ваш перший танець — момент, який запам\'ятається назавжди'
                ),
                'description' => $this->t(
                    'Making your first dance a moment forever remembered',
                    'Jouw eerste dans, een moment voor altijd',
                    'Ваш перший танець — момент, який запам\'ятається назавжди'
                ),
                'content' => $this->t(
                    '<p>Your wedding day deserves a soundtrack as unforgettable as the moment itself. Pop Rock Avenue brings seven musicians, a packed dance floor, and the professionalism your celebration deserves.</p>',
                    '<p>Jouw trouwdag verdient een soundtrack die net zo onvergetelijk is als het moment zelf. Pop Rock Avenue brengt zeven muzikanten, een volle dansvloer en de professionaliteit die jouw viering verdient.</p>',
                    '<p>Ваш весільний день заслуговує на саундтрек, не менш незабутній, ніж сам момент. Pop Rock Avenue — сім музикантів, заповнений танцпол і професіоналізм, якого заслуговує ваше свято.</p>'
                ),
                'meta_title' => $this->t(
                    'Wedding Band Netherlands | Pop Rock Avenue',
                    'Bruiloft Band Nederland | Pop Rock Avenue',
                    'Весільний гурт Нідерланди | Pop Rock Avenue'
                ),
                'meta_description' => $this->t(
                    'Premium 7-piece live cover band for weddings across the Netherlands.',
                    'Premium 7-koppig live coverband voor bruiloften in heel Nederland.',
                    'Преміальний 7-учасний live cover band для весіль у Нідерландах.'
                ),
                'image' => '/images/events/weddings.jpg',
                'hero_image' => '/images/events/weddings.jpg',
                'accent_color' => '#7C3AED',
                'sort_order' => 1,
            ],
            [
                'slug' => 'corporate-events',
                'name' => $this->t('Corporate', 'Zakelijk', 'Корпоративи'),
                'title' => $this->t('Corporate', 'Zakelijk', 'Корпоративи'),
                'subtitle' => $this->t(
                    'Premium live entertainment for business events',
                    'Premium live entertainment voor zakelijke events',
                    'Преміальне живе шоу для бізнес-заходів'
                ),
                'description' => $this->t(
                    'Premium live entertainment for business events',
                    'Premium live entertainment voor zakelijke events',
                    'Преміальне живе шоу для бізнес-заходів'
                ),
                'content' => $this->t(
                    '<p>Elevate your corporate event with a professional live band that understands the balance between sophistication and energy.</p>',
                    '<p>Til je bedrijfsevent naar een hoger niveau met een professionele liveband die de balans tussen elegantie en energie begrijpt.</p>',
                    '<p>Підніміть ваш корпоратив на новий рівень із професійним live band, який розуміє баланс між елегантністю та енергією.</p>'
                ),
                'meta_title' => $this->t('Corporate Event Band | Pop Rock Avenue', 'Bedrijfsevent Band | Pop Rock Avenue', 'Корпоративний гурт | Pop Rock Avenue'),
                'meta_description' => $this->t(
                    'Professional live cover band for corporate events across the Netherlands.',
                    'Professionele live coverband voor bedrijfsevents in heel Nederland.',
                    'Професійний live cover band для корпоративів у Нідерландах.'
                ),
                'image' => '/images/events/corporate.jpg',
                'hero_image' => '/images/events/corporate.jpg',
                'accent_color' => '#22D3EE',
                'sort_order' => 2,
            ],
            [
                'slug' => 'private-parties',
                'name' => $this->t('Private Parties', 'Privéfeesten', 'Приватні вечірки'),
                'title' => $this->t('Private Parties', 'Privéfeesten', 'Приватні вечірки'),
                'subtitle' => $this->t(
                    'Turning every celebration into a story to tell',
                    'Elke viering wordt een verhaal om te vertellen',
                    'Перетворюємо кожне свято на історію, яку хочеться розповідати'
                ),
                'description' => $this->t(
                    'Turning every celebration into a story to tell',
                    'Elke viering wordt een verhaal om te vertellen',
                    'Перетворюємо кожне свято на історію, яку хочеться розповідати'
                ),
                'content' => $this->t(
                    '<p>Birthdays, anniversaries, garden parties or milestone celebrations — we bring the energy that turns a great party into a legendary one.</p>',
                    '<p>Verjaardagen, jubilea, tuinfeesten of mijlpaalvieringen — wij brengen de energie die een goed feest legendarisch maakt.</p>',
                    '<p>Дні народження, ювілеї, садові вечірки чи знакові свята — ми приносимо енергію, що робить вечірку легендарною.</p>'
                ),
                'meta_title' => $this->t('Private Party Band | Pop Rock Avenue', 'Privéfeest Band | Pop Rock Avenue', 'Гурт для приватних вечірок | Pop Rock Avenue'),
                'meta_description' => $this->t(
                    'Live cover band for private parties across the Netherlands.',
                    'Live coverband voor privéfeesten in heel Nederland.',
                    'Live cover band для приватних вечірок у Нідерландах.'
                ),
                'image' => '/images/events/private-parties.jpg',
                'hero_image' => '/images/events/private-parties.jpg',
                'accent_color' => '#F43F5E',
                'sort_order' => 3,
            ],
            [
                'slug' => 'christmas-new-year',
                'name' => $this->t('Christmas & New Year', 'Kerst & Oud & Nieuw', 'Різдво та Новий рік'),
                'title' => $this->t('Christmas & New Year', 'Kerst & Oud & Nieuw', 'Різдво та Новий рік'),
                'subtitle' => $this->t(
                    'End the year on the absolute highest note',
                    'Sluit het jaar af op het hoogste niveau',
                    'Завершіть рік на найвищій ноті'
                ),
                'description' => $this->t(
                    'End the year on the absolute highest note',
                    'Sluit het jaar af op het hoogste niveau',
                    'Завершіть рік на найвищій ноті'
                ),
                'content' => $this->t(
                    '<p>Make your festive season celebration unforgettable with a live band that knows how to bring the party.</p>',
                    '<p>Maak je feestdagen onvergetelijk met een liveband die weet hoe je een feest bouwt.</p>',
                    '<p>Зробіть святковий сезон незабутнім із live band, який знає, як розігріти вечірку.</p>'
                ),
                'meta_title' => $this->t('Christmas & NYE Band | Pop Rock Avenue', 'Kerst & Oud & Nieuw Band | Pop Rock Avenue', 'Різдвяний гурт | Pop Rock Avenue'),
                'meta_description' => $this->t(
                    'Live cover band for Christmas and New Year celebrations.',
                    'Live coverband voor kerst- en oud & nieuwfeesten.',
                    'Live cover band для різдвяних та новорічних свят.'
                ),
                'image' => '/images/events/christmas.jpg',
                'hero_image' => '/images/events/christmas.jpg',
                'accent_color' => '#22D3EE',
                'sort_order' => 4,
            ],
        ];

        foreach ($events as $event) {
            EventType::query()->updateOrCreate(['slug' => $event['slug']], $event);
        }
    }

    protected function seedTestimonials(): void
    {
        $testimonials = [
            [
                'quote' => $this->t(
                    'The dance floor was full from the very first song. Our guests still talk about it. Pop Rock Avenue made our wedding night truly unforgettable.',
                    'De dansvloer was vanaf het eerste nummer vol. Onze gasten hebben het er nog steeds over. Pop Rock Avenue maakte onze trouwavond onvergetelijk.',
                    'Танцпол був повний з першої пісні. Гості досі про це говорять. Pop Rock Avenue зробив наше весілля справді незабутнім.'
                ),
                'author' => 'Sophie & Thomas Bakker',
                'event_type' => $this->t('Wedding', 'Bruiloft', 'Весілля'),
                'location' => 'Amstelveen',
                'year' => 2024,
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'quote' => $this->t(
                    'Hired them for our company\'s 25th anniversary gala. Perfectly professional and impossibly energetic — 300 guests, zero empty seats on the dance floor.',
                    'Geboekt voor ons 25-jarig jubileum. Perfect professioneel en ongelooflijk energiek — 300 gasten, geen lege plek op de dansvloer.',
                    'Замовили на 25-річний ювілей компанії. Ідеальний професіоналізм і неймовірна енергія — 300 гостей, жодного вільного місця на танцполі.'
                ),
                'author' => 'Mark de Vries',
                'event_type' => $this->t('Corporate Gala', 'Zakelijk Gala', 'Корпоративне гала'),
                'location' => 'Amsterdam',
                'year' => 2024,
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'quote' => $this->t(
                    'Second time booking them and even better than the first. They read the crowd perfectly and kept the energy sky-high from start to finish.',
                    'Tweede keer geboekt en nog beter dan de eerste. Ze voelen het publiek perfect aan en hielden de energie van begin tot eind hoog.',
                    'Бронювали вдруге — і ще краще, ніж уперше. Вони ідеально читають зал і тримають енергію на максимумі від початку до кінця.'
                ),
                'author' => 'Lena Janssen',
                'event_type' => $this->t('Private Party', 'Privéfeest', 'Приватна вечірка'),
                'location' => 'Rotterdam',
                'year' => 2025,
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::query()->create($testimonial);
        }
    }

    protected function seedRepertoire(): void
    {
        $categories = [
            ['name' => $this->t('Pop', 'Pop', 'Поп'), 'slug' => 'pop', 'accent_color' => '#22D3EE', 'sort_order' => 1, 'artists' => ['Bruno Mars', 'Dua Lipa', 'The Weeknd', 'Adele', 'Harry Styles']],
            ['name' => $this->t('Rock', 'Rock', 'Рок'), 'slug' => 'rock', 'accent_color' => '#7C3AED', 'sort_order' => 2, 'artists' => ['Coldplay', 'U2', 'Foo Fighters', 'Pearl Jam', 'Muse']],
            ['name' => $this->t('Dance', 'Dance', 'Денс'), 'slug' => 'dance', 'accent_color' => '#F43F5E', 'sort_order' => 3, 'artists' => ['Daft Punk', 'Calvin Harris', 'David Guetta', 'Avicii']],
            ['name' => $this->t('80s', 'Jaren 80', '80-ті'), 'slug' => '80s', 'accent_color' => '#22D3EE', 'sort_order' => 4, 'artists' => ['Michael Jackson', 'Prince', 'Toto', 'A-ha', 'Queen']],
            ['name' => $this->t('90s', 'Jaren 90', '90-ті'), 'slug' => '90s', 'accent_color' => '#7C3AED', 'sort_order' => 5, 'artists' => ["Destiny's Child", 'Oasis', 'TLC', 'No Doubt', 'Seal']],
            ['name' => $this->t('Classics', 'Klassiekers', 'Класика'), 'slug' => 'classics', 'accent_color' => '#EAB308', 'sort_order' => 6, 'artists' => ['Frank Sinatra', 'Stevie Wonder', 'ABBA', 'Earth Wind & Fire']],
        ];

        foreach ($categories as $data) {
            $category = RepertoireCategory::query()->updateOrCreate(
                ['slug' => $data['slug']],
                ['name' => $data['name'], 'accent_color' => $data['accent_color'], 'sort_order' => $data['sort_order']]
            );

            foreach ($data['artists'] as $sortOrder => $artist) {
                RepertoireSong::query()->updateOrCreate(
                    ['repertoire_category_id' => $category->id, 'artist' => $artist],
                    ['is_featured' => true, 'sort_order' => $sortOrder + 1]
                );
            }
        }
    }

    protected function seedBandMembers(): void
    {
        $roles = [
            $this->t('Lead Vocals', 'Leadzang', 'Головний вокал'),
            $this->t('Lead Guitar', 'Leadgitaar', 'Солова гітара'),
            $this->t('Bass Guitar', 'Basgitaar', 'Бас-гітара'),
            $this->t('DJ & Electronic Percussion', 'DJ & Elektronisch Slagwerk', 'DJ та електронна перкусія'),
            $this->t('Backing Vocals & Keys', 'Backing Vocals & Keys', 'Бек-вокал та клавіші'),
            $this->t('Rhythm Guitar', 'Ritmegitaar', 'Ритм-гітара'),
            $this->t('Drums', 'Drums', 'Ударні'),
        ];

        foreach ($roles as $index => $role) {
            BandMember::query()->updateOrCreate(
                ['sort_order' => $index + 1],
                ['role' => $role]
            );
        }
    }
}

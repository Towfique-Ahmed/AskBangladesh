<?php
/**
 * SEO layer: per-page titles and descriptions, canonical URLs, Open Graph,
 * Twitter cards, breadcrumbs and JSON-LD structured data.
 *
 * Titles aim for roughly 50–60 characters and descriptions for 140–160, the
 * range Google renders without truncating on most result pages.
 */

declare(strict_types=1);

defined('APP_ROOT') || exit('Direct access is not permitted.');

/**
 * Trim a meta description to a length search engines render in full,
 * cutting on a word boundary rather than mid-word.
 */
function bd_trim_meta(string $text, int $limit): string
{
    $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    if (mb_strlen($text) <= $limit) {
        return $text;
    }

    $cut   = mb_substr($text, 0, $limit);
    $space = mb_strrpos($cut, ' ');
    if ($space !== false && $space > $limit * 0.6) {
        $cut = mb_substr($cut, 0, $space);
    }
    return rtrim($cut, " ,.;:—-") . '…';
}

/**
 * Normalise a page's SEO options into everything the <head> needs.
 *
 * @param array{
 *   title?:string, description?:string, path?:string, type?:string,
 *   noindex?:bool, breadcrumbs?:array, jsonld?:array, keywords?:string
 * } $opts
 */
function bd_seo(array $opts = []): array
{
    $title = trim((string) ($opts['title'] ?? APP_NAME));
    $path  = (string) ($opts['path'] ?? bd_request_path());

    /*
     * Search results truncate titles at roughly 60 characters, so the brand
     * suffix is appended only when it still fits. Long entity titles keep
     * their own words rather than losing them to the brand.
     */
    $suffix    = ' | ' . APP_NAME;
    $fullTitle = str_contains($title, APP_NAME) ? $title : $title . $suffix;
    if (mb_strlen($fullTitle) > 60) {
        $fullTitle = $title;
    }

    return [
        'title'       => $title,
        'full_title'  => $fullTitle,
        'description' => bd_trim_meta((string) ($opts['description'] ?? APP_TAGLINE), 158),
        'canonical'   => bd_abs_url($path),
        'type'        => $opts['type'] ?? 'website',
        'noindex'     => (bool) ($opts['noindex'] ?? false),
        'keywords'    => $opts['keywords'] ?? '',
        'breadcrumbs' => $opts['breadcrumbs'] ?? [],
        'jsonld'      => $opts['jsonld'] ?? [],
    ];
}

/**
 * Static per-page metadata. Detail pages build their own in the page file,
 * because their copy depends on the entity being shown.
 */
function bd_page_seo(string $page): array
{
    $meta = [
        'home' => [
            'title'       => 'AskBangladesh — Everything About Bangladesh',
            'description' => 'Explore Bangladesh in one place: an interactive map of all 64 districts, live prayer times, gold rates, currency conversion, travel guides and government services.',
            'keywords'    => 'Bangladesh, Bangladesh information, 64 districts, Bangladesh map, Bangladesh travel',
            'path'        => '',
        ],
        'map' => [
            'title'       => 'Bangladesh Map — 64 Districts & Landmarks',
            'description' => 'Explore an interactive map of Bangladesh with all 64 districts, the highest peaks, top travel destinations, airports and seaports. Pan, zoom and tap any pin.',
            'keywords'    => 'Bangladesh map, district map of Bangladesh, interactive Bangladesh map',
            'path'        => 'map',
        ],
        'districts' => [
            'title'       => 'All 64 Districts of Bangladesh by Division',
            'description' => 'Complete list of the 64 districts of Bangladesh grouped by all 8 divisions, with Bangla names, area, population and what each district is famous for.',
            'keywords'    => '64 districts of Bangladesh, Bangladesh districts list, divisions of Bangladesh',
            'path'        => 'districts',
        ],
        'geography' => [
            'title'       => 'Geography of Bangladesh — Delta & Climate',
            'description' => 'Which continent Bangladesh is on, the Ganges–Brahmaputra–Meghna delta, its six seasons, land borders, coastline and the four extreme points of the country.',
            'keywords'    => 'geography of Bangladesh, which continent is Bangladesh in, Bangladesh climate',
            'path'        => 'geography',
        ],
        'mountains' => [
            'title'       => 'Mountains of Bangladesh — Highest Peaks',
            'description' => 'Every major peak in Bangladesh ranked by height, from Saka Haphong at 1,052 m down through Keokradong and Tazing Dong, with districts and trekking notes.',
            'keywords'    => 'highest mountain in Bangladesh, Saka Haphong, Keokradong, Tazing Dong',
            'path'        => 'mountains',
        ],
        'rivers' => [
            'title'       => 'Rivers of Bangladesh — Padma & Jamuna',
            'description' => 'The major rivers of Bangladesh with their lengths and why each matters, from the Padma, Jamuna and Meghna to the Karnaphuli, Teesta and Halda.',
            'keywords'    => 'rivers of Bangladesh, Padma river, Jamuna river, Meghna river',
            'path'        => 'rivers',
        ],
        'travel' => [
            'title'       => 'Best Places to Visit in Bangladesh',
            'description' => 'The best places to visit in Bangladesh: Cox’s Bazar, the Sundarbans, Sajek Valley, Sreemangal tea gardens and more, with the best season for each trip.',
            'keywords'    => 'places to visit in Bangladesh, Bangladesh tourism, Cox\'s Bazar, Sundarbans',
            'path'        => 'travel',
        ],
        'transport' => [
            'title'       => 'Transport in Bangladesh — Roads & Fares',
            'description' => 'How to get around Bangladesh: national highways, train and bus fares, rickshaws and CNGs, launches, the Dhaka metro, airports, seaports and megaprojects.',
            'keywords'    => 'transport in Bangladesh, Bangladesh highways, bus fare, train ticket Bangladesh',
            'path'        => 'transport',
        ],
        'time' => [
            'title'       => 'Current Time in Bangladesh — Live Clock',
            'description' => 'What time is it in Bangladesh right now? Live Bangladesh Standard Time (UTC+6), a world clock for 34 cities and a two-way time zone converter.',
            'keywords'    => 'time in Bangladesh, Bangladesh Standard Time, BST, Dhaka time now',
            'path'        => 'time',
        ],
        'currency' => [
            'title'       => 'BDT Converter — Taka Exchange Rates',
            'description' => 'Convert the Bangladeshi Taka against the US dollar, euro, pound, riyal, dirham and 27 more currencies, with a full BDT exchange rate table.',
            'keywords'    => 'BDT converter, taka to dollar, Bangladeshi Taka exchange rate, USD to BDT',
            'path'        => 'currency',
        ],
        'gold' => [
            'title'       => 'Gold Price in Bangladesh Today — 22K & 21K',
            'description' => 'Today’s gold price in Bangladesh for 22 karat, 21 karat, 18 karat and traditional gold per bhori, gram and ana, plus silver rates and a weight converter.',
            'keywords'    => 'gold price in Bangladesh, gold price today, 22k gold price bhori, BAJUS gold rate',
            'path'        => 'gold',
        ],
        'prayer' => [
            'title'       => 'Prayer Times in Bangladesh Today',
            'description' => 'Today’s prayer times for every district of Bangladesh — Fajr, Dhuhr, Asr, Maghrib and Isha — with a live countdown to the next namaz and sehri and iftar times.',
            'keywords'    => 'prayer time Bangladesh, namaz time, sehri iftar time, Dhaka prayer times',
            'path'        => 'prayer',
        ],
        'religion' => [
            'title'       => 'Religions of Bangladesh & Festivals',
            'description' => 'Islam, Hinduism, Buddhism, Christianity and indigenous beliefs in Bangladesh: population shares, the major religious sites of each, and the festival calendar.',
            'keywords'    => 'religion in Bangladesh, Bangladesh festivals, Durga Puja, Eid Bangladesh',
            'path'        => 'religion',
        ],
        'government' => [
            'title'       => 'Bangladesh Government Services & Hotlines',
            'description' => 'Bangladesh government online services in one place: NID, e-passport, birth registration, e-TIN, BRTA and land records, plus every national emergency number.',
            'keywords'    => 'Bangladesh government services, NID online, e-passport Bangladesh, 999 hotline',
            'path'        => 'government',
        ],
        'about' => [
            'title'       => 'About Bangladesh — Facts, Symbols & History',
            'description' => 'The national profile of Bangladesh: capital, currency, language, government, national symbols, a history timeline from Pundranagara to today, food and facts.',
            'keywords'    => 'about Bangladesh, Bangladesh facts, national symbols of Bangladesh, Bangladesh history',
            'path'        => 'about',
        ],
        'search' => [
            'title'       => 'Search Everything About Bangladesh',
            'description' => 'Search everything about Bangladesh from one box: districts, rivers, mountains, travel destinations, government services, gold rates and prayer times.',
            'path'        => 'search',
        ],
        '404' => [
            'title'       => 'Page Not Found',
            'description' => 'That page does not exist on AskBangladesh.',
            'noindex'     => true,
        ],
    ];

    return $meta[$page] ?? $meta['404'];
}

/* ------------------------------------------------------ structured data */

/** Organisation and site-level JSON-LD, emitted on every page. */
function bd_jsonld_site(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        '@id'      => bd_abs_url() . '#website',
        'name'     => APP_NAME,
        'url'      => bd_abs_url(),
        'description' => APP_TAGLINE,
        'inLanguage'  => 'en',
        'potentialAction' => [
            '@type'  => 'SearchAction',
            'target' => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => bd_abs_url('search') . '?q={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

/** BreadcrumbList JSON-LD from [['name' => ..., 'url' => ...], …]. */
function bd_jsonld_breadcrumbs(array $crumbs): array
{
    $items = [];
    foreach ($crumbs as $i => $crumb) {
        $item = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $crumb['name'],
        ];
        if (!empty($crumb['url'])) {
            $item['item'] = SITE_URL . $crumb['url'];
        }
        $items[] = $item;
    }

    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}

/** FAQPage JSON-LD from [question => answer]. */
function bd_jsonld_faq(array $pairs): array
{
    $items = [];
    foreach ($pairs as $question => $answer) {
        $items[] = [
            '@type'          => 'Question',
            'name'           => $question,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer],
        ];
    }

    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $items,
    ];
}

/* ------------------------------------------------------------ sitemap */

/**
 * Every indexable URL on the site, with sitemap metadata.
 * @return array<int, array{loc:string, changefreq:string, priority:string}>
 */
function bd_sitemap_urls(): array
{
    $urls = [];
    $add = static function (string $path, string $freq, string $priority) use (&$urls): void {
        $urls[] = ['loc' => bd_abs_url($path), 'changefreq' => $freq, 'priority' => $priority];
    };

    // Landing page and the tools whose data changes daily.
    $add('',        'daily',   '1.0');
    $add('map',     'monthly', '0.9');
    $add('gold',    'daily',   '0.9');
    $add('prayer',  'daily',   '0.9');
    $add('currency','daily',   '0.9');
    $add('time',    'daily',   '0.8');

    // Reference sections.
    $add('districts', 'monthly', '0.9');
    $add('travel',    'monthly', '0.9');
    $add('geography', 'monthly', '0.8');
    $add('mountains', 'monthly', '0.8');
    $add('rivers',    'monthly', '0.8');
    $add('transport', 'monthly', '0.8');
    $add('religion',  'monthly', '0.8');
    $add('government','monthly', '0.8');
    $add('about',     'monthly', '0.8');

    // Division pages.
    foreach (array_keys(bd_divisions()) as $division) {
        $add('division/' . bd_slug($division), 'monthly', '0.7');
    }

    // A page per district, and a prayer-time page per district.
    foreach (bd_districts() as $district) {
        $add('district/' . bd_slug($district['name']), 'monthly', '0.7');
        $add('prayer/' . bd_slug($district['name']),   'daily',   '0.6');
    }

    // A page per travel destination.
    foreach (bd_places('travel') as $place) {
        $add('travel/' . bd_slug($place['name']), 'monthly', '0.7');
    }

    return $urls;
}

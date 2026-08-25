<?php
/**
 * Shared helpers: data access, the global search index, the map projection
 * and the cached HTTP client used by the live-rate endpoints.
 */

declare(strict_types=1);

defined('APP_ROOT') || exit('Direct access is not permitted.');

/* ------------------------------------------------------------------ data */

function bd_data(string $set): array
{
    static $cache = [];
    if (!isset($cache[$set])) {
        $file = APP_ROOT . '/includes/data/' . $set . '.php';
        $cache[$set] = is_file($file) ? require $file : [];
    }
    return $cache[$set];
}

function bd_districts(): array { return bd_data('districts')['districts'] ?? []; }
function bd_divisions(): array { return bd_data('districts')['divisions'] ?? []; }
function bd_places(string $key): array { return bd_data('places')[$key] ?? []; }
function bd_nation(string $key): array { return bd_data('nation')[$key] ?? []; }

/* ---------------------------------------------------------------- output */

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function bd_num(float|int $n, int $decimals = 0): string
{
    return number_format((float) $n, $decimals);
}

/** Short human form for large counts: 12,043,977 -> "12.04 M". */
function bd_compact(float|int $n): string
{
    $n = (float) $n;
    if ($n >= 1_000_000_000) return round($n / 1_000_000_000, 2) . ' B';
    if ($n >= 1_000_000)     return round($n / 1_000_000, 2) . ' M';
    if ($n >= 1_000)         return round($n / 1_000, 1) . ' K';
    return (string) round($n);
}

/* ------------------------------------------------------- urls & routing */

/** URL-safe slug: "Cox's Bazar" -> "coxs-bazar", "ঢাকা" -> transliterated away. */
function bd_slug(string $text): string
{
    $text = str_replace(['’', "'", '`'], '', $text);
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text) ?? '';
    $text = trim($text, '-');
    return mb_strtolower($text) ?: 'item';
}

/**
 * Build a site-relative URL from a clean path.
 *   bd_url()                       -> /
 *   bd_url('districts')            -> /districts
 *   bd_url('district/dhaka')       -> /district/dhaka
 *   bd_url('search', ['q' => 'x']) -> /search?q=x
 */
function bd_url(string $path = '', array $query = []): string
{
    $path = trim($path, '/');
    $url  = BASE_PATH . '/' . $path;
    if ($query !== []) {
        $url .= '?' . http_build_query($query);
    }
    return $url;
}

/** Absolute URL, for canonical tags, Open Graph and the sitemap. */
function bd_abs_url(string $path = '', array $query = []): string
{
    return SITE_URL . bd_url($path, $query);
}

/* Canonical detail-page URLs for each entity type. */
function bd_district_url(array $d): string { return bd_url('district/' . bd_slug($d['name'])); }
function bd_division_url(string $name): string { return bd_url('division/' . bd_slug($name)); }
function bd_travel_url(array $t): string { return bd_url('travel/' . bd_slug($t['name'])); }
function bd_sun_url(array $d): string { return bd_url('sunrise-sunset/' . bd_slug($d['name'])); }

/** The request path with the base directory and surrounding slashes removed. */
function bd_request_path(): string
{
    $uri  = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    $uri  = is_string($uri) ? rawurldecode($uri) : '/';
    $base = BASE_PATH;

    if ($base !== '' && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }
    return trim($uri, '/');
}

/**
 * Resolve the request into a page name plus its parameters.
 * @return array{page: string, slug: string, path: string}
 */
function bd_route(): array
{
    static $route = null;
    if ($route !== null) {
        return $route;
    }

    $path     = bd_request_path();
    $segments = $path === '' ? [] : explode('/', $path);
    $first    = strtolower($segments[0] ?? '');
    $second   = isset($segments[1]) ? strtolower($segments[1]) : '';

    // Single-segment pages.
    $simple = [
        ''           => 'home',
        'map'        => 'map',
        'districts'  => 'districts',
        'divisions'  => 'districts',
        'geography'  => 'geography',
        'mountains'  => 'mountains',
        'rivers'     => 'rivers',
        'travel'     => 'travel',
        'transport'  => 'transport',
        'time'       => 'time',
        'currency'   => 'currency',
        'gold'       => 'gold',
        'sunrise-sunset' => 'sun',
        'religion'   => 'religion',
        'government' => 'government',
        'about'      => 'about',
        'search'     => 'search',
        'calendar'   => 'calendar',
        'thana'      => 'thana',
        'privacy'    => 'privacy',
        'terms'      => 'terms',
        'contact'    => 'contact',
        'sitemap.xml'=> 'sitemap',
        'robots.txt' => 'robots',
    ];

    // Two-segment detail pages: /district/dhaka, /travel/kuakata-sea-beach, …
    $detail = [
        'district'       => 'district',
        'division'       => 'division',
        'travel'         => 'travel-detail',
        'sunrise-sunset' => 'sun',
    ];

    if ($second !== '' && isset($detail[$first])) {
        $route = ['page' => $detail[$first], 'slug' => $second, 'path' => $path];
    } elseif (isset($simple[$first]) && count($segments) <= 1) {
        $route = ['page' => $simple[$first], 'slug' => '', 'path' => $path];
    } else {
        $route = ['page' => '404', 'slug' => '', 'path' => $path];
    }

    return $route;
}

function bd_current_page(): string
{
    return bd_route()['page'];
}

/** Mark a nav item active, including on that section's detail pages. */
function bd_active(string $page): string
{
    $current = bd_current_page();
    $family  = [
        'districts' => ['districts', 'district', 'division'],
        'travel'    => ['travel', 'travel-detail'],
        'geography' => ['geography', 'mountains', 'rivers'],
        'sun'       => ['sun'],
    ];
    $group = $family[$page] ?? [$page];
    return in_array($current, $group, true) ? ' is-active' : '';
}

/* --------------------------------------------------------- slug lookups */

function bd_find_district(string $slug): ?array
{
    foreach (bd_districts() as $district) {
        if (bd_slug($district['name']) === $slug) {
            return $district;
        }
    }
    return null;
}

function bd_find_division(string $slug): ?array
{
    foreach (bd_divisions() as $name => $division) {
        if (bd_slug($name) === $slug) {
            return ['name' => $name] + $division;
        }
    }
    return null;
}

function bd_find_travel(string $slug): ?array
{
    foreach (bd_places('travel') as $place) {
        if (bd_slug($place['name']) === $slug) {
            return $place;
        }
    }
    return null;
}

/** Districts belonging to a division, in data order. */
function bd_division_districts(string $division): array
{
    return array_values(array_filter(
        bd_districts(),
        static fn (array $d): bool => $d['division'] === $division
    ));
}

/* ------------------------------------------------------- map projection */

/**
 * Bangladesh outline as an ordered list of [lon, lat] boundary points.
 * Simplified for a stylised vector map, traced roughly clockwise from
 * the north-west corner of Chapainawabganj.
 */
function bd_outline(): array
{
    return [
        // West border with West Bengal, running north.
        [88.03, 24.66], [88.12, 24.90], [88.02, 25.20], [88.30, 25.22], [88.45, 25.20],
        [88.13, 25.60], [88.25, 25.85], [88.35, 26.10], [88.42, 26.36],
        // Northern tip at Banglabandha, then the Rangpur frontier.
        [88.55, 26.55], [88.75, 26.50], [88.95, 26.30], [88.78, 26.10], [88.90, 25.95],
        [89.20, 26.00], [89.40, 26.15], [89.65, 26.20], [89.85, 26.00], [89.82, 25.70],
        [89.75, 25.35], [89.85, 25.30],
        // The Meghalaya border along the north of Mymensingh and Sylhet.
        [90.10, 25.20], [90.40, 25.15], [90.70, 25.18], [91.00, 25.20], [91.25, 25.18],
        [91.50, 25.15], [91.90, 25.18], [92.15, 25.10], [92.35, 24.95], [92.25, 24.70],
        [92.10, 24.55], [92.35, 24.38], [92.15, 24.20], [91.95, 24.15], [91.70, 24.20],
        [91.55, 24.10], [91.40, 24.05],
        // Around the Tripura salient, which pushes west into the country.
        [91.30, 23.85], [91.15, 23.65], [91.05, 23.40], [91.12, 23.20], [91.35, 23.05],
        [91.55, 22.98], [91.80, 23.10], [92.00, 23.25], [92.15, 23.45], [92.25, 23.68],
        // South along the Mizoram and Myanmar border, through the Hill Tracts.
        [92.38, 23.40], [92.45, 23.00], [92.60, 22.50], [92.65, 22.10], [92.60, 21.70],
        [92.58, 21.40], [92.40, 21.25], [92.30, 21.05], [92.15, 21.05], [92.28, 20.85],
        // Teknaf peninsula, then back up the coast.
        [92.20, 20.75], [92.05, 21.15], [91.95, 21.60], [91.85, 21.95], [91.70, 22.20],
        [91.50, 22.35], [91.25, 22.40], [91.00, 22.35], [90.75, 22.15], [90.55, 22.05],
        [90.40, 21.90], [90.15, 21.82], [90.00, 22.00], [89.85, 21.90], [89.60, 21.72],
        // The Sundarbans coast and the south-western border.
        [89.30, 21.78], [89.05, 21.90], [88.92, 22.15], [88.85, 22.45], [88.72, 22.65],
        [88.90, 23.05], [89.00, 23.20], [88.75, 23.28], [88.72, 23.55], [88.85, 23.75],
        [88.62, 23.70], [88.55, 23.95], [88.45, 24.10], [88.15, 24.30], [88.05, 24.45],
    ];
}

/** Bounding box the projection maps onto the SVG viewBox. */
const BD_BOUNDS = ['minLon' => 87.9, 'maxLon' => 92.8, 'minLat' => 20.5, 'maxLat' => 26.8];
const BD_VIEW   = ['w' => 620, 'h' => 780];

/** Project geographic coordinates into the SVG coordinate space. */
function bd_project(float $lon, float $lat): array
{
    $b = BD_BOUNDS;
    $x = ($lon - $b['minLon']) / ($b['maxLon'] - $b['minLon']) * BD_VIEW['w'];
    $y = ($b['maxLat'] - $lat) / ($b['maxLat'] - $b['minLat']) * BD_VIEW['h'];
    return [round($x, 2), round($y, 2)];
}

function bd_outline_path(): string
{
    $points = [];
    foreach (bd_outline() as [$lon, $lat]) {
        [$x, $y] = bd_project($lon, $lat);
        $points[] = "$x,$y";
    }
    return 'M' . implode(' L', $points) . ' Z';
}

/* --------------------------------------------------------- search index */

/**
 * Build one flat, searchable index across every dataset in the app.
 * Each row: title, subtitle, body, category, icon, url.
 */
function bd_search_index(): array
{
    static $index = null;
    if ($index !== null) {
        return $index;
    }

    $index = [];
    $add = static function (array $row) use (&$index): void {
        $row += ['subtitle' => '', 'body' => '', 'icon' => '📌', 'url' => bd_url()];
        $row['haystack'] = mb_strtolower(
            $row['title'] . ' ' . $row['subtitle'] . ' ' . $row['body'] . ' ' . $row['category']
        );
        $index[] = $row;
    };

    foreach (bd_districts() as $d) {
        $add([
            'title'    => $d['name'] . ' District',
            'subtitle' => $d['bn'] . ' · ' . $d['division'] . ' Division',
            'body'     => $d['famous'] . ' Area ' . $d['area'] . ' km². Population ' . bd_num($d['population']) . '.',
            'category' => 'District',
            'icon'     => '📍',
            'url'      => bd_district_url($d),
        ]);
    }

    foreach (bd_divisions() as $name => $div) {
        $add([
            'title'    => $name . ' Division',
            'subtitle' => 'Established ' . $div['established'],
            'body'     => 'Area ' . bd_num($div['area']) . ' km², population about ' . bd_compact($div['population']) . '.',
            'category' => 'Division',
            'icon'     => '🗺️',
            'url'      => bd_division_url($name),
        ]);
    }

    foreach (bd_places('travel') as $t) {
        $add([
            'title'    => $t['name'],
            'subtitle' => $t['type'] . ' · ' . $t['district'] . ' · best ' . $t['best'],
            'body'     => $t['desc'],
            'category' => 'Travel',
            'icon'     => $t['emoji'],
            'url'      => bd_travel_url($t),
        ]);
    }

    foreach (bd_places('mountains') as $m) {
        $add([
            'title'    => $m['name'],
            'subtitle' => $m['height'] . ' m · ' . $m['district'] . ' · ' . $m['range'],
            'body'     => $m['note'] . ' ' . $m['alt'],
            'category' => 'Mountain',
            'icon'     => '⛰️',
            'url'      => bd_url('mountains'),
        ]);
    }

    foreach (bd_places('rivers') as $r) {
        $add([
            'title'    => $r['name'] . ' River',
            'subtitle' => $r['bn'] . ' · ' . $r['length'] . ' km in Bangladesh',
            'body'     => $r['note'],
            'category' => 'River',
            'icon'     => '🌊',
            'url'      => bd_url('rivers'),
        ]);
    }

    foreach (bd_places('roads') as $r) {
        $add([
            'title'    => $r['code'] . ' — ' . $r['name'],
            'subtitle' => $r['length'] . ' km national highway',
            'body'     => $r['note'],
            'category' => 'Road',
            'icon'     => '🛣️',
            'url'      => bd_url('transport'),
        ]);
    }

    foreach (bd_places('megaprojects') as $m) {
        $add([
            'title'    => $m['name'],
            'subtitle' => 'Opened ' . $m['opened'] . ' · ' . $m['stat'],
            'body'     => $m['note'],
            'category' => 'Infrastructure',
            'icon'     => '🏗️',
            'url'      => bd_url('transport'),
        ]);
    }

    foreach (bd_places('transport') as $t) {
        $add([
            'title'    => $t['mode'],
            'subtitle' => $t['bn'] . ' · typical fare ' . $t['fare'],
            'body'     => $t['tip'] . ' Available: ' . $t['where'],
            'category' => 'Transport',
            'icon'     => $t['emoji'],
            'url'      => bd_url('transport'),
        ]);
    }

    foreach (bd_places('airports') as $a) {
        $add([
            'title'    => $a['name'],
            'subtitle' => $a['code'] . ' · ' . $a['city'] . ' · ' . $a['type'],
            'body'     => 'Airport serving ' . $a['city'] . '.',
            'category' => 'Airport',
            'icon'     => '✈️',
            'url'      => bd_url('transport'),
        ]);
    }

    foreach (bd_places('ports') as $p) {
        $add([
            'title'    => $p['name'],
            'subtitle' => $p['type'],
            'body'     => $p['note'],
            'category' => 'Port',
            'icon'     => '⚓',
            'url'      => bd_url('transport'),
        ]);
    }

    foreach (bd_nation('religions') as $r) {
        $add([
            'title'    => $r['name'],
            'subtitle' => $r['bn'] . ' · ' . $r['share'] . '% of the population',
            'body'     => $r['note'] . ' ' . implode(', ', $r['sites']),
            'category' => 'Religion',
            'icon'     => $r['emoji'],
            'url'      => bd_url('religion'),
        ]);
    }

    foreach (bd_nation('festivals') as $f) {
        $add([
            'title'    => $f['name'],
            'subtitle' => $f['bn'] . ' · ' . $f['when'],
            'body'     => $f['desc'],
            'category' => 'Festival',
            'icon'     => $f['emoji'],
            'url'      => bd_url('religion'),
        ]);
    }

    foreach (bd_nation('government') as $g) {
        $add([
            'title'    => $g['office'],
            'subtitle' => $g['seat'],
            'body'     => $g['role'] . ' ' . $g['web'],
            'category' => 'Government',
            'icon'     => '🏛️',
            'url'      => bd_url('government'),
        ]);
    }

    foreach (bd_nation('eservices') as $s) {
        $add([
            'title'    => $s['name'],
            'subtitle' => 'Government online service',
            'body'     => $s['desc'] . ' ' . $s['url'],
            'category' => 'e-Service',
            'icon'     => $s['emoji'],
            'url'      => bd_url('government'),
        ]);
    }

    foreach (bd_nation('emergency') as $s) {
        $add([
            'title'    => $s['service'] . ' — dial ' . $s['number'],
            'subtitle' => 'Emergency hotline',
            'body'     => 'Call ' . $s['number'] . ' for ' . $s['service'] . '.',
            'category' => 'Emergency',
            'icon'     => $s['emoji'],
            'url'      => bd_url('government'),
        ]);
    }

    foreach (bd_nation('symbols') as $s) {
        $add([
            'title'    => $s['item'],
            'subtitle' => 'National symbol',
            'body'     => $s['value'],
            'category' => 'Symbol',
            'icon'     => $s['emoji'],
            'url'      => bd_url('about'),
        ]);
    }

    foreach (bd_nation('food') as $f) {
        $add([
            'title'    => $f['name'],
            'subtitle' => 'Bangladeshi cuisine',
            'body'     => $f['desc'],
            'category' => 'Food',
            'icon'     => $f['emoji'],
            'url'      => bd_url('about'),
        ]);
    }

    foreach (bd_nation('timeline') as $t) {
        $add([
            'title'    => $t['year'],
            'subtitle' => 'History',
            'body'     => $t['event'],
            'category' => 'History',
            'icon'     => '📜',
            'url'      => bd_url('about'),
        ]);
    }

    foreach (bd_nation('facts') as $fact) {
        $add([
            'title'    => mb_substr($fact, 0, 60) . (mb_strlen($fact) > 60 ? '…' : ''),
            'subtitle' => 'Did you know?',
            'body'     => $fact,
            'category' => 'Fact',
            'icon'     => '💡',
            'url'      => bd_url('about'),
        ]);
    }

    // Tools are searchable too, so "convert currency" finds the converter.
    foreach (bd_tools() as $tool) {
        $add([
            'title'    => $tool['name'],
            'subtitle' => 'Tool',
            'body'     => $tool['desc'] . ' ' . implode(' ', $tool['keywords']),
            'category' => 'Tool',
            'icon'     => $tool['emoji'],
            'url'      => $tool['url'],
        ]);
    }

    return $index;
}

function bd_tools(): array
{
    return [
        ['name' => 'Interactive Bangladesh Map', 'emoji' => '🗺️', 'url' => bd_url('map'), 'desc' => 'Explore all 64 districts, peaks, beaches, airports and ports on a live vector map.', 'keywords' => ['map', 'district map', 'location', 'where']],
        ['name' => 'Live Clock & World Time Converter', 'emoji' => '🕰️', 'url' => bd_url('time'), 'desc' => 'Bangladesh Standard Time with a converter for 24 world cities, plus countdowns.', 'keywords' => ['time', 'clock', 'timezone', 'BST', 'converter', 'timer', 'countdown']],
        ['name' => 'Currency Converter', 'emoji' => '💱', 'url' => bd_url('currency'), 'desc' => 'Convert the Bangladeshi Taka against 30+ world currencies with live rates.', 'keywords' => ['currency', 'taka', 'BDT', 'dollar', 'exchange rate', 'remittance', 'convert money']],
        ['name' => 'Gold Price in Bangladesh', 'emoji' => '🥇', 'url' => bd_url('gold'), 'desc' => 'BAJUS gold and silver rates per bhori, gram and ana, with a weight converter.', 'keywords' => ['gold', 'gold price', 'bhori', 'vori', 'silver', 'jewellery', 'BAJUS', 'ana']],
        ['name' => 'Sunrise & Sunset Times', 'emoji' => '🌅', 'url' => bd_url('sunrise-sunset'), 'desc' => 'Sunrise, sunset, day length, solar noon and golden hour for every district.', 'keywords' => ['sunrise', 'sunset', 'day length', 'daylight', 'golden hour', 'solar noon', 'dawn', 'dusk', 'twilight']],
        ['name' => 'Global Search', 'emoji' => '🔍', 'url' => bd_url('search'), 'desc' => 'One search box across every district, place, service and fact in the app.', 'keywords' => ['search', 'find', 'lookup']],
        ['name' => 'Government Directory', 'emoji' => '🏛️', 'url' => bd_url('government'), 'desc' => 'Ministries, e-services, emergency hotlines and national institutions.', 'keywords' => ['government', 'NID', 'passport', 'tax', 'e-service', 'hotline', '999']],
        ['name' => 'Travel Guide', 'emoji' => '🧳', 'url' => bd_url('travel'), 'desc' => 'The best places to visit across the country with seasons and districts.', 'keywords' => ['travel', 'tourism', 'places', 'visit', 'beach', 'hills']],
        ['name' => 'Transport & Roads', 'emoji' => '🛣️', 'url' => bd_url('transport'), 'desc' => 'Highways, fares, airports, ports and the big infrastructure projects.', 'keywords' => ['transport', 'road', 'highway', 'bus', 'train', 'launch', 'metro', 'fare']],
        ['name' => 'Geography & Mountains', 'emoji' => '⛰️', 'url' => bd_url('geography'), 'desc' => 'Continent, delta, rivers, peaks, climate and the extreme points of the country.', 'keywords' => ['geography', 'continent', 'asia', 'mountain', 'peak', 'river', 'climate', 'delta']],
        ['name' => 'Religions & Festivals', 'emoji' => '🕊️', 'url' => bd_url('religion'), 'desc' => 'Faiths of Bangladesh, their major sites and the festival calendar.', 'keywords' => ['religion', 'islam', 'hindu', 'buddhist', 'christian', 'festival', 'puja', 'eid']],
        ['name' => 'About Bangladesh', 'emoji' => '🇧🇩', 'url' => bd_url('about'), 'desc' => 'National profile, symbols, history timeline, food and surprising facts.', 'keywords' => ['about', 'facts', 'history', 'symbol', 'flag', 'anthem', 'food']],
    ];
}

/**
 * Rank the index against a query. Scoring favours exact title matches,
 * then title prefixes, then whole-word hits, then loose substrings.
 */
function bd_search(string $query, int $limit = 40): array
{
    $query = trim($query);
    if ($query === '') {
        return [];
    }

    $q     = mb_strtolower($query);
    $terms = array_values(array_filter(preg_split('/\s+/', $q) ?: []));
    $hits  = [];

    foreach (bd_search_index() as $row) {
        $title = mb_strtolower($row['title']);
        $score = 0;

        if ($title === $q) {
            $score += 200;
        } elseif (str_starts_with($title, $q)) {
            $score += 120;
        } elseif (str_contains($title, $q)) {
            $score += 80;
        }

        if (str_contains($row['haystack'], $q)) {
            $score += 30;
        }

        foreach ($terms as $term) {
            if (mb_strlen($term) < 2) {
                continue;
            }
            if (str_contains($title, $term)) {
                $score += 20;
            }
            if (preg_match('/\b' . preg_quote($term, '/') . '/u', $row['haystack'])) {
                $score += 10;
            } elseif (str_contains($row['haystack'], $term)) {
                $score += 4;
            }
        }

        if ($score > 0) {
            $row['score'] = $score;
            unset($row['haystack']);
            $hits[] = $row;
        }
    }

    usort($hits, static fn (array $a, array $b): int => $b['score'] <=> $a['score'] ?: strcmp($a['title'], $b['title']));

    return array_slice($hits, 0, $limit);
}

/* ------------------------------------------------------ cached http get */

function bd_cache_get(string $key, int $ttl): ?array
{
    $file = CACHE_DIR . '/' . preg_replace('/[^a-z0-9_.-]/i', '_', $key) . '.json';
    if (!is_file($file) || (time() - filemtime($file)) > $ttl) {
        return null;
    }
    $raw = @file_get_contents($file);
    if ($raw === false) {
        return null;
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

function bd_cache_put(string $key, array $data): void
{
    $file = CACHE_DIR . '/' . preg_replace('/[^a-z0-9_.-]/i', '_', $key) . '.json';
    @file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
}

/**
 * GET a JSON endpoint, returning null instead of throwing when the network is
 * unavailable, blocked or slow. Prefers cURL (which honours the standard
 * proxy environment variables) and falls back to the streams wrapper.
 */
function bd_http_json(string $url): ?array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_TIMEOUT        => HTTP_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => HTTP_TIMEOUT,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT      => 'AskBangladesh/' . APP_VERSION,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $raw    = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if (is_string($raw) && $raw !== '' && $status >= 200 && $status < 300) {
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }
        return null;
    }

    $context = stream_context_create([
        'http' => [
            'method'        => 'GET',
            'timeout'       => HTTP_TIMEOUT,
            'ignore_errors' => true,
            'header'        => "Accept: application/json\r\nUser-Agent: AskBangladesh/" . APP_VERSION . "\r\n",
        ],
        'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);

    $raw = @file_get_contents($url, false, $context);
    if ($raw === false || $raw === '') {
        return null;
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

function bd_json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: public, max-age=300');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

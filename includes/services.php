<?php
/**
 * Live data services: currency rates, gold and silver prices, and prayer
 * times. Every service tries a live source first, caches the result, and
 * falls back to bundled values so the app still works without a network.
 */

declare(strict_types=1);

defined('APP_ROOT') || exit('Direct access is not permitted.');

/* ------------------------------------------------------------- currency */

/** Currencies offered in the converter, in display order. */
function bd_currencies(): array
{
    return [
        'BDT' => ['name' => 'Bangladeshi Taka',      'symbol' => '৳',   'flag' => '🇧🇩'],
        'USD' => ['name' => 'US Dollar',             'symbol' => '$',   'flag' => '🇺🇸'],
        'EUR' => ['name' => 'Euro',                  'symbol' => '€',   'flag' => '🇪🇺'],
        'GBP' => ['name' => 'British Pound',         'symbol' => '£',   'flag' => '🇬🇧'],
        'INR' => ['name' => 'Indian Rupee',          'symbol' => '₹',   'flag' => '🇮🇳'],
        'SAR' => ['name' => 'Saudi Riyal',           'symbol' => '﷼',   'flag' => '🇸🇦'],
        'AED' => ['name' => 'UAE Dirham',            'symbol' => 'د.إ', 'flag' => '🇦🇪'],
        'MYR' => ['name' => 'Malaysian Ringgit',     'symbol' => 'RM',  'flag' => '🇲🇾'],
        'KWD' => ['name' => 'Kuwaiti Dinar',         'symbol' => 'د.ك', 'flag' => '🇰🇼'],
        'QAR' => ['name' => 'Qatari Riyal',          'symbol' => 'ر.ق', 'flag' => '🇶🇦'],
        'OMR' => ['name' => 'Omani Rial',            'symbol' => 'ر.ع', 'flag' => '🇴🇲'],
        'BHD' => ['name' => 'Bahraini Dinar',        'symbol' => '.د.ب','flag' => '🇧🇭'],
        'SGD' => ['name' => 'Singapore Dollar',      'symbol' => 'S$',  'flag' => '🇸🇬'],
        'AUD' => ['name' => 'Australian Dollar',     'symbol' => 'A$',  'flag' => '🇦🇺'],
        'CAD' => ['name' => 'Canadian Dollar',       'symbol' => 'C$',  'flag' => '🇨🇦'],
        'JPY' => ['name' => 'Japanese Yen',          'symbol' => '¥',   'flag' => '🇯🇵'],
        'CNY' => ['name' => 'Chinese Yuan',          'symbol' => '¥',   'flag' => '🇨🇳'],
        'KRW' => ['name' => 'South Korean Won',      'symbol' => '₩',   'flag' => '🇰🇷'],
        'CHF' => ['name' => 'Swiss Franc',           'symbol' => 'CHF', 'flag' => '🇨🇭'],
        'ITL' => ['name' => 'Italy (Euro)',          'symbol' => '€',   'flag' => '🇮🇹'],
        'TRY' => ['name' => 'Turkish Lira',          'symbol' => '₺',   'flag' => '🇹🇷'],
        'PKR' => ['name' => 'Pakistani Rupee',       'symbol' => '₨',   'flag' => '🇵🇰'],
        'LKR' => ['name' => 'Sri Lankan Rupee',      'symbol' => 'Rs',  'flag' => '🇱🇰'],
        'NPR' => ['name' => 'Nepalese Rupee',        'symbol' => '₨',   'flag' => '🇳🇵'],
        'THB' => ['name' => 'Thai Baht',             'symbol' => '฿',   'flag' => '🇹🇭'],
        'IDR' => ['name' => 'Indonesian Rupiah',     'symbol' => 'Rp',  'flag' => '🇮🇩'],
        'ZAR' => ['name' => 'South African Rand',    'symbol' => 'R',   'flag' => '🇿🇦'],
        'RUB' => ['name' => 'Russian Ruble',         'symbol' => '₽',   'flag' => '🇷🇺'],
        'BRL' => ['name' => 'Brazilian Real',        'symbol' => 'R$',  'flag' => '🇧🇷'],
        'SEK' => ['name' => 'Swedish Krona',         'symbol' => 'kr',  'flag' => '🇸🇪'],
        'NOK' => ['name' => 'Norwegian Krone',       'symbol' => 'kr',  'flag' => '🇳🇴'],
        'NZD' => ['name' => 'New Zealand Dollar',    'symbol' => 'NZ$', 'flag' => '🇳🇿'],
    ];
}

/** Bundled indicative rates, used only when no live feed is reachable. */
function bd_fallback_rates(): array
{
    // Units of the given currency per 1 BDT.
    return [
        'BDT' => 1.0,       'USD' => 0.00820,  'EUR' => 0.00760,  'GBP' => 0.00648,
        'INR' => 0.7150,    'SAR' => 0.03076,  'AED' => 0.03012,  'MYR' => 0.03610,
        'KWD' => 0.00251,   'QAR' => 0.02986,  'OMR' => 0.00316,  'BHD' => 0.00309,
        'SGD' => 0.01096,   'AUD' => 0.01252,  'CAD' => 0.01128,  'JPY' => 1.2450,
        'CNY' => 0.05880,   'KRW' => 11.180,   'CHF' => 0.00710,  'ITL' => 0.00760,
        'TRY' => 0.3220,    'PKR' => 2.3050,   'LKR' => 2.4600,   'NPR' => 1.1440,
        'THB' => 0.2760,    'IDR' => 132.50,   'ZAR' => 0.1480,   'RUB' => 0.7420,
        'BRL' => 0.04480,   'SEK' => 0.08120,  'NOK' => 0.08450,  'NZD' => 0.01372,
    ];
}

/**
 * Exchange rates expressed as "units of currency X per 1 BDT".
 * @return array{rates: array<string,float>, source: string, updated: string, live: bool}
 */
function bd_exchange_rates(): array
{
    $cached = bd_cache_get('rates_bdt', CACHE_TTL_RATES);
    if ($cached !== null && !empty($cached['rates'])) {
        return $cached;
    }

    $wanted = array_keys(bd_currencies());
    $result = null;

    $live = bd_http_json('https://open.er-api.com/v6/latest/BDT');
    if ($live !== null && !empty($live['rates']) && is_array($live['rates'])) {
        $rates = ['BDT' => 1.0];
        foreach ($wanted as $code) {
            $lookup = $code === 'ITL' ? 'EUR' : $code;
            if (isset($live['rates'][$lookup]) && is_numeric($live['rates'][$lookup])) {
                $rates[$code] = (float) $live['rates'][$lookup];
            }
        }
        if (count($rates) > 5) {
            $result = [
                'rates'   => $rates + bd_fallback_rates(),
                'source'  => 'open.er-api.com',
                'updated' => date('c', (int) ($live['time_last_update_unix'] ?? time())),
                'live'    => true,
            ];
        }
    }

    if ($result === null) {
        $result = [
            'rates'   => bd_fallback_rates(),
            'source'  => 'bundled indicative rates',
            'updated' => date('c'),
            'live'    => false,
        ];
    }

    bd_cache_put('rates_bdt', $result);
    return $result;
}

function bd_convert(float $amount, string $from, string $to, array $rates): ?float
{
    if (!isset($rates[$from], $rates[$to]) || $rates[$from] <= 0) {
        return null;
    }
    // Rates are per 1 BDT, so route the conversion through the Taka.
    return $amount / $rates[$from] * $rates[$to];
}

/* ----------------------------------------------------------------- gold */

/** Traditional Bengali weight units, expressed in grams. */
function bd_weight_units(): array
{
    return [
        'bhori' => ['label' => 'Bhori (ভরি)',   'grams' => 11.6638038],
        'ana'   => ['label' => 'Ana (আনা)',     'grams' => 0.7289877],  // 1 bhori = 16 ana
        'roti'  => ['label' => 'Roti (রতি)',    'grams' => 0.1214980],  // 1 ana = 6 roti
        'gram'  => ['label' => 'Gram (গ্রাম)',  'grams' => 1.0],
        'tola'  => ['label' => 'Tola (তোলা)',   'grams' => 11.6638038],
        'ounce' => ['label' => 'Troy ounce',    'grams' => 31.1034768],
    ];
}

/**
 * Gold and silver prices per bhori in BDT, following the BAJUS
 * (Bangladesh Jewellers Association) category structure.
 */
function bd_gold_prices(): array
{
    $cached = bd_cache_get('gold_bd', CACHE_TTL_GOLD);
    if ($cached !== null && !empty($cached['gold'])) {
        return $cached;
    }

    // Indicative BAJUS-style board, per bhori (11.664 g).
    $gold = [
        ['karat' => '22K', 'label' => '22 Karat (Hallmark)', 'purity' => 91.6, 'bhori' => 173000],
        ['karat' => '21K', 'label' => '21 Karat (Hallmark)', 'purity' => 87.5, 'bhori' => 165100],
        ['karat' => '18K', 'label' => '18 Karat (Hallmark)', 'purity' => 75.0, 'bhori' => 141500],
        ['karat' => 'SD',  'label' => 'Traditional (Sanatan)','purity' => 58.0, 'bhori' => 116800],
    ];
    $silver = [
        ['karat' => '22K', 'label' => '22 Karat Silver', 'purity' => 91.6, 'bhori' => 2900],
        ['karat' => '21K', 'label' => '21 Karat Silver', 'purity' => 87.5, 'bhori' => 2755],
        ['karat' => '18K', 'label' => '18 Karat Silver', 'purity' => 75.0, 'bhori' => 2350],
        ['karat' => 'SD',  'label' => 'Traditional Silver','purity' => 58.0, 'bhori' => 1750],
    ];
    $live = false;
    $source = 'indicative BAJUS-style board';

    // If an international spot price is reachable, re-base the board on it.
    $spot = bd_http_json('https://api.gold-api.com/price/XAU');
    $rates = bd_exchange_rates()['rates'];
    if ($spot !== null && isset($spot['price']) && is_numeric($spot['price']) && !empty($rates['USD'])) {
        $usdPerGram = (float) $spot['price'] / 31.1034768;
        $bdtPerGram = $usdPerGram / (float) $rates['USD'];
        // Retail bhori price adds the local making/market premium over spot.
        $premium = 1.09;
        foreach ($gold as $i => $row) {
            $gold[$i]['bhori'] = (int) round($bdtPerGram * ($row['purity'] / 100) * 11.6638038 * $premium, -1);
        }
        $silverSpot = bd_http_json('https://api.gold-api.com/price/XAG');
        if ($silverSpot !== null && isset($silverSpot['price']) && is_numeric($silverSpot['price'])) {
            $sBdtPerGram = ((float) $silverSpot['price'] / 31.1034768) / (float) $rates['USD'];
            foreach ($silver as $i => $row) {
                $silver[$i]['bhori'] = (int) round($sBdtPerGram * ($row['purity'] / 100) * 11.6638038 * 1.15, -1);
            }
        }
        $live = true;
        $source = 'gold-api.com spot price, converted to BDT';
    }

    $expand = static function (array $rows): array {
        foreach ($rows as $i => $row) {
            $perGram = $row['bhori'] / 11.6638038;
            $rows[$i]['gram']  = round($perGram, 2);
            $rows[$i]['ana']   = round($row['bhori'] / 16, 2);
            $rows[$i]['ounce'] = round($perGram * 31.1034768, 2);
        }
        return $rows;
    };

    $result = [
        'gold'    => $expand($gold),
        'silver'  => $expand($silver),
        'live'    => $live,
        'source'  => $source,
        'updated' => date('c'),
    ];

    bd_cache_put('gold_bd', $result);
    return $result;
}

/* --------------------------------------------------------- prayer times */

/**
 * Calculate the five daily prayer times plus sunrise, using the standard
 * solar position algorithm. Angles follow the University of Islamic
 * Sciences, Karachi convention (Fajr 18°, Isha 18°) commonly used in
 * Bangladesh, with the Hanafi Asr shadow ratio.
 *
 * @return array<string,string> prayer name => HH:MM in Bangladesh time
 */
function bd_prayer_times(float $lat, float $lon, ?int $timestamp = null, string $asrSchool = 'hanafi'): array
{
    $timestamp ??= time();
    $tzOffset   = 6.0; // Bangladesh Standard Time, UTC+06:00, no DST.

    $y = (int) date('Y', $timestamp);
    $m = (int) date('n', $timestamp);
    $d = (int) date('j', $timestamp);

    // Julian day at 00:00 UT.
    if ($m <= 2) { $y -= 1; $m += 12; }
    $a  = intdiv($y, 100);
    $b  = 2 - $a + intdiv($a, 4);
    $jd = floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $d + $b - 1524.5;

    $n  = $jd - 2451545.0;
    $g  = fmod(357.529 + 0.98560028 * $n, 360.0);          // mean anomaly
    $q  = fmod(280.459 + 0.98564736 * $n, 360.0);          // mean longitude
    $L  = fmod($q + 1.915 * sin(deg2rad($g)) + 0.020 * sin(deg2rad(2 * $g)), 360.0);
    $eps = 23.439 - 0.00000036 * $n;                        // obliquity

    $ra = rad2deg(atan2(cos(deg2rad($eps)) * sin(deg2rad($L)), cos(deg2rad($L))));
    $ra = fmod($ra + 360.0, 360.0) / 15.0;
    $decl = rad2deg(asin(sin(deg2rad($eps)) * sin(deg2rad($L))));
    $eqt  = $q / 15.0 - $ra;                                // equation of time, hours
    // Wrap into (-12, +12]; the raw difference can straddle the 0/24h seam.
    $eqt  = fmod(fmod($eqt, 24.0) + 24.0, 24.0);
    if ($eqt > 12.0) { $eqt -= 24.0; }

    $dhuhr = 12.0 + $tzOffset - $lon / 15.0 - $eqt;

    // Hour angle for a given sun altitude below the horizon.
    $hourAngle = static function (float $angle) use ($lat, $decl): ?float {
        $cosH = (-sin(deg2rad($angle)) - sin(deg2rad($decl)) * sin(deg2rad($lat)))
              / (cos(deg2rad($decl)) * cos(deg2rad($lat)));
        if ($cosH < -1.0 || $cosH > 1.0) {
            return null; // Sun never reaches this altitude at this latitude/date.
        }
        return rad2deg(acos($cosH)) / 15.0;
    };

    $shadow = $asrSchool === 'hanafi' ? 2.0 : 1.0;
    $asrAngle = -rad2deg(atan(1.0 / ($shadow + tan(deg2rad(abs($lat - $decl))))));
    $asrDelta = $hourAngle($asrAngle);

    $sunAngle  = 0.833; // refraction + solar disc
    $riseDelta = $hourAngle($sunAngle);
    $fajrDelta = $hourAngle(18.0);
    $ishaDelta = $hourAngle(18.0);

    $fmt = static function (?float $hours): string {
        if ($hours === null) { return '—'; }
        $hours = fmod($hours + 24.0, 24.0);
        $h = (int) floor($hours);
        $mi = (int) round(($hours - $h) * 60);
        if ($mi === 60) { $mi = 0; $h = ($h + 1) % 24; }
        return sprintf('%02d:%02d', $h, $mi);
    };

    return [
        'Fajr'    => $fmt($fajrDelta === null ? null : $dhuhr - $fajrDelta),
        'Sunrise' => $fmt($riseDelta === null ? null : $dhuhr - $riseDelta),
        'Dhuhr'   => $fmt($dhuhr + 0.0334),                       // small safety offset
        'Asr'     => $fmt($asrDelta === null ? null : $dhuhr + $asrDelta),
        'Maghrib' => $fmt($riseDelta === null ? null : $dhuhr + $riseDelta + 0.0334),
        'Isha'    => $fmt($ishaDelta === null ? null : $dhuhr + $ishaDelta),
    ];
}

/** Icon and short description for each prayer, for the UI. */
function bd_prayer_meta(): array
{
    return [
        'Fajr'    => ['bn' => 'ফজর',    'emoji' => '🌄', 'note' => 'Dawn, before sunrise'],
        'Sunrise' => ['bn' => 'সূর্যোদয়','emoji' => '🌅', 'note' => 'End of Fajr time'],
        'Dhuhr'   => ['bn' => 'যোহর',   'emoji' => '☀️', 'note' => 'After the sun passes its zenith'],
        'Asr'     => ['bn' => 'আসর',    'emoji' => '🌤️', 'note' => 'Afternoon'],
        'Maghrib' => ['bn' => 'মাগরিব',  'emoji' => '🌇', 'note' => 'Just after sunset — iftar time'],
        'Isha'    => ['bn' => 'এশা',    'emoji' => '🌙', 'note' => 'Night'],
    ];
}

/* -------------------------------------------------------------- clocks */

/** World cities offered by the time converter, with UTC offsets in hours. */
function bd_world_clocks(): array
{
    return [
        ['city' => 'Dhaka',        'zone' => 'Asia/Dhaka',        'flag' => '🇧🇩'],
        ['city' => 'Kolkata',      'zone' => 'Asia/Kolkata',      'flag' => '🇮🇳'],
        ['city' => 'Kathmandu',    'zone' => 'Asia/Kathmandu',    'flag' => '🇳🇵'],
        ['city' => 'Karachi',      'zone' => 'Asia/Karachi',      'flag' => '🇵🇰'],
        ['city' => 'Colombo',      'zone' => 'Asia/Colombo',      'flag' => '🇱🇰'],
        ['city' => 'Yangon',       'zone' => 'Asia/Yangon',       'flag' => '🇲🇲'],
        ['city' => 'Bangkok',      'zone' => 'Asia/Bangkok',      'flag' => '🇹🇭'],
        ['city' => 'Singapore',    'zone' => 'Asia/Singapore',    'flag' => '🇸🇬'],
        ['city' => 'Kuala Lumpur', 'zone' => 'Asia/Kuala_Lumpur', 'flag' => '🇲🇾'],
        ['city' => 'Jakarta',      'zone' => 'Asia/Jakarta',      'flag' => '🇮🇩'],
        ['city' => 'Hong Kong',    'zone' => 'Asia/Hong_Kong',    'flag' => '🇭🇰'],
        ['city' => 'Beijing',      'zone' => 'Asia/Shanghai',     'flag' => '🇨🇳'],
        ['city' => 'Tokyo',        'zone' => 'Asia/Tokyo',        'flag' => '🇯🇵'],
        ['city' => 'Seoul',        'zone' => 'Asia/Seoul',        'flag' => '🇰🇷'],
        ['city' => 'Sydney',       'zone' => 'Australia/Sydney',  'flag' => '🇦🇺'],
        ['city' => 'Auckland',     'zone' => 'Pacific/Auckland',  'flag' => '🇳🇿'],
        ['city' => 'Riyadh',       'zone' => 'Asia/Riyadh',       'flag' => '🇸🇦'],
        ['city' => 'Dubai',        'zone' => 'Asia/Dubai',        'flag' => '🇦🇪'],
        ['city' => 'Doha',         'zone' => 'Asia/Qatar',        'flag' => '🇶🇦'],
        ['city' => 'Kuwait City',  'zone' => 'Asia/Kuwait',       'flag' => '🇰🇼'],
        ['city' => 'Muscat',       'zone' => 'Asia/Muscat',       'flag' => '🇴🇲'],
        ['city' => 'Istanbul',     'zone' => 'Europe/Istanbul',   'flag' => '🇹🇷'],
        ['city' => 'Moscow',       'zone' => 'Europe/Moscow',     'flag' => '🇷🇺'],
        ['city' => 'Rome',         'zone' => 'Europe/Rome',       'flag' => '🇮🇹'],
        ['city' => 'Paris',        'zone' => 'Europe/Paris',      'flag' => '🇫🇷'],
        ['city' => 'Berlin',       'zone' => 'Europe/Berlin',     'flag' => '🇩🇪'],
        ['city' => 'London',       'zone' => 'Europe/London',     'flag' => '🇬🇧'],
        ['city' => 'New York',     'zone' => 'America/New_York',  'flag' => '🇺🇸'],
        ['city' => 'Toronto',      'zone' => 'America/Toronto',   'flag' => '🇨🇦'],
        ['city' => 'Chicago',      'zone' => 'America/Chicago',   'flag' => '🇺🇸'],
        ['city' => 'Los Angeles',  'zone' => 'America/Los_Angeles','flag'=> '🇺🇸'],
        ['city' => 'São Paulo',    'zone' => 'America/Sao_Paulo', 'flag' => '🇧🇷'],
        ['city' => 'Johannesburg', 'zone' => 'Africa/Johannesburg','flag'=> '🇿🇦'],
        ['city' => 'UTC',          'zone' => 'UTC',               'flag' => '🌐'],
    ];
}

/** Upcoming national days, used by the countdown timers. */
function bd_countdowns(): array
{
    $now  = new DateTimeImmutable('now', new DateTimeZone(BD_TIMEZONE));
    $year = (int) $now->format('Y');

    $days = [
        ['name' => 'Language Martyrs’ Day', 'md' => '02-21', 'emoji' => '🕊️'],
        ['name' => 'Independence Day',      'md' => '03-26', 'emoji' => '🇧🇩'],
        ['name' => 'Pohela Boishakh',       'md' => '04-14', 'emoji' => '🎉'],
        ['name' => 'Victory Day',           'md' => '12-16', 'emoji' => '🏅'],
        ['name' => 'New Year',              'md' => '01-01', 'emoji' => '✨'],
    ];

    $out = [];
    foreach ($days as $day) {
        $target = new DateTimeImmutable("$year-{$day['md']} 00:00:00", new DateTimeZone(BD_TIMEZONE));
        if ($target < $now) {
            $target = $target->modify('+1 year');
        }
        $day['target'] = $target->format('c');
        $day['days']   = (int) $now->diff($target)->format('%a');
        $out[] = $day;
    }

    usort($out, static fn (array $a, array $b): int => $a['days'] <=> $b['days']);
    return $out;
}

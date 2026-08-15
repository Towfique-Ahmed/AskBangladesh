<?php
/** Daily prayer times for any Bangladeshi district. */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/services.php';

$districtName = trim((string) ($_GET['district'] ?? 'Dhaka'));
$school       = ($_GET['school'] ?? 'hanafi') === 'shafi' ? 'shafi' : 'hanafi';
$dateParam    = (string) ($_GET['date'] ?? '');
$timestamp    = $dateParam !== '' ? strtotime($dateParam) : time();

if ($timestamp === false) {
    bd_json_response(['error' => 'Invalid date. Use YYYY-MM-DD.'], 400);
}

$match = null;
foreach (bd_districts() as $district) {
    if (strcasecmp($district['name'], $districtName) === 0) {
        $match = $district;
        break;
    }
}

if ($match === null) {
    bd_json_response(['error' => 'Unknown district: ' . $districtName], 404);
}

bd_json_response([
    'district' => $match['name'],
    'division' => $match['division'],
    'lat'      => $match['lat'],
    'lon'      => $match['lon'],
    'date'     => date('Y-m-d', $timestamp),
    'method'   => 'University of Islamic Sciences, Karachi (Fajr 18°, Isha 18°)',
    'school'   => $school === 'hanafi' ? 'Hanafi (Asr shadow ratio 2)' : 'Shafi (Asr shadow ratio 1)',
    'timezone' => 'Asia/Dhaka (UTC+06:00)',
    'times'    => bd_prayer_times((float) $match['lat'], (float) $match['lon'], $timestamp, $school),
]);

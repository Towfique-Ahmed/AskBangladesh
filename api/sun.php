<?php
/** Sunrise, sunset and related solar times for any Bangladeshi district. */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/services.php';

$districtName = trim((string) ($_GET['district'] ?? 'Dhaka'));
$dateParam    = (string) ($_GET['date'] ?? '');
$timestamp    = $dateParam !== '' ? strtotime($dateParam) : time();

if ($timestamp === false) {
    bd_json_response(['error' => 'Invalid date. Use YYYY-MM-DD.'], 400);
}

$match = null;
foreach (bd_districts() as $district) {
    if (strcasecmp($district['name'], $districtName) === 0
        || bd_slug($district['name']) === bd_slug($districtName)) {
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
    'timezone' => 'Asia/Dhaka (UTC+06:00)',
    'method'   => 'Solar position algorithm; sunrise and sunset at -0.833° to allow for refraction and the solar disc',
    'times'    => bd_sun_times((float) $match['lat'], (float) $match['lon'], $timestamp),
]);
